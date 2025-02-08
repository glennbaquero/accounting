<?php

namespace App\Http\Controllers\VendorPayments;

use Throwable;
use Carbon\Carbon;
use App\Models\Users\User;
use App\Helpers\CodeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PurchaseOrders\VendorPayment;
use App\Models\Journals\VendorPaymentJournal;
use Illuminate\Validation\ValidationException;
use App\Models\PurchaseOrders\VendorPaymentLine;
use App\Http\Requests\VendorPayments\VendorPaymentStoreRequest;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;

class VendorPaymentController extends Controller
{
    public function index() {
        return view('vendor-payments.index', [
            'clients' => User::getClients()
        ]);
    }

    public function create($vendor_invoice = null){ 

        $code = $vendor_invoice;

        return view('vendor-payments.create', [
            'vendor_payment_number' => 'VP-' . CodeHelper::generateNumberCode(),
            'vendor_invoice_number' => $code,
        ]);
    }

    public function store(VendorPaymentStoreRequest $request) {

        
        $code = DocumentCodeControl::generateCode($request->client_id, 3, 'App\Models\PurchaseOrders\VendorPayment');
        if($code) {
            $request['vendor_invoice_number'] = $code;
        }

        if(!$request->itemLines) {
            throw ValidationException::withMessages(['purchase order lines' => 'Please add vendor payment_lines']);
        }
        
        try {
            DB::beginTransaction();
            $admin_id = auth()->user()->id;
            $request['created_by'] = $admin_id;
            $request['updated_by'] = $admin_id;

            $item = VendorPayment::store($request);

            $message = "You have successfully created {$item->vendor_payment_number}";
            $redirect = $item->renderShowUrl();

            if ($request->itemLines) {
                foreach($request->itemLines as $line) {
                    VendorPaymentLine::create([
                        'vendor_invoice_line_number' => $line['vendor_invoice_line_number'],
                        'payment_line_number' => $line['payment_line_number'] ?? null,
                        'vendor_account' => $item->vendor_account,
                        'invoice_account' => $item->invoice_account,
                        'vendor_payment_id' => $item->id,
                        
                        'purchase_order_number' => $line['purchase_order_number'] ?? null,
                        'procurement_category' => $line['procurement_category'], 
                        'status' => 1,
                        
                        // Product Raw Data
                        'product' => $line['product'],
    
                        // Variant Information
                        'price_per_unit' => $line['price_per_unit'] ? $line['price_per_unit'] : $line['variant']['unit_price'] ?? 0,
    
                        // Variant Raw Data
                        'variant' => $line['variant'],
    
                        // Cost Information
                        'quantity' => $line['quantity'], 
                        'amount' => $line['amount'], 
                        'discount' => $line['discount'],
                        'discount_percentage' => $line['discount_percentage'],
    
                        // Financial Dimension
                        'sales_tax_group' => $line['dimension_value_cost_center_id'],
                        'dimension_value_cost_center_id' => $line['dimension_value_cost_center_id'], 
                        'dimension_value_department_id' => $line['dimension_value_department_id'], 
                        'dimension_value_expense_purpose_id' => $line['dimension_value_expense_purpose_id'], 
    
                        'product_id' => $line['product_id'] ?? null, 
                        'variant_id' => $line['variant_id'] ?? null, 
                        'charges_on_purchases' => $line['charge_on_purchase'],
                        'specification_id' => $line['specification_id'],
    
                        'service_id' => $line['service_id'],
                        'service_task' => $line['service_task'],
                        'service_task_details' => $line['service_task_details'],
                        'rpm_method' => $line['rpm_method'],
                        'number_of_hours' => $line['number_of_hours'],
    
                        'company_id' => $item->company_id,
                        'client_id' => $item->client_id,
                        'created_by' => auth()->user()->id,
                     ]);
                }
            }
            DB::commit();
        } catch(Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
        }

        return [
            'message' => $message,
            'redirect' => $redirect
        ];
    }

    public function show($id) {
        $item = VendorPayment::withTrashed()->findOrFail($id);
        return view('vendor-payments.show', [
            'item' => $item
        ]);
    }

    // TODO: it's doing too much! refactor the posting of payment to another function 'post_function'
    public function update(VendorPaymentStoreRequest $request, $id) {
        try {
            DB::beginTransaction();

            $admin_id = auth()->user()->id;
            $name = User::find($admin_id)->renderName();
            $request['updated_by'] = $admin_id;
            $request['updated_at'] = now();
            $item = VendorPayment::withTrashed()->findOrFail($id);
            $item = VendorPayment::store($request, $item);
    
            $message = "You have successfully updated {$item->vendor_payment_number}";
    
            $dateNow = Carbon::now();
    
            foreach($request->itemLines as $vendor_payment_line) {
                $line_id = isset($vendor_payment_line['id']) ? $vendor_payment_line['id'] : 0;
                if ($line_id != 0) {
                    $vendor_payment_line['updated_by'] = $admin_id;
                    $vendor_payment_line['updated_by_name'] = $name;
                    $vendor_payment_line['updated_at'] = $dateNow;
                } else {
                    $vendor_payment_line['created_by'] = $admin_id;
                    $vendor_payment_line['created_by_name'] = $name;
                    $vendor_payment_line['created_at'] = $dateNow;
                }
    
                unset($vendor_payment_line['created_by_user']);
                unset($vendor_payment_line['updated_by_user']);
                unset($vendor_payment_line['vendor_invoice_number']);
                unset($vendor_payment_line['less_discount']);
                unset($vendor_payment_line['add_charge']);
        
                if (isset($vendor_payment_line['approved_payment'])) {
                    if (! isset($vendor_payment_line['approved_by_id'])) {
    
                        $vendor_payment_line['approved_by_id'] = $admin_id;
                        $vendor_payment_line['approved_date'] = $dateNow;
                        $vendor_payment_line['approved_by_name'] = $name;
                    }
                }
                
                $vendor_payment_line['service_id'] = $item->service_id;
                $vendor_payment_line['service_task'] = $item->service_task;
                $vendor_payment_line['service_task_details'] = $item->service_task_details;
                $vendor_payment_line['rpm_method'] = $item->rpm_method;
                $vendor_payment_line['number_of_hours'] = $item->number_of_hours;
                
                VendorPaymentLine::updateOrCreate(
                    ['id' => $line_id],
                    $vendor_payment_line
                );
            }
    
            // reject vendor payment line if any
            if ($request->rejectItemLines) {
                foreach($request->rejectItemLines as $itemLine) {
                    $id = $itemLine['id'];
                    $itemLine['rejected_by_id'] = $admin_id;
                    $itemLine['rejected_date'] = $dateNow;
                    $itemLine['rejected_by_name'] = $name;
                    unset($itemLine['created_by_user']);
                    unset($itemLine['updated_by_user']);
                    $vendor_payment_line = VendorPaymentLine::withTrashed()->findOrFail($id);
                    $vendor_payment_line->update($itemLine);
    
                }
            }
    
            // archive vendor payment lines if any
            if ($request->removeItemLines) {
                foreach($request->removeItemLines as $id) {
                    $vendor_payment_line = VendorPaymentLine::find($id);
                    $vendor_payment_line->archive();
                }
            }

            DB::commit();

            return [ 'message' => $message ];
        } catch(Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
        }
    }

    public function archive($id)
    {
        $item = VendorPayment::withTrashed()->findOrFail($id);
        $item->archive();

        return [
            'message' => "You have successfully archived {$item->vendor_payment_number}"
        ];
    }

    public function restore($id)
    {
        $item = VendorPayment::withTrashed()->findOrFail($id);
        $item->unarchive();

        return [
            'message' => "You have successfully restored {$item->sales_order_number}"
        ];
    }

    public function approved(Request $request, $id) 
    {
        $item = VendorPayment::withTrashed()->findOrFail($id);
        $message = "You have successfully approved the {$item->vendor_payment_number}";

        $admin_id = auth()->user()->id;
        $name = User::find($admin_id)->renderName();

        $item->update([
            'approved_date' => now(),
            'approved_by' => $admin_id,
            'approved_payment' => true,
            'approved_by_name' => $name,
            'updated_by' => $admin_id
        ]);

        return response()->json([
             'message' => $message,
        ]);
    }

    public function posted(Request $request, $id) 
    {
        $item = VendorPayment::withTrashed()->findOrFail($id);

        if ($item->posting_date) {
            throw ValidationException::withMessages(['Message' => 'Transaction was already posted before!']);
        }

        // $redirect = route('po-invoice-approval-journals.create').'#invoice_approval_journal';

        $approved_journal_entry = VendorPaymentJournal::where('cost_center', $item->cost_center->financial_dimension_value_code)
            ->where('department', $item->department->financial_dimension_value_code)
            ->where('expense_purpose', $item->expense_purpose->financial_dimension_value_code)
            ->where('method_of_payment_id', $item->method_of_payment_id)
            ->where('journal_name', 'Approved Payment')
            ->where('client_id', $item->client_id)
            ->where('company_id', auth()->user()->company_id)
            ->first();

        $rejected_journal_entry = VendorPaymentJournal::where('cost_center', $item->cost_center->financial_dimension_value_code)
            ->where('department', $item->department->financial_dimension_value_code)
            ->where('expense_purpose', $item->expense_purpose->financial_dimension_value_code)
            ->where('method_of_payment_id', $item->method_of_payment_id)
            ->where('journal_name', 'Not Approved Payment')
            ->where('client_id', $item->client_id)
            ->where('company_id', auth()->user()->company_id)
            ->first();

        try {
            DB::beginTransaction();

            $admin_id = auth()->user()->id;
            $name = User::find($admin_id)->renderName();

            if (! $approved_journal_entry) {
                $count = VendorPaymentJournal::withTrashed()->count();
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                $approved_journal_entry = VendorPaymentJournal::create([
                    'vendor_payment_journal_number' => $number,
                    'method_of_payment_id' => $item->method_of_payment_id,
                    'invoice_journal_batch_number' => 'JBN',
                    'journal_name' => 'Approved Payment',
                    'description' => 'Auto generated to serve as approval journal for approved payments',
                    'journal_status' => 'Open',
                    'journal_type' => 'Payment Approval Journal',
                    'account_type' => 'Vendor',
                    'document' => 'VENDOR PAYMENT',
                    'cost_center' => $item->cost_center->financial_dimension_value_code,
                    'expense_purpose' => $item->expense_purpose->financial_dimension_value_code,
                    'department' => $item->department->financial_dimension_value_code,
                    'journal_name_number' => 'NN',
                    'protest_settlements' => '---',
                    'bank_account' => $item->bank_account,
                    'used_by_user' => 'UBU',
                    'created_by' => $name,
                    'client_id' => $item->client_id,
                    'company_id' => $item->company_id
                ]);
            }

            if (! $rejected_journal_entry) {
                $count = VendorPaymentJournal::withTrashed()->count();
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                $rejected_journal_entry = VendorPaymentJournal::create([
                    'vendor_payment_journal_number' => $number,
                    'method_of_payment_id' => $item->method_of_payment_id,
                    'invoice_journal_batch_number' => 'JBN',
                    'journal_name' => 'Not Approved Payment',
                    'description' => 'Auto generated to serve as approval journal for not approved payments',
                    'journal_status' => 'Open',
                    'journal_type' => 'Payment Approval Journal',
                    'account_type' => 'Vendor',
                    'document' => 'VENDOR PAYMENT',
                    'cost_center' => $item->cost_center->financial_dimension_value_code,
                    'expense_purpose' => $item->expense_purpose->financial_dimension_value_code,
                    'department' => $item->department->financial_dimension_value_code,
                    'journal_name_number' => 'NN',
                    'protest_settlements' => '---',
                    'bank_account' => $item->bank_account,
                    'used_by_user' => 'UBU',
                    'created_by' => $name,
                    'client_id' => $item->client_id,
                    'company_id' => $item->company_id
                ]);
            }

            if ($item->vendor_payment_lines()->whereNotNull('approved_by_id')->count() > 0) {
                $item->createPaymentJournalEntry($approved_journal_entry, 'debit_amount', 'approved_payment');
                $item->createPaymentJournalEntry($approved_journal_entry, 'credit_amount', 'approved_payment');

                $redirect = $approved_journal_entry->renderShowUrl().'#posted';
            }

            if ($item->vendor_payment_lines()->whereNotNull('rejected_by_id')->count() > 0) {
                $item->createPaymentJournalEntry($rejected_journal_entry, 'debit_amount', 'is_rejected');
                $item->createPaymentJournalEntry($rejected_journal_entry, 'credit_amount', 'is_rejected');
                if (! $redirect) {
                    $redirect = $rejected_journal_entry->renderShowUrl().'#posted';
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
        }
        
        if (!$approved_journal_entry && !$rejected_journal_entry) {
            DB::rollBack();
            throw ValidationException::withMessages(['Payment Journal Entry' => 'No Payment Journal Entry match found, please create one first']);
        }

        return response()->json([
            'message' => "You have successfully posted {$item->customer_invoice_number}",
            'redirect' => $redirect
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $item = VendorPayment::withTrashed()->findOrFail($id);
        $item->update([
            'is_cancelled' => true,
            'cancelled_on' => now(),
            'cancelled_by' => auth()->user()->id,
        ]);

        return [
            'message' => "You have successfully cancelled the #{$item->sales_order_number}"
        ];
    }

}
