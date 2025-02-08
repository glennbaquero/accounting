<?php

namespace App\Http\Controllers\CustomerPayments;

use Throwable;
use Carbon\Carbon;
use App\Models\Users\User;
use App\Helpers\CodeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SalesOrders\CustomerPayment;
use Illuminate\Validation\ValidationException;
use App\Models\Journals\CustomerPaymentJournal;
use App\Models\SalesOrders\CustomerPaymentLine;
use App\Http\Requests\CustomerPayments\CustomerPaymentStoreRequest;

class CustomerPaymentController extends Controller
{
    public function index() {
        return view('customer-payments.index', [
            'clients' => User::getClients()
        ]);
    }

    public function create($customer_invoice = null){ 

        $code = $customer_invoice;

        return view('customer-payments.create', [
            'customer_payment_number' => 'CP-' . CodeHelper::generateNumberCode(),
            'customer_invoice_number' => $code,
        ]);
    }

    public function store(CustomerPaymentStoreRequest $request) {
        
        if(!$request->itemLines) {
            throw ValidationException::withMessages(['purchase order lines' => 'Please add customer payment_lines']);
        }

        try {
            DB::beginTransaction();
            $admin_id = auth()->user()->id;
            $request['created_by'] = $admin_id;
            $request['updated_by'] = $admin_id;

            $item = CustomerPayment::store($request);

            $message = "You have successfully created {$item->customer_payment_number}";
            $redirect = $item->renderShowUrl();

            if ($request->itemLines) {
                foreach($request->itemLines as $customer_payment_line) {
                    $customer_payment_line['customer_payment_id'] = $item->id;
                    $customer_payment_line['created_by'] = $admin_id;
                    $customer_payment_line['created_at'] = Carbon::now();
                    $customer_payment_line['company_id'] = auth()->user()->company_id;
                    $customer_payment_line['client_id'] = $item->client_id;
                    unset($customer_payment_line['created_by_user']);
                    unset($customer_payment_line['updated_by_user']);
                    $customer_payment_line['service_id'] = $item->service_id;
                    $customer_payment_line['service_task'] = $item->service_task;
                    $customer_payment_line['service_task_details'] = $item->service_task_details;
                    $customer_payment_line['rpm_method'] = $item->rpm_method;
                    $customer_payment_line['number_of_hours'] = $item->number_of_hours;
                    CustomerPaymentLine::create($customer_payment_line);
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
        $item = CustomerPayment::withTrashed()->findOrFail($id);
        return view('customer-payments.show', [
            'item' => $item
        ]);
    }

    public function update(CustomerPaymentStoreRequest $request, $id) {
        try {
            DB::beginTransaction();
            $admin_id = auth()->user()->id;
            $name = User::find($admin_id)->renderName();
            $request['updated_by'] = $admin_id;
            $request['updated_at'] = now();
            $item = CustomerPayment::withTrashed()->findOrFail($id);
            $item = CustomerPayment::store($request, $item);

            $message = "You have successfully updated {$item->customer_payment_number}";
            $dateNow = Carbon::now();

            foreach($request->itemLines as $customer_payment_line) {
                $line_id = isset($customer_payment_line['id']) ? $customer_payment_line['id'] : 0;
                if ($line_id != 0) {
                    $customer_payment_line['updated_by'] = $admin_id;
                    $customer_payment_line['updated_by_name'] = $name;
                    $customer_payment_line['updated_at'] = $dateNow;
                } else {
                    $customer_payment_line['created_by'] = $admin_id;
                    $customer_payment_line['created_by_name'] = $name;
                    $customer_payment_line['created_at'] = $dateNow;
                }
    
                unset($customer_payment_line['created_by_user']);
                unset($customer_payment_line['updated_by_user']);
    
                if (isset($customer_payment_line['approved_payment'])) {
                    if (! isset($customer_payment_line['approved_by_id'])) {
    
                        $customer_payment_line['approved_by_id'] = $admin_id;
                        $customer_payment_line['approved_date'] = $dateNow;
                        $customer_payment_line['approved_by_name'] = $name;
                    }
                }

                $customer_payment_line['service_id'] = $item->service_id;
                $customer_payment_line['service_task'] = $item->service_task;
                $customer_payment_line['service_task_details'] = $item->service_task_details;
                $customer_payment_line['rpm_method'] = $item->rpm_method;
                $customer_payment_line['number_of_hours'] = $item->number_of_hours;

                CustomerPaymentLine::updateOrCreate(
                    ['id' => $line_id],
                    $customer_payment_line
                );
            }
    
            // reject customer payment line if any
            if ($request->rejectItemLines) {
                foreach($request->rejectItemLines as $itemLine) {
                    $id = $itemLine['id'];
                    $itemLine['rejected_by_id'] = $admin_id;
                    $itemLine['rejected_date'] = $dateNow;
                    $itemLine['rejected_by_name'] = $name;
                    unset($itemLine['created_by_user']);
                    unset($itemLine['updated_by_user']);
                    $customer_payment_line = CustomerPaymentLine::withTrashed()->findOrFail($id);
                    $customer_payment_line->update($itemLine);
    
                }
            }
    
            // archive customer payment lines if any
            if ($request->removeItemLines) {
                foreach($request->removeItemLines as $id) {
                    $customer_payment_line = CustomerPaymentLine::find($id);
                    $customer_payment_line->archive();
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
        $item = CustomerPayment::withTrashed()->findOrFail($id);
        $item->archive();

        return [
            'message' => "You have successfully archived {$item->customer_payment_number}"
        ];
    }

    public function restore($id)
    {
        $item = CustomerPayment::withTrashed()->findOrFail($id);
        $item->unarchive();

        return [
            'message' => "You have successfully restored {$item->sales_order_number}"
        ];
    }

    public function approved(Request $request, $id) 
    {
        $item = CustomerPayment::withTrashed()->findOrFail($id);
        $message = "You have successfully approved the {$item->customer_payment_number}";

        $admin_id = auth()->user()->id;
        $name = User::find($admin_id)->renderName();

        $item->update([
            'approved_date' => now(),
            'approved_by_id' => $admin_id,
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
        $item = CustomerPayment::withTrashed()->findOrFail($id);

        if ($item->posting_date) {
            throw ValidationException::withMessages(['Message' => 'Transaction was already posted before!']);
        }

        // $redirect = route('po-invoice-approval-journals.create').'#invoice_approval_journal';

        $approved_journal_entry = CustomerPaymentJournal::where('cost_center', $item->cost_center->financial_dimension_value_code)
            ->where('department', $item->department->financial_dimension_value_code)
            ->where('expense_purpose', $item->expense_purpose->financial_dimension_value_code)
            ->where('method_of_payment_id', $item->method_of_payment_id)
            ->where('journal_name', 'Approved Payment')
            ->where('client_id', $item->client_id)
            ->where('company_id', auth()->user()->company_id)
            ->first();

        $rejected_journal_entry = CustomerPaymentJournal::where('cost_center', $item->cost_center->financial_dimension_value_code)
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
                $count = CustomerPaymentJournal::withTrashed()->count();
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                $approved_journal_entry = CustomerPaymentJournal::create([
                    'customer_payment_journal_number' => $number,
                    'method_of_payment_id' => $item->method_of_payment_id,
                    'invoice_journal_batch_number' => 'JBN',
                    'journal_name' => 'Approved Payment',
                    'description' => 'Auto generated to serve as approval journal for approved payments',
                    'journal_status' => 'Open',
                    'journal_type' => 'Payment Approval Journal',
                    'account_type' => 'Customer',
                    'document' => 'CUSTOMER PAYMENT',
                    'cost_center' => $item->cost_center->financial_dimension_value_code,
                    'expense_purpose' => $item->expense_purpose->financial_dimension_value_code,
                    'department' => $item->department->financial_dimension_value_code,
                    'journal_name_number' => 'NN',
                    'bank_account' => $item->bank_account,
                    'used_by_user' => 'UBU',
                    'created_by' => $name,
                    'client_id' => $item->client_id,
                    'company_id' => $item->company_id
                ]);
            }

            if (! $rejected_journal_entry) {
                $count = CustomerPaymentJournal::withTrashed()->count();
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                $rejected_journal_entry = CustomerPaymentJournal::create([
                    'customer_payment_journal_number' => $number,
                    'method_of_payment_id' => $item->method_of_payment_id,
                    'invoice_journal_batch_number' => 'JBN',
                    'journal_name' => 'Not Approved Payment',
                    'description' => 'Auto generated to serve as approval journal for not approved payments',
                    'journal_status' => 'Open',
                    'journal_type' => 'Payment Approval Journal',
                    'account_type' => 'Customer',
                    'document' => 'CUSTOMER PAYMENT',
                    'cost_center' => $item->cost_center->financial_dimension_value_code,
                    'expense_purpose' => $item->expense_purpose->financial_dimension_value_code,
                    'department' => $item->department->financial_dimension_value_code,
                    'journal_name_number' => 'NN',
                    'bank_account' => $item->bank_account,
                    'used_by_user' => 'UBU',
                    'created_by' => $name,
                    'client_id' => $item->client_id,
                    'company_id' => $item->company_id
                ]);
            }

            if ($item->customer_payment_lines()->whereNotNull('approved_by_id')->count() > 0) {
                $item->createPaymentJournalEntry($approved_journal_entry, 'debit_amount', 'approved_payment');
                $item->createPaymentJournalEntry($approved_journal_entry, 'credit_amount', 'approved_payment');

                $redirect = $approved_journal_entry->renderShowUrl().'#posted';
            }

            if ($item->customer_payment_lines()->whereNotNull('rejected_by_id')->count() > 0) {
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
}
