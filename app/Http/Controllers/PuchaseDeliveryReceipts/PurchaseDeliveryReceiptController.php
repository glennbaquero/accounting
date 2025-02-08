<?php

namespace App\Http\Controllers\PurchaseDeliveryReceipts;

use Throwable;
use App\Models\Users\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Invoices\PurchaseDeliveryReceipt;
use App\Models\Invoices\PurchaseDeliveryReceiptLine;
use App\Models\PurchaseOrders\PurchaseOrder;
use Illuminate\Validation\ValidationException;
use App\Models\Journals\InvoiceApprovalJournal;
use App\Http\Requests\Invoices\VendorInvoiceStoreRequest;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;
use PDF;

class PurchaseDeliveryReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase-delivery-receipts.index', [
            'clients' => User::getClients()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($vendor_invoice = null)
    {
        $vendor_invoice = VendorInvoice::find($vendor_invoice);

        return view('purchase-delivery-receipts.create', [
            'vendor_invoice' => $vendor_invoice
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorInvoiceStoreRequest $request, $purchase_order_number)
    {

        $code = DocumentCodeControl::generateCode($request->client_id, 2, 'App\Models\Invoices\PurchaseDeliveryReceipt');
        if($code) {
            $request['vendor_invoice_number'] = $code;
        }

        if ($purchase_order_number == 'null') {
            $purchase_order_number = $request->purchase_order_number;
        }

        if(!$request['vendor_invoice_lines']) {
           throw ValidationException::withMessages(['vendor invoice lines' => 'Please add vendor invoice lines']);
        }

        try {
            DB::beginTransaction();
            $request['invoice_date'] = $request['invoice_date'] ?? now();
            // $request['invoiced_by'] = auth()->user()->id;
            $request['created_by'] = auth()->user()->id;
    
            $item = PurchaseDeliveryReceipt::store($request);
            $vendor_invoice_lines = $request['vendor_invoice_lines'];
            
            if($vendor_invoice_lines) {
                foreach ($vendor_invoice_lines as $line) {
                    PurchaseDeliveryReceiptLine::create([
                        'purchase_delivery_receipt_number' => $line['purchase_delivery_receipt_number'],
                        'purchase_delivery_receipt_line_number' => $line['purchase_delivery_receipt_line_number'] ?? null,
                        'vendor_account' => $item->vendor_account,
                        'receive_now_quantity' => $line['receive_now_quantity'] ?? 0,
                        'invoice_account' => $item->invoice_account,
                        'vendor_name' => $item->vendor_name,
                        'vendor_invoice_number' => $item->vendor_invoice_number,
                        'purchase_order_number' => $line['purchase_order_number'] ?? null,
                        'line_number' => $item->id, 
                        'line_status' => $line['line_status'],
                        'procurement_category' => $line['procurement_category'], 
                        
                        // Product Information
                        'item_number' => $line['product']['product_number'] ?? null,
                        'item_name' => $line['product']['name'] ?? null,
                        'batch_number' => $line['product']['batch_number'] ?? null, 
                        'serial_number' => $line['product']['serial_number'] ?? null,
                        'size' => isset($line['size']) ?? null,
                        'color' => isset($line['color']) ?? null,

                        // Product Raw Data
                        'product' => $line['product'],

                        // Variant Information
                        'variant_number' => $line['variant']['variant_number'] ?? null, 
                        'variant_name' => $line['variant']['name'] ?? null, 
                        'unit_price' => $line['unit_price'] ? $line['unit_price'] : $line['variant']['unit_price'] ?? 0,

                        // Variant Raw Data
                        'variant' => $line['variant'],

                        // Cost Information
                        'quantity' => $line['quantity'], 
                        'amount' => $line['amount'], 
                        'discount' => $line['discount'],
                        'discount_percentage' => $line['discount_percentage'],

                        // Financial Dimension
                        'sales_tax_group' => $item['sales_tax_group'],
                        'cost_center_id' => $line['cost_center_id'], 
                        'department_id' => $line['department_id'], 
                        'expense_purpose_id' => $line['expense_purpose_id'], 

                        'product_id' => $line['product_id'] ?? null, 
                        'variant_id' => $line['variant_id'] ?? null, 
                        'charge_on_purchase' => $line['charge_on_purchase'],
                        'specification_id' => $line['specification_id'],

                        'service_id' => $line['service_id'],
                        'service_task' => $line['service_task'],
                        'service_task_details' => $line['service_task_details'],
                        'rpm_method' => $line['rpm_method'],
                        'number_of_hours' => $line['number_of_hours'],
                        'discount_id' => $line['discount_id'],

                        'charge_id' => $line['charge_id'],

                        'less_discount' => $line['less_discount'],
                        'cash_discount' => $line['cash_discount'],
                        'add_charge' => $line['add_charge'],
                        'charge' => $line['charge'],
                        'add_fee' => $line['add_fee'],
                        'fee' => $line['fee'],
                        'line_amount' => $line['line_amount'],
                        'additional_tax' => $line['additional_tax'],
                        'vat_amount' => $line['vat_amount'],
                        'line_vat' => $line['line_vat'],
                        'total_sales_vat_inclusive' => $line['total_sales_vat_inclusive'],

                        'company_id' => $item->company_id,
                        'client_id' => $item->client_id,

                        // Audit Information
                        'created_by' => auth()->user()->id,
                    ]);
                }
            }
            DB::commit();

            $message = "You have successfully created {$item->vendor_invoice_number}";
            $redirect = $item->renderShowUrl();
            return response()->json([
                'message' => $message,
                'redirect' => $redirect,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchaseDeliveryReceipt  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = PurchaseDeliveryReceipt::withTrashed()->findOrFail($id);

        return view('purchase-delivery-receipts.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseDeliveryReceipt  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(VendorInvoiceStoreRequest $request, $id)
    {
        if(!$request['vendor_invoice_lines']) {
			throw ValidationException::withMessages(['vendor invoice lines' => 'Please add vendor invoice lines']);
		}

        try {
            DB::beginTransaction();

            $item = PurchaseDeliveryReceipt::withTrashed()->findOrFail($id);
            $request['approved_by'] =  $item->approved_by; 
            // $request['invoiced_by'] =  $item->invoiced_by; 
            $request['updated_by'] = auth()->user()->id;
            $request['created_by'] =  $item->created_by;

            $item = PurchaseDeliveryReceipt::store($request, $item);

            $message = "You have successfully updated {$item->vendor_invoice_number}";
    
            $vendor_invoice_lines = $request['vendor_invoice_lines'];
    
            if($vendor_invoice_lines) {
                foreach ($vendor_invoice_lines as $line) {
                    if(! isset($line['id'])) {
                        $create = PurchaseDeliveryReceiptLine::create([
                            'receipt_line_number' => $line['purchase_delivery_receipt_number'],
                            'receipt_number' => $line['purchase_delivery_receipt_line_number'] ?? null,
                            'vendor_account' => $item->vendor_account,
                            'receive_now_quantity' => $line['receive_now_quantity'] ?? 0,
                            'invoice_account' => $item->invoice_account,
                            'vendor_name' => $item->vendor_name,
                            'vendor_invoice_number' => $item->vendor_invoice_number,
                            'purchase_order_number' => $line['purchase_order_number'] ?? null,
                            'line_number' => $item->id, 
                            'line_status' => $line['line_status'],
                            'procurement_category' => $line['procurement_category'], 
                            
                            // Product Information
                            'item_number' => $line['product']['product_number'] ?? null,
                            'item_name' => $line['product']['name'] ?? null,
                            'batch_number' => $line['product']['batch_number'] ?? null, 
                            'serial_number' => $line['product']['serial_number'] ?? null,
                            'size' => $line['size'],
                            'color' => $line['color'],
    
                            // Product Raw Data
                            'product' => $line['product'],
    
                            // Variant Information
                            'variant_number' => $line['variant']['variant_number'] ?? null, 
                            'variant_name' => $line['variant']['name'] ?? null, 
                            'unit_price' => $line['unit_price'] ? $line['unit_price'] : $line['variant']['unit_price'] ?? 0,
    
                            // Variant Raw Data
                            'variant' => $line['variant'],
    
                            // Cost Information
                            'quantity' => $line['quantity'], 
                            'amount' => $line['amount'], 
                            'discount' => $line['discount'],
                            'discount_percentage' => $line['discount_percentage'],
    
                            // Financial Dimension
                            'sales_tax_group' => $item['sales_tax_group'],
                            'cost_center_id' => $line['cost_center_id'], 
                            'department_id' => $line['department_id'], 
                            'expense_purpose_id' => $line['expense_purpose_id'], 
    
                            'product_id' => $line['product_id'] ?? null, 
                            'variant_id' => $line['variant_id'] ?? null, 
                            'charge_on_purchase' => $line['charge_on_purchase'],
                            'specification_id' => $line['specification_id'],

                            'service_id' => $line['service_id'],
                            'service_task' => $line['service_task'],
                            'service_task_details' => $line['service_task_details'],
                            'rpm_method' => $line['rpm_method'],
                            'number_of_hours' => $line['number_of_hours'],

                            'charge_id' => $line['charge_id'],
                            'discount_id' => $line['discount_id'],
                            'less_discount' => $line['less_discount'],
                            'cash_discount' => $line['cash_discount'],
                            'add_charge' => $line['add_charge'],
                            'charge' => $line['charge'],
                            'add_fee' => $line['add_fee'],
                            'fee' => $line['fee'],
                            'line_amount' => $line['line_amount'],
                            'additional_tax' => $line['additional_tax'],
                            'vat_amount' => $line['vat_amount'],
                            'line_vat' => $line['line_vat'],
                            'total_sales_vat_inclusive' => $line['total_sales_vat_inclusive'],
                            'company_id' => $item->company_id,
                            'client_id' => $item->client_id,
    
                            // Audit Information
                            'created_by' => auth()->user()->id,
                        ]); 
                    }else {
                        PurchaseDeliveryReceiptLine::findOrFail($line['id'])->update([
                            'receipt_line_number' => $line['purchase_delivery_receipt_number'],
                            'receipt_number' => $line['purchase_delivery_receipt_line_number'] ?? null,
                            'vendor_account' => $item->vendor_account,
                            'receive_now_quantity' => $line['receive_now_quantity'] ?? 0,
                            'invoice_account' => $item->invoice_account,
                            'vendor_name' => $item->vendor_name,
                            'vendor_invoice_number' => $item->vendor_invoice_number,
                            'purchase_order_number' => $line['purchase_order_number'] ?? null,
                            'line_number' => $item->id, 
                            'line_status' => $line['line_status'],
                            'procurement_category' => $line['procurement_category'], 
                            
                            // Product Information
                            'item_number' => $line['product']['product_number'] ?? null,
                            'item_name' => $line['product']['name'] ?? null,
                            'batch_number' => $line['product']['batch_number'] ?? null, 
                            'serial_number' => $line['product']['serial_number'] ?? null,
                            'size' => $line['size'],
                            'color' => $line['color'],
    
                            // Product Raw Data
                            'product' => $line['product'],
    
                            // Variant Information
                            'variant_number' => $line['variant']['variant_number'] ?? null, 
                            'variant_name' => $line['variant']['name'] ?? null, 
                            'unit_price' => $line['unit_price'] ? $line['unit_price'] : $line['variant']['unit_price'] ?? 0,
    
                            // Variant Raw Data
                            'variant' => $line['variant'],
    
                            // Cost Information
                            'quantity' => $line['quantity'], 
                            'amount' => $line['amount'], 
                            'discount' => $line['discount'],
                            'discount_percentage' => $line['discount_percentage'],
    
                            // Financial Dimension
                            'sales_tax_group' => $item['sales_tax_group'],
                            'cost_center_id' => $line['cost_center_id'], 
                            'department_id' => $line['department_id'], 
                            'expense_purpose_id' => $line['expense_purpose_id'], 
    
                            'product_id' => $line['product_id'] ?? null, 
                            'variant_id' => $line['variant_id'] ?? null, 
                            'charge_on_purchase' => $line['charge_on_purchase'],
                            'specification_id' => $line['specification_id'],
                            
                            'service_id' => $line['service_id'],
                            'service_task' => $line['service_task'],
                            'service_task_details' => $line['service_task_details'],
                            'rpm_method' => $line['rpm_method'],
                            'number_of_hours' => $line['number_of_hours'],

                            'charge_id' => $line['charge_id'],
                            'discount_id' => $line['discount_id'],
    
                            'company_id' => $item->company_id,
                            'client_id' => $item->client_id,

                            'less_discount' => $line['less_discount'],
                            'cash_discount' => $line['cash_discount'],
                            'add_charge' => $line['add_charge'],
                            'charge' => $line['charge'],
                            'add_fee' => $line['add_fee'],
                            'fee' => $line['fee'],
                            'line_amount' => $line['line_amount'],
                            'additional_tax' => $line['additional_tax'],
                            'vat_amount' => $line['vat_amount'],
                            'line_vat' => $line['line_vat'],
                            'total_sales_vat_inclusive' => $line['total_sales_vat_inclusive'],
    
                            // Audit Information
                            'updated_by' => auth()->user()->id,
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json([
                'message' => $message,
            ]);
        } catch(Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseDeliveryReceipt  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        try {
            DB::beginTransaction();
            $item = PurchaseDeliveryReceipt::withTrashed()->findOrFail($id);

            // remove related invoice voucher
            foreach($item->vouchers as $voucher) {
                $voucher->archive();
            }

            // remove related payments
            foreach($item->payments as $payment) {
                // remove related vouchers
                foreach($payment->vouchers as $voucher) {
                    $voucher->archive();
                }
                $payment->archive();
            }

            $item->archive();
            
            DB::commit();
            
            return response()->json([
                'message' => "You have successfully archived {$item->vendor_invoice_number}",
            ]);
        } catch(Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\PurchaseDeliveryReceipt  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {        
        try {
            DB::beginTransaction();
            $item = PurchaseDeliveryReceipt::withTrashed()->findOrFail($id);

            // restore related invoice voucher
            foreach($item->vouchers_with_trash as $voucher) {
                $voucher->unarchive();
            }

            // restore related payments
            foreach($item->payments_with_trashed as $payment) {
                // restore related vouchers
                foreach($payment->vouchers_with_trashed as $voucher) {
                    $voucher->unarchive();
                }
                $payment->unarchive();
            }

            $item->unarchive();
            
            DB::commit();
            
            return response()->json([
                'message' => "You have successfully restored {$item->vendor_invoice_number}",
            ]);
        } catch(Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
        }
    }

    public function approved(Request $request, $id) 
    {
        $item = PurchaseDeliveryReceipt::withTrashed()->findOrFail($id);
        $message = "You have successfully approved the {$item->vendor_invoice_number}";

        if(!$item->invoice_onhold_checkbox) {
            $item->update([
                'approved_date' => now(),
                'approved_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'is_already_confirmed' => true,
            ]);
        }else {
            throw ValidationException::withMessages(['On hold' => 'Invoice is currently onhold']);
        }

        return response()->json([
             'message' => $message,
        ]);
    }

    public function posted(Request $request, $id) 
    {
        $item = PurchaseDeliveryReceipt::withTrashed()->findOrFail($id);

        if ($item->posting_date) {
            throw ValidationException::withMessages(['message' => 'Transaction was already posted']);
        }

        if (!$item->vendor_posting_profile) {
            throw ValidationException::withMessages(['message' => 'Please assign posting profile']);
        }
        
        if (!$item->vendor_posting_profile->posting_lines->count()) {
            throw ValidationException::withMessages(['message' => 'Posting profile header has no posting lines']);
        }

        if ($item->settlement_type == 'None') {
            $item->update([
                'posting_date' => now(),
                'posted_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'posted_invoice_checkbox' => true,
            ]);
        }

        $approved_journal_entry = InvoiceApprovalJournal::where('cost_center', $item->cost_center->financial_dimension_value_code)
            ->where('department', $item->department->financial_dimension_value_code)
            ->where('expense_purpose', $item->expense_purpose->financial_dimension_value_code)
            ->where('journal_name', 'Approved Invoice')
            ->where('client_id', $item->client_id)
            ->where('company_id', auth()->user()->company_id)
            ->first();

        $rejected_journal_entry = InvoiceApprovalJournal::where('cost_center', $item->cost_center->financial_dimension_value_code)
            ->where('department', $item->department->financial_dimension_value_code)
            ->where('expense_purpose', $item->expense_purpose->financial_dimension_value_code)
            ->where('journal_name', 'Not Approved Invoice')
            ->where('client_id', $item->client_id)
            ->where('company_id', auth()->user()->company_id)
            ->first();

        try {
            DB::beginTransaction();

            $admin_id = auth()->user()->id;
            $name = User::find($admin_id)->renderName();

            if (! $approved_journal_entry) {
                $count = InvoiceApprovalJournal::where('company_id', auth()->user()->company_id)->withTrashed()->count();
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                $approved_journal_entry = InvoiceApprovalJournal::create([
                    'invoice_approval_journal_number' => $number,
                    'invoice_journal_batch_number' => 'JBN',
                    'journal_name' => 'Approved Invoice',
                    'description' => 'Auto generated to serve as approval journal for approved invoices',
                    'journal_status' => 'Open',
                    'journal_type' => 'Invoice Approval Journal',
                    'account_type' => 'Vendor',
                    'document' => 'VENDOR INVOICE',
                    'cost_center' => $item->cost_center->financial_dimension_value_code,
                    'expense_purpose' => $item->expense_purpose->financial_dimension_value_code,
                    'department' => $item->department->financial_dimension_value_code,
                    'journal_name_number' => 'NN',
                    'bank_account' => 'BA',
                    'used_by_user' => 'UBU',
                    'created_by' => $name,
                    'client_id' => $item->client_id,
                    'company_id' => $item->company_id
                ]);
            }

            if (! $rejected_journal_entry) {
                $count = InvoiceApprovalJournal::where('company_id', auth()->user()->company_id)->withTrashed()->count();
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                $rejected_journal_entry = InvoiceApprovalJournal::create([
                    'invoice_approval_journal_number' => $number,
                    'invoice_journal_batch_number' => 'JBN',
                    'journal_name' => 'Not Approved Invoice',
                    'description' => 'Auto generated to serve as approval journal for not approved invoices',
                    'journal_status' => 'Open',
                    'journal_type' => 'Invoice Approval Journal',
                    'account_type' => 'Vendor',
                    'document' => 'VENDOR INVOICE',
                    'cost_center' => $item->cost_center->financial_dimension_value_code,
                    'expense_purpose' => $item->expense_purpose->financial_dimension_value_code,
                    'department' => $item->department->financial_dimension_value_code,
                    'journal_name_number' => 'NN',
                    'bank_account' => 'BA',
                    'used_by_user' => 'UBU',
                    'created_by' => $name,
                    'client_id' => $item->client_id,
                    'company_id' => $item->company_id
                ]);
            }

            if ($item->vendor_invoice_lines()->whereNotNull('approved_on')->count() > 0) {
                $item->generateInvoiceJournal($approved_journal_entry, 'debit_amount', 'approved_on');
                $item->generateInvoiceJournal($approved_journal_entry, 'credit_amount', 'approved_on');

                $redirect = $approved_journal_entry->renderShowUrl().'#posted';
            }

            if ($item->vendor_invoice_lines()->whereNotNull('rejected_on')->count() > 0) {
                $item->generateInvoiceJournal($rejected_journal_entry, 'debit_amount', 'rejected_on');
                $item->generateInvoiceJournal($rejected_journal_entry, 'credit_amount', 'rejected_on');
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
            'message' => "You have successfully posted {$item->vendor_invoice_number}",
            'redirect' => $redirect
        ]);
    }

    public function generateInvoiceNumber() {
        // get date today
        $dt = date('Ymd');
        // invoice
        $invoice = 'invoice';
        // random string
        $f_rand = substr(md5(mt_rand()),0,5);
        $s_rand = substr(md5(mt_rand()),0,5);

        return $invoice . '-' . $dt . '-' . $f_rand . '-' .  $s_rand;
    }

    public function printPDF($id)
    {
        $vi = PurchaseDeliveryReceipt::find($id);
    
        return view('purchase-delivery-receipts.print',[
            'vi' => $vi,
            'vendor' => $vi->vendor,
            'vi_lines' => $vi->vendor_invoice_lines,
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $vi = PurchaseDeliveryReceipt::find($id);

        $vi->update([
            'is_cancelled' => true,
            'cancelled_on' => now(),
            'cancelled_by' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => "You have cancelled the invoice #{$item->vendor_invoice_number}",
        ]);
    }
}
