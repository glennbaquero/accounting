<?php

namespace App\Http\Controllers\SalesReturnJournals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\CustomerInvoiceJournals\CustomerInvoiceJournalStoreRequest;
use App\Http\Requests\CustomerInvoiceJournals\CustomerApprovalJournaVoucherStoreRequest;
use App\Models\Journals\SalesOrderReturnJournal;
use App\Models\JournalLines\SalesReturnJournalVoucher;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\Ledgers\Ledger;
use App\Models\GeneralLedgers\GeneralLedgerLine;
use App\Models\Users\User;

use DB;
use Carbon\Carbon;

class SalesReturnJournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cost_centers = FinancialDimension::where('use_value_from', 'Cost centers')->first()?->financial_dimension_values;
        $departments = FinancialDimension::where('use_value_from', 'Departments')->first()?->financial_dimension_values;
        $expense_purposes = FinancialDimension::where('use_value_from', 'Expense purposes')->first()?->financial_dimension_values;
        $clients = User::getClients();
        
        return view('sales-return-journals.index', [
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('sales-return-journals.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerInvoiceJournalStoreRequest $request)
    {
        $count = SalesOrderReturnJournal::withTrashed()->count();
        $number = str_pad($count, 4, '0', STR_PAD_LEFT);
        $request['journal_number'] = $number;
        $request['journal_status'] = 'Open';
        $request['protest_settlements'] = '---';
        $request['protest_settled_process'] = '---';
        $request['financial_dimensions'] = '---';
        $request['locked_by_system'] = '---';
        $request['private_for_user_group'] = '---';
        $request['created_by'] = auth()->user()->fullname;
        $request['updated_at'] = null;

        $item = SalesOrderReturnJournal::store($request);

        $message = "You have successfully created {$item->journal_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SalesOrderReturnJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
        return view('sales-return-journals.show', [
            'item' => $item,
        ]);
    }

    public function edit($id)
    {
        $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
        return view('sales-return-journals.update', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SalesOrderReturnJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerInvoiceJournalStoreRequest $request, $id)
    {
        $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->journal_number}";
        $request['updated_by'] = auth()->user()->fullname;

        $item = SalesOrderReturnJournal::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SalesOrderReturnJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->journal_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\SalesOrderReturnJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->journal_number}",
        ]);
    }

    public function fetch(Request $request) 
    {

        $items = new SalesOrderReturnJournal;

        if($request->filled('show')) {
            if($request->show != 'All') {
                $items = $items->where('journal_status', $request->show);
            }

            if($request->show == 'Open') {
                $items = $items->where('journal_status', $request->show)->whereNull('posted_by');
            }

            if($request->show == 'Posted') {
                $items = $items->where('journal_status', $request->show)->whereNotNull('posted_by');
            }
        }

        $items = $items->withTrashed()->orderby('id', 'desc')->get();

        return response()->json([
            'items' => $items
        ]);
    }

    public function createVouchers(CustomerApprovalJournaVoucherStoreRequest $request, $id)
    {
        $request = $request->except(['voucher_type']);
        $request['transaction_date']  = Carbon::parse($request['transaction_date'])->format('Y-m-d');
        $request['invoice_date']  = Carbon::parse($request['invoice_date'])->format('Y-m-d');
        $request['due_date']  = Carbon::parse($request['due_date'])->format('Y-m-d');
        $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
        $request['journal_number'] = $item->journal_number;
        $request['journal_batch_number'] = $item->journal_batch_number;
        $request['journal_name'] = $item->journal_name;
        $request['voucher_line_number'] = $item->sales_return_journal_vouchers()->count() + 1;
        $request['voucher_date'] = now();
        $request['customer_invoice_number'] = '---';
        $request['offset_account_type'] = $request['offset_account_type'] ? $request['offset_account_type'] :'---';
        $request['offset_account'] = $request['offset_account'] ? $request['offset_account'] :'---';
        $request['main_account'] = $request['main_account'] ? $request['main_account'] :'---';
        $request['account_type'] = $request['account_type'] ? $request['account_type'] :'---';
        $request['payment_id'] = $request['invoice_number'];
        $request['created_by'] = auth()->user()->fullname;
        $cst_voucher = SalesReturnJournalVoucher::create($request);

        $credit_amount = $item->sales_return_journal_vouchers()->where('payment_id', $cst_voucher->payment_id)->where('due_date', $cst_voucher->due_date)->orWhere('invoice_number', $cst_voucher->invoice_number)->orWhere('invoice_date', $cst_voucher->invoice_date)->sum('credit_amount');
        $debit_amount = $item->sales_return_journal_vouchers()->where('payment_id', $cst_voucher->payment_id)->where('due_date', $cst_voucher->due_date)->orWhere('invoice_number', $cst_voucher->invoice_number)->orWhere('invoice_date', $cst_voucher->invoice_date)->sum('debit_amount');

        $balance_journal = $credit_amount - $debit_amount;

        $cst_voucher->update(['balance_journal' => $balance_journal]);

        // $cst_voucher->create($request->except(['voucher_number', 'journal_number']));

        return response()->json([
            'message' => "Successfully added a new voucher in {$item->journal_number}",
        ]);
    }

    public function updateVoucher(CustomerApprovalJournaVoucherStoreRequest $request, $id) 
    {
        $request['updated_by'] = auth()->user()->fullname;
        $request['updated_at'] = now();
        $request['transaction_date'] = Carbon::parse($request->transaction_date);
        $request['voucher_date'] = $request->filled('voucher_date') ? Carbon::parse($request->voucher_date) : null;
        $request['approved_date'] = $request->filled('approved_date') ? Carbon::parse($request->approved_date) : null;
        $request['invoice_date'] = $request->filled('invoice_date') ? Carbon::parse($request->invoice_date) : null;
        $request['due_date'] = $request->filled('due_date') ? Carbon::parse($request->due_date) : null;
        $request['invoice_payment_release_date'] = $request->filled('invoice_payment_release_date') ? Carbon::parse($request->invoice_payment_release_date) : null;
        $request['cash_discount_date'] = $request->filled('cash_discount_date') ? Carbon::parse($request->cash_discount_date) : null;
        $request['log_date'] = $request->filled('log_date') ? Carbon::parse($request->log_date) : null;
        $item = SalesReturnJournalVoucher::withTrashed()->findOrFail($id);
        $item->update($request->except(['client' ,'selected', 'updateUrl', 'pending_customer_invoice', 'sales_tax_included_in_amount', 'alreadyInSelectedItem', 'invoice_approval_journal_vouchers', 'created_by', 'created_date', 'updated_date', 'voucher_number', 'journal_number', 'posted_by_name', 'offset_account_id', 'normal_balance', 'increase_rule', 'decrease_rule', 'balance', 'main_account_name', 'offset_account_name']));

        return response()->json([
            'message' => "Successfully update a new voucher in {$item->journal_number}",
        ]);
    }
    
    public function updateStatusVoucher(Request $request) 
    {
        foreach ($request->selectedIds as $id) {
            $item = SalesReturnJournalVoucher::withTrashed()->findOrFail($id);
            if($request->status == 'Approved') {
                $item->update([
                    'approved_date' => now(),
                    'approved_by_journal' => auth()->user()->fullname,
                    'rejected_by_journal' => null,
                ]);
            } else {
                $item->update([
                    'rejected_by_journal' => auth()->user()->fullname,
                    'approved_date' => null,
                    'approved_by_journal' => null
                ]);                
            } 
        }
        
        return response()->json([
            'message' => "Successfully {$request->status} selected voucher",
        ]);
    }

    public function updateStatusHeader(Request $request) 
    {
        foreach ($request->selectedIds as $id) {
            $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
            if($request->status == 'Approved') {
                $item->update([
                    'approved_date' => now(),
                    'approved_by_journal' => auth()->user()->fullname,
                    'rejected_by_journal' => null,
                ]);
            } else {
                $item->update([
                    'rejected_by_journal' => auth()->user()->fullname,
                    'approved_date' => null,
                    'approved_by_journal' => null
                ]);                
            } 
        }
        
        return response()->json([
            'message' => "Successfully {$request->status} selected voucher",
        ]);
    }

    public function validateJournal(Request $request, $id) 
    {
        $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);
        $total_credit = $item->sales_return_journal_vouchers->sum('credit_amount');
        $total_debit = $item->sales_return_journal_vouchers->sum('debit_amount');

        if(!$request->filled('withOffsetAccount')) {
            if($total_debit != $total_credit) {
                
                $item->sales_return_journal_vouchers()->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Debit and credit not balance.',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);

                throw ValidationException::withMessages([
                    'message' => ['Debit and credit not balance.']
                ]);
            } else {
                $item->sales_return_journal_vouchers()->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Success: Debit and credit is balance',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
            }   
        } else {
            $offset_account = $item->offset_account;
            $lines_not_same_offset = $item->sales_return_journal_vouchers()->where('offset_account', '!=', $offset_account)->get();

            if($lines_not_same_offset) {
                throw ValidationException::withMessages([
                    'message' => ['Validation error voucher offset account is not the same. Please check all vouchers.']
                ]);
            }
           
        }

        
        return response()->json([
            'message' => 'Debit and credit is balance.'
        ]);
    }

    public function validateVoucher(Request $request, $id) {

        $vouchers = SalesReturnJournalVoucher::whereIn('id', $request->ids)->with('header')->orderby('voucher_date');
        $query = SalesOrderReturnJournal::where('id', $id)->first();

        $list_vouchers = $vouchers->get();
        $for_mainaccount_list_vouchers = $vouchers->get();

        if(!$list_vouchers->count()) {
            throw ValidationException::withMessages([
                'message' => ['No selected vouchers']
            ]);
        }

        foreach ($list_vouchers as $key => $voucher) {
            
            // Check if voucher has pair
            $pair = $query->sales_return_journal_vouchers()->where('entry_pair_number', $voucher->entry_pair_number);
          
            if($pair->count() <= 1) {
                $voucher->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: This voucher has no pair.',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                $voucher->header->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_date' => now(),
                ]);
                throw ValidationException::withMessages([
                    'message' => ['Validation error: This voucher has no pair '.$voucher->voucher_number.'.']
                ]);
            }
            
            if(!$voucher->description || $voucher->description == '---') {
                $voucher->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: This voucher has no description.',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                $voucher->header->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_date' => now(),
                ]);
                throw ValidationException::withMessages([
                    'message' => ['Validation error: Voucher '.$voucher->voucher_number.' has no description.']
                ]);
            }

            $main_account_to_search = $voucher->main_account;

            $new_list = $for_mainaccount_list_vouchers->forget($key);

            if($new_list->contains('main_account', $main_account_to_search)) {
                $voucher->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Same main account has detected.',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                $voucher->header->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_date' => now(),
                ]);
                throw ValidationException::withMessages([
                    'message' => ['Validation error: Same main account has detected.']
                ]);
            }
            
            if($voucher->debit_amount <= 0 && $voucher->credit_amount <= 0) {
                $voucher->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Debit amount and credit amount has no amount.',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                $voucher->header->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_date' => now(),
                ]);
                throw ValidationException::withMessages([
                    'message' => ['Validation error: Debit amount and credit amount has no amount in '.$voucher->voucher_number.'.']
                ]);
            }

            if($voucher->main_account && !$voucher->offset_account) {
                if($voucher->debit_amount <= 0) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Debit amount is zero.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);
                    $voucher->header->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_date' => now(),
                    ]);
                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Debit amount is zero in '.$voucher->voucher_number.'.']
                    ]);
                } 

                if($voucher->credit_amount > 0) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Credit amount must be zero.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);
                    $voucher->header->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_date' => now(),
                    ]);
                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Credit amount must be zero in '.$voucher->voucher_number.'.']
                    ]);
                }
            }

            if($voucher->offset_account && !$voucher->main_account) {
                if($voucher->credit_amount <= 0) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Credit amount is zero.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);
                    $voucher->header->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_date' => now(),
                    ]);
                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Credit amount is zero in '.$voucher->voucher_number.'.']
                    ]);
                } 

                if($voucher->debit_amount > 0) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Debit amount must be zero.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);
                    $voucher->header->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_date' => now(),
                    ]);
                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Debit amount must be zero in '.$voucher->voucher_number.'.']
                    ]);
                }
            }

            if(!$voucher->description) {
                $voucher->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Description is empty.',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                $voucher->header->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_date' => now(),
                ]);
                throw ValidationException::withMessages([
                    'message' => ['Validation error: Description is empty in '.$voucher->voucher_number.'.']
                ]);
            }

            if($key > 0) {

                $pairs = $query->sales_return_journal_vouchers();
                $invoice_number = $pairs->where('entry_pair_number', $voucher->entry_pair_number)->get()->pluck('voucher_number');
             
                if (count(array_unique($invoice_number->toArray())) === 1 && end($invoice_number) === $voucher->voucher_number) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice number is not match with '.$voucher->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);
                    $voucher->header->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_date' => now(),
                    ]);
                    throw ValidationException::withMessages([
                        'message' => ['Validation error: invoice number is not match.']
                    ]);
                 }
                
                $invoice_date = $pairs->where('entry_pair_number', $voucher->entry_pair_number)->get()->pluck('invoice_date');
                if (count(array_unique($invoice_date->toArray())) === 1 && end($invoice_date) === $voucher->invoice_date) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice date is not match with '.$voucher->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);
                    $voucher->header->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_date' => now(),
                    ]);
                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Invoice Date is not match.']
                    ]);
                }

                $payment_id = $pairs->where('entry_pair_number', $voucher->entry_pair_number)->get()->pluck('payment_id');
                if (count(array_unique($payment_id->toArray())) === 1 && end($payment_id) === $voucher->payment_id) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Payment ID is not match with '.$voucher->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);
                    $voucher->header->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_date' => now(),
                    ]);
                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Payment ID is not match.']
                    ]);
                }

                if($voucher->main_account && $voucher->main_account != '---') {
                    $ma = $voucher->main_account_details;
                    if($ma->debit_credit_decrease_rule == 'Credit' && ($voucher->credit_amount >= 0 || $voucher->credit_amount == null)){
                        $voucher->update([
                            'logged_by' => auth()->user()->fullname,
                            'log_message' => 'Error: increase/decrease rules is not followed',
                            'log_in_checkbox' => true,
                            'log_date' => now(),
                        ]);
                        $voucher->header->update([
                            'logged_by' => auth()->user()->fullname,
                            'log_date' => now(),
                        ]);
                    }
                }
                
                if($voucher->offset_account && $voucher->offset_account != '---') {
                    $ma = $voucher->offset_main_account_details;
                    if($ma->debit_credit_decrease_rule == 'Debit' && ($voucher->debit_amount >= 0 || $voucher->debit_amount == null)){
                        $voucher->update([
                            'logged_by' => auth()->user()->fullname,
                            'log_message' => 'Error: increase/decrease rules is not followed',
                            'log_in_checkbox' => true,
                            'log_date' => now(),
                        ]);
                        $voucher->header->update([
                            'logged_by' => auth()->user()->fullname,
                            'log_date' => now(),
                        ]);
                    }
                }
            }


            if($pair->sum('debit_amount') != $pair->sum('credit_amount')) {
                $voucher->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Total not balance.',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                $voucher->header->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_date' => now(),
                ]);
                throw ValidationException::withMessages([
                    'message' => ['Validation error: Total not balance.']
                ]);
            }
        }



        $vouchers->update([
            'logged_by' => auth()->user()->fullname,
            'log_message' => 'Success: Voucher successfully validated.',
            'log_in_checkbox' => true,
            'log_date' => now(),
        ]);

        return response()->json([
            'message' => 'Voucher is validated!'
        ]);

    }

    public function post(Request $request, $id)
    {
        
        $item = SalesOrderReturnJournal::withTrashed()->findOrFail($id);

        // get params from logged in user
        $login_user_company_id = auth()->user()->company_id;

        DB::beginTransaction();

            foreach ($item->sales_return_journal_vouchers()->whereNotNull('approved_date') as $key => $voucher) {

                $client_id = $voucher->client_id;

                // getting available ledger 
                $ledger = Ledger::where('client_id', $client_id)->where('company_id', $login_user_company_id)->whereDate('active_from', '<=', $voucher->invoice_date)->whereDate('active_to', '>=', $voucher->invoice_date)->first();
                

                if(!$ledger) {
                    throw ValidationException::withMessages([
                        'message' => ['No available ledger found for the of voucher line: '. $voucher->voucher_number]
                    ]);
                }

                // getting relationship
                $ledger_calendar = $ledger->ledger_calendar;
                $fiscal_calendar = $ledger_calendar->fiscal_calendar;

                // checking if fiscal calendar is open
                if($fiscal_calendar->fiscal_year_status != 'Open') {
                    throw ValidationException::withMessages([
                        'message' => ['Fiscal calendar year status is not open. Please contact your accounting administrator.']
                    ]);
                }

                // checking if the invoice date is within range of fiscal period lists
                $fiscal_periods = $fiscal_calendar->fiscal_periods()->whereDate('fiscal_period_start_date', '<=', $voucher->invoice_date)->whereDate('fiscal_period_end_date', '>=', $voucher->invoice_date)->count();

                if($fiscal_periods) {

                    // getting the chart of accounts, main account from coa, and link main account from main account
                    $chart_of_account = $ledger->chart_of_account;
                    $main_account = $chart_of_account->main_accounts()->where('id', $voucher->main_account)->first();

                    // getting the general ledger from ledger and checking if invoice date is within range of general ledger
                    $general_ledger = $ledger->general_ledgers()->whereDate('period_from', '<=', $voucher->invoice_date)->whereDate('period_to', '>=', $voucher->invoice_date)->first();

                    if(!$general_ledger) {
                        throw ValidationException::withMessages([
                            'message' => ['No general ledger found.']
                        ]);
                    }

                    // inserting the voucher to general ledger lines
                    $count = GeneralLedgerLine::count();
                    $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                    $code = 'GNRLLDGRLN-'.now()->format('m-d-y').'-'.$number;
                    $general_ledger->general_ledger_lines()->create([
                        'journal_line_id' => $voucher->voucher_line_number,
                        'ledger_journal_code' => $code,
                        'ledger_journal_line_id' => $voucher->id,
                        'ledger_line_number' => $voucher->entry_line_number,
                        'company_id' => $login_user_company_id,
                        'client_id' => $voucher->client_id,
                        'ledger_calendar' => $ledger_calendar->id,
                        'journal_header_id' => $item->id,
                        'journal_voucher_id' => $voucher->id,
                        'journal_name' => $item->journal_name,
                        'journal_type' => $item->journal_type,
                        'main_account_code_number' => $main_account->main_account_code_number ? $main_account->main_account_code_number : '---',
                        'main_account' => $main_account->id,
                        'main_account_type' => $main_account->main_account_type,
                        'main_account_category' => $main_account->main_account_category_id,
                        'main_account_normal_balance' => $main_account->balance_control,
                        'ledger_transaction_date' => now(),
                        'cost_center' => $item->cost_center,
                        'department' => $item->department,
                        'expense_purpose' => $item->expense_purpose,
                        'matched_voucher_to_gl' => 'All Matched',
                        'debit_amount' => $voucher->debit_amount,
                        'credit_amount' => $voucher->credit_amount,
                        'balance_amount' => $voucher->balance_journal,
                        'reversed_checkbox' => $item->reversing_entry_checkbox,
                        'reverse_date' => $item->reversing_date,
                        'reverse_by' => $item->created_by,
                        'posted_voucher' => 'Posted',
                        'created_by' => auth()->user()->fullname
                    ]); 

                    $voucher->update([
                        'approved_date' => now(),
                        'approved_by_journal' => auth()->user()->fullname,
                        'posted_on' => now(),
                        'posted_by' => auth()->user()->id
                    ]);
                }

            }

            $item->update([
                'approved_date' => now(),
                'approved_by_journal' => auth()->user()->fullname,
                'posted_on' => now(),
                'posted_by' => auth()->user()->fullname,
                'journal_status' => 'Posted'
            ]);

        DB::commit();


        return response()->json([
            'message' => 'Posting Success'
        ]);
    }
}
