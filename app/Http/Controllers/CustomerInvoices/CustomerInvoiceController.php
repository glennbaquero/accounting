<?php

namespace App\Http\Controllers\CustomerInvoices;

use Throwable;
use App\Models\Users\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\SalesOrders\SalesOrder;
use App\Models\Invoices\CustomerInvoice;
use App\Models\Invoices\CustomerInvoiceLine;
use Illuminate\Validation\ValidationException;
use App\Models\Journals\CustomerInvoiceJournal;
use App\Http\Requests\Invoices\CustomerInvoiceStoreRequest;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;
use PDF;

class CustomerInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer-invoices.index', [
            'clients' => User::getClients()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($sales_order_number = null)
    {
        $sales_order = SalesOrder::where('sales_order_number', $sales_order_number)->first();

        return view('customer-invoices.create', [
            'sales_order' => $sales_order
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerInvoiceStoreRequest $request, $sales_order_number)
    {

        $code = DocumentCodeControl::generateCode($request->client_id, 5, 'App\Models\Invoices\CustomerInvoice');
        
        if($code) {
            $request['vendor_invoice_number'] = $code;
        }

        if ($sales_order_number == 'null') {
            $sales_order_number = $request->sales_order_number;
        }

        if(!$request['customer_invoice_lines']) {
           throw ValidationException::withMessages(['customer invoice lines' => 'Please add customer invoice lines']);
        }

        try {
            DB::beginTransaction();
            $request['invoice_date'] = $request['invoice_date'] ?? now();
            // $request['invoiced_by'] = auth()->user()->id;
            $request['created_by'] = auth()->user()->id;
    
            $item = CustomerInvoice::store($request);
            $customer_invoice_lines = $request['customer_invoice_lines'];
            
            if($customer_invoice_lines) {
                foreach ($customer_invoice_lines as $line) {
                    $item->customer_invoice_lines()->create([
                        'customer_invoice_line_number' => $line['customer_invoice_line_number'],
                        'sales_order_line_number' => $line['sales_order_line_number'] ?? null,
                        'customer_account' => $item->customer_account,
                        'invoice_account' => $item->invoice_account,
                        'customer_name' => $item->customer_name,
                        'customer_invoice_number' => $item->customer_invoice_number,
                        'sales_order_number' => $item->sales_order_number,
                        'line_number' => $item->id, 
                        'line_status' => $line['line_status'],
                        'sales_category' => $line['sales_category'], 
                        'receive_now_quantity' => $line['receive_now_quantity'] ?? 0, 
                        'description' => @$line['description'],
                        
                        // Product Information
                        'item_number' => $line['product']['product_number'] ?? null,
                        'item_name' => $line['product']['name'] ?? null,
                        'batch_number' => $line['product']['batch_number'] ?? null, 
                        'serial_number' => $line['product']['serial_number'] ?? null,
                        'size' => $line['variant']['size'],
                        'color' => $line['variant']['color'],
                        
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

                        'service_id' => $line['service_id'],
                        'service_task' => $line['service_task'],
                        'service_task_details' => $line['service_task_details'],
                        'rpm_method' => $line['rpm_method'],
                        'number_of_hours' => $line['number_of_hours'],

                        'product_id' => $line['product_id'] ?? null, 
                        'variant_id' => $line['variant_id'] ?? null, 
                        'charge_on_purchase' => $line['charge_on_purchase'],
                        'specification_id' => $line['specification_id'],

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
                }
            }
            DB::commit();

            $message = "You have successfully created {$item->customer_invoice_number}";
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
     * @param  \App\CustomerInvoice  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = CustomerInvoice::withTrashed()->findOrFail($id);

        return view('customer-invoices.show', [
            'item' => $item,
            'clients' => User::getClients()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerInvoice  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerInvoiceStoreRequest $request, $id)
    {

        if(!$request['customer_invoice_lines']) {
           throw ValidationException::withMessages(['customer invoice lines' => 'Please add customer invoice lines']);
        }
        try {
            DB::beginTransaction();

            $item = CustomerInvoice::withTrashed()->findOrFail($id);
            $message = "You have successfully updated {$item->customer_invoice_number}";
            $request['updated_by'] = auth()->user()->id;
            $request['updated_at'] = now();
            $request['created_by'] =  $item->created_by;
            $item = CustomerInvoice::store($request, $item);
    
            $lines = $request->customer_invoice_lines;
            if(isset($lines)) {
                foreach($lines as $line) {
                    if(!isset($line['id'])) {
                        $item->customer_invoice_lines()->create([
                            'customer_invoice_line_number' => $line['customer_invoice_line_number'],
                            'sales_order_line_number' => $line['sales_order_line_number'] ?? null,
                            'customer_account' => $item->customer_account,
                            'invoice_account' => $item->invoice_account,
                            'customer_name' => $item->customer_name,
                            'customer_invoice_number' => $item->customer_invoice_number,
                            'sales_order_number' => $item->sales_order_number,
                            'line_number' => $item->id, 
                            'line_status' => $line['line_status'],
                            'sales_category' => $line['sales_category'], 
                            'receive_now_quantity' => $line['receive_now_quantity'] ?? 0, 
                            'description' => $line['description'],
                            
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
                        CustomerInvoiceLine::findOrFail($line['id'])->update([
                            'customer_invoice_line_number' => $line['customer_invoice_line_number'],
                            'sales_order_line_number' => $line['sales_order_line_number'] ?? null,
                            'customer_account' => $item->customer_account,
                            'invoice_account' => $item->invoice_account,
                            'customer_name' => $item->customer_name,
                            'customer_invoice_number' => $item->customer_invoice_number,
                            'sales_order_number' => $item->sales_order_number,
                            'line_number' => $item->id, 
                            'line_status' => $line['line_status'],
                            'sales_category' => $line['sales_category'], 
                            'receive_now_quantity' => $line['receive_now_quantity'] ?? 0, 
                            'description' => $line['description'],
                            
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
			DB::rollback();
            throw ValidationException::withMessages(['Something went wrong!' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerInvoice  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CustomerInvoice::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->customer_invoice_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\CustomerInvoice  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = CustomerInvoice::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->customer_invoice_number}",
        ]);
    }

    public function confirmation(Request $request, $id) 
    {
        $item = CustomerInvoice::withTrashed()->findOrFail($id);
        $message = "You have successfully confirm the {$item->customer_invoice_number}";


        if(!$item->invoice_onhold_checkbox) {
            $item->update([
                'approved_date' => now(),
                'approved_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'is_already_confirmed' => true,
                'approved_invoice_checkbox' => true
            ]);
        }else {
            throw ValidationException::withMessages(['On hold' => 'Invoice is currently onhold']);
        }
        
        return response()->json([
             'message' => $message,
        ]);
    }

    public function post(Request $request, $id) 
    {
        $item = CustomerInvoice::withTrashed()->findOrFail($id);

        if ($item->posting_date) {
            throw ValidationException::withMessages(['Message' => 'Transaction was already posted before!']);
        }

        if (!$item->customer_posting_profile) {
            throw ValidationException::withMessages(['message' => 'Please assign posting profile']);
        }    

        if ($item->settlement_type == 'None') {
            $item->update([
                'posting_date' => now(),
                'posted_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'posted_invoice_checkbox' => true,
            ]);
            return [ 
                'message' => "You have successfully posted {$item->customer_invoice_number}",
                'redirect' => $item->renderShowUrl()
            ];
        }

        // $redirect = route('po-invoice-approval-journals.create').'#invoice_approval_journal';

        $approved_journal_entry = CustomerInvoiceJournal::where('cost_center', $item->cost_center->financial_dimension_value_code)
            ->where('department', $item->department->financial_dimension_value_code)
            ->where('expense_purpose', $item->expense_purpose->financial_dimension_value_code)
            ->where('journal_name', 'Approved Invoice')
            ->where('client_id', $item->client_id)
            ->where('company_id', auth()->user()->company_id)
            ->first();

        $rejected_journal_entry = CustomerInvoiceJournal::where('cost_center', $item->cost_center->financial_dimension_value_code)
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
                $count = CustomerInvoiceJournal::where('company_id', auth()->user()->company_id)->withTrashed()->count();
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                $approved_journal_entry = CustomerInvoiceJournal::create([
                    'customer_invoice_journal_number' => $number,
                    'invoice_journal_batch_number' => 'JBN',
                    'journal_name' => 'Approved Invoice',
                    'description' => 'Auto generated to serve as approval journal for approved invoices',
                    'journal_status' => 'Open',
                    'journal_type' => 'Invoice Approval Journal',
                    'account_type' => 'Customer',
                    'cost_center' => $item->cost_center->financial_dimension_value_code,
                    'expense_purpose' => $item->expense_purpose->financial_dimension_value_code,
                    'department' => $item->department->financial_dimension_value_code,
                    'document' => 'CUSTOMER INVOICE',
                    'journal_name_number' => 'NN',
                    'bank_account' => 'BA',
                    'used_by_user' => 'UBU',
                    'created_by' => $name,
                    'client_id' => $item->client_id,
                    'company_id' => $item->company_id
                ]);
            }

            if (! $rejected_journal_entry) {
                $count = CustomerInvoiceJournal::where('company_id', auth()->user()->company_id)->withTrashed()->count();
                $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                $rejected_journal_entry = CustomerInvoiceJournal::create([
                    'customer_invoice_journal_number' => $number,
                    'invoice_journal_batch_number' => 'JBN',
                    'journal_name' => 'Not Approved Invoice',
                    'description' => 'Auto generated to serve as approval journal for not approved invoices',
                    'journal_status' => 'Open',
                    'journal_type' => 'Invoice Approval Journal',
                    'account_type' => 'Customer',
                    'cost_center' => $item->cost_center->financial_dimension_value_code,
                    'expense_purpose' => $item->expense_purpose->financial_dimension_value_code,
                    'department' => $item->department->financial_dimension_value_code,
                    'document' => 'CUSTOMER INVOICE',
                    'journal_name_number' => 'NN',
                    'bank_account' => 'BA',
                    'used_by_user' => 'UBU',
                    'created_by' => $name,
                    'client_id' => $item->client_id,
                    'company_id' => $item->company_id
                ]);
            }

            if ($item->customer_invoice_lines()->whereNotNull('approved_on')->count() > 0) {
                $item->generateCustomerInvoiceJournal($approved_journal_entry, 'debit_amount', 'approved_on');
                $item->generateCustomerInvoiceJournal($approved_journal_entry, 'credit_amount', 'approved_on');

                $redirect = $approved_journal_entry->renderShowUrl().'#posted';
            }

            if ($item->customer_invoice_lines()->whereNotNull('rejected_on')->count() > 0) {
                $item->generateCustomerInvoiceJournal($rejected_journal_entry, 'debit_amount', 'rejected_on');
                $item->generateCustomerInvoiceJournal($rejected_journal_entry, 'credit_amount', 'rejected_on');
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
        $ci = CustomerInvoice::find($id);
    
        return view('customer-invoices.print',[
            'ci' => $ci,
            'customer' => $ci->customer,
            'ci_lines' => $ci->customer_invoice_lines,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerInvoiceAging()
    {
        return view('customer-invoices.aging', [
            'clients' => User::getClients()
        ]);
    }
}
