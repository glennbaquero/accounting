<?php

namespace App\Http\Controllers\CustomerPaymentJournals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\CustomerPaymentJournals\CustomerPaymentJournalStoreRequest;
use App\Http\Requests\CustomerPaymentJournals\CustomerPaymentVoucherStoreRequest;

use App\Models\Journals\CustomerPaymentJournal;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\JournalLines\CustomerPaymentJournalVoucher;
use App\Models\Ledgers\Ledger;
use App\Models\Users\User;
use App\Models\CashflowTransactions\CashflowTransaction;


use DB;
use Carbon\Carbon;

class CustomerPaymentJournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cost_centers = FinancialDimension::where('use_value_from', 'Cost centers')->first()->financial_dimension_values;
        $departments = FinancialDimension::where('use_value_from', 'Departments')->first()->financial_dimension_values;
        $expense_purposes = FinancialDimension::where('use_value_from', 'Expense purposes')->first()->financial_dimension_values;
        $clients = User::getClients();
        
        return view('customer-payment-journals.index', [
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
        return view('customer-payment-journals.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerPaymentJournalStoreRequest $request)
    {
        $count = CustomerPaymentJournal::withTrashed()->count();
        $number = str_pad($count, 4, '0', STR_PAD_LEFT);
        $request['customer_payment_journal_number'] = $number;
        $request['journal_status'] = 'Open';
        $request['protest_settlements'] = '---';
        $request['protest_settled_process'] = '---';
        $request['financial_dimensions'] = '---';
        $request['locked_by_system'] = '---';
        $request['private_for_user_group'] = '---';
        $request['created_by'] = auth()->user()->fullname;
        $request['updated_at'] = null;

        $item = CustomerPaymentJournal::store($request);

        $message = "You have successfully created {$item->customer_payment_journal_number}";
        // $redirect = route('customer-payment-journals.header-show', $item->id);
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerPaymentJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
        $cost_centers = FinancialDimension::where('use_value_from', 'Cost centers')->first()->financial_dimension_values;
        $departments = FinancialDimension::where('use_value_from', 'Departments')->first()->financial_dimension_values;
        $expense_purposes = FinancialDimension::where('use_value_from', 'Expense purposes')->first()->financial_dimension_values;
        $clients = User::getClients();

        return view('customer-payment-journals.show', [
            'item' => $item,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
            'clients' => $clients,
        ]);
    }    
    
    public function edit($id)
    {
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
        return view('customer-payment-journals.update', [
            'item' => $item,
        ]);
    }

    public function showUpdate($id)
    {
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
        return view('customer-payment-journals.update', [
            'item' => $item,
        ]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerPaymentJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerPaymentJournalStoreRequest $request, $id)
    {
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->customer_payment_journal_number}";
        $request['updated_by'] = auth()->user()->fullname;

        $item = CustomerPaymentJournal::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerPaymentJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->customer_payment_journal_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\CustomerPaymentJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->customer_payment_journal_number}",
        ]);
    }

    public function fetch(Request $request) 
    {

        $items = new CustomerPaymentJournal;

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

    public function createVouchers(CustomerPaymentVoucherStoreRequest $request, $id)
    {
        $request['payment_due_date']  = Carbon::parse($request['due_date'])->format('Y-m-d');
        $request = $request->except(['voucher_type', 'due_date']);
        $request['transaction_date']  = Carbon::parse($request['transaction_date'])->format('Y-m-d');
        $request['invoice_date']  = Carbon::parse($request['invoice_date'])->format('Y-m-d');
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
        $request['customer_payment_journal_number'] = $item->customer_payment_journal_number;
        $request['invoice_journal_batch_number'] = $item->invoice_journal_batch_number;
        $request['journal_name'] = $item->journal_name;
        $request['voucher_line_number'] = $item->customer_payment_journal_vouchers()->count() + 1;
        $request['voucher_date'] = now();
        // $request['customer_payment_number'] = '---';
        $request['offset_account_type'] = $request['offset_account_type'] ? $request['offset_account_type'] :'---';
        $request['offset_account'] = $request['offset_account'] ? $request['offset_account'] :'---';
        $request['main_account'] = $request['main_account'] ? $request['main_account'] :'---';
        $request['account_type'] = $request['account_type'] ? $request['account_type'] :'---';
        $request['payment_id'] = $request['invoice_number'];
        $request['created_by'] = auth()->user()->fullname;
        $voucher = CustomerPaymentJournalVoucher::create($request);


        $credit_amount = $item->customer_payment_journal_vouchers()->where('payment_id', $voucher->payment_id)->where('payment_due_date', $voucher->payment_due_date)->orWhere('invoice_number', $voucher->invoice_number)->orWhere('invoice_date', $voucher->invoice_date)->sum('credit_amount');

        $debit_amount = $item->customer_payment_journal_vouchers()->where('payment_id', $voucher->payment_id)->where('payment_due_date', $voucher->payment_due_date)->orWhere('invoice_number', $voucher->invoice_number)->orWhere('invoice_date', $voucher->invoice_date)->sum('debit_amount');

        $balance_journal = $credit_amount - $debit_amount;

        $voucher->update(['balance_journal' => $balance_journal]);


        return response()->json([
            'message' => "Successfully added a new voucher in {$item->customer_payment_journal_number}",
        ]);
    }

    public function updateVoucher(CustomerPaymentVoucherStoreRequest $request, $id) 
    {
        $request['updated_by'] = auth()->user()->fullname;
        $request['updated_at'] = now();

        $request['transaction_date'] = $request->filled('transaction_date') ? Carbon::parse($request->transaction_date) : null; 
        $request['voucher_date'] = $request->filled('voucher_date') ? Carbon::parse($request->voucher_date) : null; 
        $request['approved_date'] = $request->filled('approved_date') ? Carbon::parse($request->approved_date) : null; 
        $request['invoice_date'] = $request->filled('invoice_date') ? Carbon::parse($request->invoice_date) : null; 
        $request['payment_due_date'] = $request->filled('payment_due_date') ? Carbon::parse($request->payment_due_date) : null; 
        $request['maturity_date'] = $request->filled('maturity_date') ? Carbon::parse($request->maturity_date) : null; 
        $request['received_date'] = $request->filled('received_date') ? Carbon::parse($request->received_date) : null; 
        $request['log_date'] = $request->filled('log_date') ? Carbon::parse($request->log_date) : null; 
        $request['adjusted_on'] = $request->filled('adjusted_on') ? Carbon::parse($request->adjusted_on) : null; 
        $item = CustomerPaymentJournalVoucher::withTrashed()->findOrFail($id);
        $item->update($request->except(['selected', 'updateUrl', 'pending_customer_invoice', 'sales_tax_included_in_amount', 'alreadyInSelectedItem', 'customer_payment_journal_vouchers', 'created_by', 'created_date', 'updated_date', 'posted_by_name', 'offset_account_id', 'normal_balance', 'increase_rule', 'decrease_rule', 'balance', 'main_account_name', 'offset_account_name', 'client']));
        // $item->;

        return response()->json([
            'message' => "Successfully update a new voucher in {$item->voucher_number}",
        ]);
    }
    
    public function updateStatusVoucher(Request $request) 
    {
        foreach ($request->selectedIds as $id) {
            $item = CustomerPaymentJournalVoucher::withTrashed()->findOrFail($id);
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
            $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
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

    public function validateJournal(Request $request, $id) {
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);
        $total_credit = $item->customer_payment_journal_vouchers->sum('credit_amount');
        $total_debit = $item->customer_payment_journal_vouchers->sum('debit_amount');

        if(!$request->filled('withOffsetAccount')) {
            if($total_debit != $total_credit) {

                $item->customer_payment_journal_vouchers()->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Total not balance',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                
                throw ValidationException::withMessages([
                    'message' => ['Total not balance']
                ]);
            } else {
                $item->customer_payment_journal_vouchers()->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Success: Ready for posting',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
            }  
        } else {
            $offset_account = $item->offset_account;
            $lines_not_same_offset = $item->customer_payment_journal_vouchers()->where('offset_account', '!=', $offset_account)->get();

            if($lines_not_same_offset) {
                throw ValidationException::withMessages([
                    'message' => ['Validation error voucher offset account is not the same. Please check all vouchers.']
                ]);
            }
           
        }

        return response()->json([
            'message' => 'Total was validated'
        ]);
    }

    public function validateVoucher(Request $request) {
        $vouchers = CustomerPaymentJournalVoucher::withTrashed()->whereIn('id', $request->ids)->orderby('voucher_date');

        if($vouchers->count() % 2) {
            $vouchers->update([
                'logged_by' => auth()->user()->fullname,
                'log_message' => 'Error: Voucher count is not balance.',
                'log_in_checkbox' => true,
                'log_date' => now(),
            ]);

            throw ValidationException::withMessages([
                'message' => ['Validation error: Voucher count is not balance.']
            ]);
        }

        $list_vouchers = $vouchers->get();
        $for_mainaccount_list_vouchers = $vouchers->get();

        foreach ($list_vouchers as $key => $voucher) {

            $main_account_to_search = $voucher->main_account;

            $new_list = $for_mainaccount_list_vouchers->forget($key);

            if($new_list->contains('main_account', $main_account_to_search)) {
                $voucher->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Same main account has detected.',
                    'log_in_checkbox' => true,
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
                 +   'log_date' => now(),
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
                     +   'log_date' => now(),
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
                     +   'log_date' => now(),
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
                     +   'log_date' => now(),
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
                     +   'log_date' => now(),
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
                 +   'log_date' => now(),
                ]);

                throw ValidationException::withMessages([
                    'message' => ['Validation error: Description is empty in '.$voucher->voucher_number.'.']
                ]);
            }
            
            if($key > 0) {
                $voucher_for_validation = $list_vouchers[$key-1];

                if($voucher_for_validation->invoice_number != $voucher->invoice_number) {
                    $voucher_for_validation->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice number is not match with '.$voucher->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice number is not match with '.$voucher_for_validation->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Invoice number is not match.']
                    ]);
                }

                if($voucher_for_validation->invoice_date != $voucher->invoice_date) {
                    $voucher_for_validation->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice date is not match with '.$voucher->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice date is not match with '.$voucher_for_validation->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Invoice date is not match.']
                    ]);
                }

                if($voucher_for_validation->payment_id != $voucher->payment_id) {
                    $voucher_for_validation->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Payment ID is not match with '.$voucher->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Payment ID is not match with '.$voucher_for_validation->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Payment ID is not match.']
                    ]);
                }

                if($voucher_for_validation->entry_pair_number != $voucher->entry_pair_number) {
                    $voucher_for_validation->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Entry pair is not match with '.$voucher->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Entry pair is not match with '.$voucher_for_validation->voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Entry pair is not match.']
                    ]);
                }
            }
            $for_mainaccount_list_vouchers = $vouchers->get();
        }

        $total_credit = $vouchers->sum('credit_amount');
        $total_debit = $vouchers->sum('debit_amount');

        if($total_debit != $total_credit) {
            $voucher->update([
                'logged_by' => auth()->user()->fullname,
                'log_message' => 'Error: Vouchers not balance.',
                'log_in_checkbox' => true,
                'log_date' => now(),
            ]);

            throw ValidationException::withMessages([
                'message' => ['Validation error: Vouchers not balance.']
            ]);
        }


        $vouchers->update([
            'logged_by' => auth()->user()->fullname,
            'log_message' => 'Success: Voucher successfully validated',
            'log_in_checkbox' => true,
            'log_date' => now(),
        ]);

        return response()->json([
            'message' => 'Total was validated'
        ]);
    }
    
    public function post(Request $request, $id)
    {
        
        $item = CustomerPaymentJournal::withTrashed()->findOrFail($id);

        // get params from logged in user
        $login_user_company_id = auth()->user()->company_id;

        DB::beginTransaction();
            $vouchers = $item->customer_payment_journal_vouchers()->whereNotNull('approved_date')->get();

            foreach ($vouchers as $key => $voucher) {

                $client_id = $voucher->client_id;

                // getting available ledger 
                $ledger = Ledger::where('client_id', $client_id)->where('company_id', $login_user_company_id)->whereDate('active_from', '<=', $voucher->invoice_date)->whereDate('active_to', '>=', $voucher->invoice_date)->first();
                

                if(!$ledger) {
                    throw ValidationException::withMessages([
                        'message' => ['No available ledger found for the of voucher line: '. $voucher->voucher_number]
                    ]);
                }

                // getting relationship
                $ledger_calendar = $ledger ? $ledger->ledger_calendar : '';
                $fiscal_calendar = $ledger_calendar ? $ledger_calendar->fiscal_calendar : '';

                // checking if fiscal calendar is open
                if($fiscal_calendar && $fiscal_calendar->fiscal_year_status != 'Open') {
                    throw ValidationException::withMessages([
                        'message' => ['Fiscal calendar year status is not open. Please contact your accounting administrator.']
                    ]);
                }

                // checking if the invoice date is within range of fiscal period lists
                $fiscal_periods = 0;
                if($fiscal_periods) {
                    $fiscal_periods = $fiscal_calendar->fiscal_periods()->whereDate('fiscal_period_start_date', '<=', $voucher->invoice_date)->whereDate('fiscal_period_end_date', '>=', $voucher->invoice_date)->count();
                }

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
                        'ledger_line_number' => ($key+1),
                        'company_id' => $login_user_company_id,
                        'client_id' => $voucher->client_id,
                        'ledger_calendar' => $ledger_calendar->id,
                        'journal_header_id' => $item->id,
                        'journal_voucher_id' => $voucher->id,
                        'journal_name' => $item->journal_name,
                        'journal_type' => $item->journal_type,
                        'main_account_code_number' => $main_account->main_account_code_number,
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

                $this->insertCashflow($voucher, $request);
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

    public function insertCashflow($voucher, $request) {
        $item = CashflowTransaction::firstOrCreate([
            'customer_payment_journal_voucher' => $voucher->voucher_number,
        ], [
            'customer_payment_journal_number' => $voucher->customer_payment_journal_number,
            'type' => 'Customer',
            'journal_name' => $voucher->journal_name,
            'voucher_date' => $voucher->voucher_date,
            'description' => $voucher->description,
            'debit_amount' => $voucher->debit_amount,
            'credit_amount' => $voucher->credit_amount,
            'posted_checkbox' => $voucher->posted_checkbox ? true : false,
            'posted_on' => $voucher->posted_on,
            'posted_by' => $voucher->posted_by,
            'customer_account' => $voucher->customer_account,
            'customer_name' => $voucher->customer_name,
            'customer_invoice_number' => $voucher->invoice_number,
            'invoice_date' => $voucher->invoice_date,
            'payment_due_date' => $voucher->payment_due_date,
            'settlement_type' => $voucher->settlement_type,

            'method_of_payment_customer' => $voucher->method_of_payment,
            'customer_payment_id' => $voucher->payment_id,

            'payment_status' => $voucher->payment_status,
            'deposit_slip_number' => $voucher->deposit_slip_number,
            'payment_specification' => $voucher->payment_specification,
            'payment_reference' => $voucher->payment_reference,
            'bank_transaction_type' => $voucher->bank_transaction_type,

            'bank_account' => $voucher->bank_account,
            'postdated_check_status' => $voucher->postdated_check_status,
            'check_number' => $voucher->check_number,
            'check_number_issued' => $voucher->check_number_issued,
            'maturity_date' => $voucher->maturity_date, 
            'received_date' => $voucher->received_date, 
            'cashier' => $voucher->cashier, 
            'salesperson' => $voucher->salesperson, 
            'issuing_bank_branch' => $voucher->issuing_bank_branch, 
            'issuing_bank_name' => $voucher->issuing_bank_name, 
            'stop_payment' => $voucher->stop_payment, 
            'replacement_check' => $voucher->replacement_check, 

            'original_check' => $voucher->original_check, 
            'check_amount' => $voucher->check_amount, 
            'recipient_name' => $voucher->recipient_name,

            'main_account' => $voucher->main_account,
            'account_type' => $voucher->account_type,
            'offset_company_accounts' => $voucher->offset_company_accounts,
            'offset_account_type' => $voucher->offset_account_type,
            'offset_account' => $voucher->offset_account,
            'offset_transaction_text' => $voucher->offset_transaction_text,
            'sales_tax_direction' => $voucher->sales_tax_direction,
            'sales_tax_group' => $voucher->sales_tax_group,
            'item_sales_tax_group' => $voucher->item_sales_tax_group,
            'withholding_tax_group' => $voucher->withholding_tax_group,
            'fee_account' => $voucher->fee_account,
            'fee_id' => $voucher->fee_id,
            'fee_amount' => $voucher->fee_amount,

            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,

        ]);

        if(!$item->cashflow_transaction_id) {
            $cashflow_transaction_id = 'cashflow-transaction-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $item->update([
                'cashflow_transaction_id' => $cashflow_transaction_id,
            ]);
        }
        
    }
}
