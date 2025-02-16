<?php

namespace App\Http\Controllers\GeneralJournals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\GeneralJournals\GeneralJournalStoreRequest;
use App\Http\Requests\GeneralJournals\GeneralJournaVoucherlStoreRequest;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\GeneralLedgers\AccrualPosting;
use App\Models\GeneralLedgers\GeneralLedgerLine;
use App\Models\JournalLines\GeneralJournalVoucher;
use App\Models\Journals\GeneralJournal;
use App\Models\Ledgers\Ledger;
use App\Models\Users\User;

use DB;
use Carbon\Carbon;

class GeneralJournalController extends Controller
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

        return view('general-journal.index', [
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

        return view('general-journal.create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GeneralJournalStoreRequest $request)
    {
        $count = GeneralJournal::withTrashed()->count() + 1;
        $number = str_pad($count, 4, '0', STR_PAD_LEFT);
        $request['general_journal_number'] = $number;
        $request['created_by'] = auth()->user()->fullname;
        $request['updated_at'] = null;
        $request['journal_status'] = 'Open';
        $request['protest_settlements'] = '---';
        $request['protest_settled_process'] = '---';
        $request['locked_by_system'] = '---';
        $request['private_for_user_group'] = '---';
        $request['financial_dimensions'] = '---';
        $request['financial_dimensions'] = '---';
        
        $item = GeneralJournal::store($request);

        $message = "You have successfully created {$item->general_journal_number}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GeneralJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = GeneralJournal::withTrashed()->findOrFail($id);

        return view('general-journal.show', [
            'item' => $item,
        ]);
    }
    
    public function edit($id)
    {
        $item = GeneralJournal::withTrashed()->findOrFail($id);
        return view('general-journal.update', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GeneralJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(GeneralJournalStoreRequest $request, $id)
    {
        $item = GeneralJournal::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->general_journal_number}";
        $request['updated_by'] = auth()->user()->fullname;

        $item = GeneralJournal::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GeneralJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = GeneralJournal::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->general_journal_number}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\GeneralJournal  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = GeneralJournal::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->general_journal_number}",
        ]);
    }


    public function fetch(Request $request) 
    {
        $items = new GeneralJournal;

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
            'items' => $this->formatData($items),
        ]);

    }

    public function formatData($items)
    {
        $result = [];

        $result = collect($items)->map(function ($item) {
            $item->showUrl = $item->renderShowUrl();
            $item->archiveUrl = $item->renderArchiveUrl();
            $item->restoreUrl = $item->renderRestoreUrl();
            return $item;
        });
        
        return $result;
    }

    public function createVouchers(GeneralJournaVoucherlStoreRequest $request, $id)
    {
        $item = GeneralJournal::withTrashed()->findOrFail($id);
        $request['general_journal_number'] = $item->general_journal_number;
        $request['cost_center'] = $item->cost_center;
        $request['department'] = $item->department;
        $request['expense_purpose'] = $item->expense_purpose;
        $request['invoice_journal_batch_number'] = $item->invoice_journal_batch_number;
        $request['journal_name'] = $item->journal_name;
        $request['voucher_line_number'] = $item->general_journal_vouchers()->count() + 1;
        $request['voucher_date'] = now();
        $request['vendor_invoice_number'] = '---';
        $request['payment_id'] = '---';
        $request['created_by'] = auth()->user()->fullname;
        $request['due_date'] = Carbon::parse($request->due_date);
        $request['invoice_date'] = Carbon::parse($request->invoice_date);
        $request['transaction_date'] = Carbon::parse($request->transaction_date);
        $request['release_date_comment'] = Carbon::parse($request->release_date_comment);
        $request['cash_discount_date'] = Carbon::parse($request->cash_discount_date);
        $request['offset_account_type'] = $request['offset_account_type'] ? $request['offset_account_type'] :'---';
        $request['offset_account'] = $request['offset_account'] ? $request['offset_account'] :'---';
        $request['main_account'] = $request['main_account'] ? $request['main_account'] :'---';
        $request['account_type'] = $request['account_type'] ? $request['account_type'] :'---';
        $request['payment_id'] = $request['invoice_number'];
        $request['payment_specification'] = $request['payment_specification'] ? $request['payment_specification'] :'---';
        $request['payment_deposit_slip'] = $request['payment_deposit_slip'] ? $request['payment_deposit_slip'] :'---';
        
        $request['company_id'] = auth()->user()->company_id;
        
        unset($request['voucher_type']);
        $voucher = GeneralJournalVoucher::create($request->all());

        $credit_amount = $item->general_journal_vouchers()->where('payment_id', $voucher->payment_id)->where('due_date', $voucher->due_date)->orWhere('invoice_number', $voucher->invoice_number)->orWhere('invoice_date', $voucher->invoice_date)->sum('credit_amount');
        
        $debit_amount = $item->general_journal_vouchers()->where('payment_id', $voucher->payment_id)->where('due_date', $voucher->due_date)->orWhere('invoice_number', $voucher->invoice_number)->orWhere('invoice_date', $voucher->invoice_date)->sum('debit_amount');

        $balance_journal = $credit_amount - $debit_amount;

        $voucher->update(['balance_journal' => $balance_journal]);


        return response()->json([
            'message' => "Successfully added a new voucher in {$item->general_journal_number}",
        ]);
    }

    public function updateVoucher(GeneralJournaVoucherlStoreRequest $request, $id) 
    {
        $request['updated_by'] = auth()->user()->fullname;
        $request['updated_at'] = now();
        $request['transaction_date'] = $request->filled('transaction_date') ? Carbon::parse($request->transaction_date) : null;
        $request['voucher_date'] = $request->filled('voucher_date') ? Carbon::parse($request->voucher_date) : null;
        $request['posted_on'] = $request->filled('posted_on') ? Carbon::parse($request->posted_on) : null;
        $request['approved_date'] = $request->filled('approved_date') ? Carbon::parse($request->approved_date) : null;
        $request['invoice_date'] = $request->filled('invoice_date') ? Carbon::parse($request->invoice_date) : null;
        $request['due_date'] = $request->filled('due_date') ? Carbon::parse($request->due_date) : null;
        $request['invoice_payment_release_date'] = $request->filled('invoice_payment_release_date') ? Carbon::parse($request->invoice_payment_release_date) : null;
        $request['cash_discount_date'] = $request->filled('cash_discount_date') ? Carbon::parse($request->cash_discount_date) : null;
        $request['log_date'] = $request->filled('log_date') ? Carbon::parse($request->log_date) : null;
        $item = GeneralJournalVoucher::withTrashed()->findOrFail($id);
        $item->update($request->except(['accrual_posting', 'selected', 'updateUrl', 'pending_customer_invoice', 'sales_tax_included_in_amount', 'alreadyInSelectedItem', 'general_journal_vouchers', 'created_by', 'created_date', 'updated_date', 'posted_by_name', 'offset_account_id', 'normal_balance', 'increase_rule', 'decrease_rule', 'balance', 'main_account_name', 'offset_account_name', 'client', 'voucher_number']));

        return response()->json([
            'message' => "Successfully update a voucher in {$item->general_journal_number}",
        ]);
    }
    
    
    public function updateStatusVoucher(Request $request) 
    {
        foreach ($request->selectedIds as $id) {
            $item = GeneralJournalVoucher::withTrashed()->findOrFail($id);
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
            $item = GeneralJournal::withTrashed()->findOrFail($id);
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
        $item = GeneralJournal::withTrashed()->findOrFail($id);
        $total_credit = $item->general_journal_vouchers->sum('credit_amount');
        $total_debit = $item->general_journal_vouchers->sum('debit_amount');

        if(!$request->filled('withOffsetAccount')) {
            if($total_debit != $total_credit) {

                $item->general_journal_vouchers()->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Total not balance',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                
                throw ValidationException::withMessages([
                    'message' => ['Total not balance']
                ]);
            } else {
                $item->general_journal_vouchers()->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Success: Ready for posting',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
            }
        } else {
            $offset_account = $item->offset_account;
            $lines_not_same_offset = $item->general_journal_vouchers()->where('offset_account', '!=', $offset_account)->get();

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
        $vouchers = GeneralJournalVoucher::withTrashed()->whereIn('id', $request->ids)->orderby('voucher_date');

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
                    'log_date' => now(),
                ]);

                throw ValidationException::withMessages([
                    'message' => ['Validation error: Debit amount and credit amount has no amount in '.$voucher->general_journal_voucher_number.'.']
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

                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Debit amount is zero in '.$voucher->general_journal_voucher_number.'.']
                    ]);
                } 

                if($voucher->credit_amount > 0) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Credit amount must be zero.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Credit amount must be zero in '.$voucher->general_journal_voucher_number.'.']
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

                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Credit amount is zero in '.$voucher->general_journal_voucher_number.'.']
                    ]);
                } 

                if($voucher->debit_amount > 0) {
                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Debit amount must be zero.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    throw ValidationException::withMessages([
                        'message' => ['Validation error: Debit amount must be zero in '.$voucher->general_journal_voucher_number.'.']
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

                throw ValidationException::withMessages([
                    'message' => ['Validation error: Description is empty in '.$voucher->general_journal_voucher_number.'.']
                ]);
            }
            
            if($key > 0) {
                $voucher_for_validation = $list_vouchers[$key-1];

                if($voucher_for_validation->invoice_number != $voucher->invoice_number) {
                    $voucher_for_validation->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice number is not match with '.$voucher->general_journal_voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice number is not match with '.$voucher_for_validation->general_journal_voucher_number.'.',
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
                        'log_message' => 'Error: Invoice date is not match with '.$voucher->general_journal_voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice date is not match with '.$voucher_for_validation->general_journal_voucher_number.'.',
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
                        'log_message' => 'Error: Payment ID is not match with '.$voucher->general_journal_voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Payment ID is not match with '.$voucher_for_validation->general_journal_voucher_number.'.',
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
                        'log_message' => 'Error: Entry pair is not match with '.$voucher->general_journal_voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Entry pair is not match with '.$voucher_for_validation->general_journal_voucher_number.'.',
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
        $item = GeneralJournal::withTrashed()->findOrFail($id);
 
        // get params from logged in user
        $login_user_company_id = auth()->user()->company_id;

        DB::beginTransaction();
            $vouchers = $item->general_journal_vouchers()->whereNotNull('approved_date')->get();

            if(!$vouchers->count()) {
                throw ValidationException::withMessages([
                    'message' => ['No available approved voucher']
                ]);
            }

            foreach ($item->general_journal_vouchers()->whereNotNull('approved_date')->get() as $key => $voucher) {

                $client_id = $voucher->client_id;

                // getting available ledger 
                $ledger = Ledger::where('client_id', $client_id)->where('company_id', $login_user_company_id)->whereDate('active_from', '<=', $voucher->invoice_date)->whereDate('active_to', '>=', $voucher->invoice_date)->first();

                if(!$ledger) {
                    throw ValidationException::withMessages([
                        'message' => ['No available ledger found for the of voucher line: '. $voucher->general_journal_voucher_number]
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
                                        
                    // auto detect if main account or offset accounts

                    $id = $voucher->main_account && $voucher->main_account != '---' ? $voucher->main_account : $voucher->offset_account;
                    $main_account = $chart_of_account->main_accounts()->where('id', $id)->first();

                    // getting the general ledger from ledger and checking if invoice date is within range of general ledger
                    $general_ledger = $ledger->general_ledger()->whereDate('period_from', '<=', $voucher->invoice_date)->whereDate('period_to', '>=', $voucher->invoice_date)->first();

                    if(!$general_ledger) {
                        throw ValidationException::withMessages([
                            'message' => ['No general ledger found.']
                        ]);
                    }

                    // inserting the voucher to general ledger lines
                    $count = $ledger->general_ledger->general_ledger_lines->count() + 1;
                    $number = str_pad($count, 4, '0', STR_PAD_LEFT);
                    $code = 'GNRLLDGRLN-'.now()->format('m-d-y').'-'.$number;
                    $general_ledger->general_ledger_lines()->create([
                        'accrual_id' => $voucher->accrual_id,
                        'journal_line_id' => $voucher->voucher_line_number,
                        'ledger_journal_code' => $code,
                        'ledger_journal_line_id' => $voucher->id,
                        'ledger_line_number' => $number,
                        'company_id' => $login_user_company_id,
                        'client_id' => $voucher->client_id,
                        'ledger_calendar' => $ledger_calendar->id,
                        'journal_header_id' => $item->id,
                        'journal_voucher_id' => $voucher->id,
                        'journal_name' => $item->journal_name,
                        'journal_type' => $item->journal_type,
                        'description' => $item->description,
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
                        'posted_on' => now(),
                        'posted_by' => auth()->user()->id,
                        'ledger_line_no' => $number,
                    ]);

                }else {
                    throw ValidationException::withMessages([
                        'message' => ['No fiscal period available. Please contact your accounting administrator.']
                    ]);
                }

            }

            $item->update([
                'posted_on' => now(),
                'posted_by' => auth()->user()->fullname,
                'journal_status' => 'Posted'
            ]);

        DB::commit();

        return response()->json([
            'message' => 'Posting Success'
        ]);
    }

    public function generateAccrual(Request $request) {

        $vouchers = GeneralJournalVoucher::whereIn('id', $request->ids)->orderby('voucher_date')->get();

        if($vouchers) {
            foreach ($vouchers as $key => $voucher) {
  
                $debit_account = GeneralJournalVoucher::where('entry_pair_number', $voucher->entry_pair_number)
                    ->select('main_account')
                    ->where('main_account', '<>', '---')
                    ->whereNotNull('main_account')->distinct('main_account')
                    ->first();

                $credit_account = GeneralJournalVoucher::where('entry_pair_number', $voucher->entry_pair_number)
                    ->select('offset_account')
                    ->where('offset_account', '<>', '---')
                    ->whereNotNull('offset_account')->distinct('offset_account')
                    ->first();  
                
                $posting = null;
                if($debit_account && $credit_account) {
                    $posting = AccrualPosting::where('ledger_posting_debit_account_number', $debit_account->main_account)
                    ->where('ledger_posting_credit_account_number', $credit_account->offset_account)
                    ->whereNotNull('approved_date')->whereNull('rejected_on')
                    ->whereDate('period_start', '<', $voucher->transaction_date)
                    ->whereDate('period_end', '>', $voucher->transaction_date)
                    ->first();
                }else {
                    throw ValidationException::withMessages([
                        'message' => ['No accrual posting matched to' . $voucher->general_journal_voucher_number]
                    ]);
                }

                if($posting) {
                    $voucher->update(['accrual_id' => $posting->id ]);
                }else {
                    throw ValidationException::withMessages([
                        'message' => ['No accrual posting matched to' . $voucher->general_journal_voucher_number]
                    ]);
                }
            }
        }else {
           
        }

        return response()->json([
            'message' => 'Successful posting'
        ]);

    }

    public function reversal($id) {
        $general_journal = GeneralJournal::find($id);

        if($general_journal) {
            $vouchers = $general_journal->general_journal_vouchers()->whereHas('accrual_posting')->whereNull('reverse_date')->get();

            foreach ($vouchers as $key => $voucher) {
                $vouchers_entries = GeneralJournalVoucher::where('entry_pair_number', $voucher->entry_pair_number)->get();
                
                $voucher->update([
                    'reverse_date' => now(),
                    'reverse_by' => auth()->user()->id,
                ]);

                if($voucher->debit_amount > 0) {
                    $voucher->credit_amount =  $voucher->debit_amount;
                    $voucher->debit_amount = 0.00;
                    $voucher->account_type = '---';
                    $voucher->main_account = '---';
                    $reversal  = $vouchers_entries->where('credit_amount', '>' , 0.00)->first();

                    $voucher->balance_journal = $reversal->balance_journal;
                    $voucher->offset_company_accounts =  $reversal->offset_company_accounts;
                    $voucher->offset_account_type = $reversal->offset_account_type;
                    $voucher->offset_account = $reversal->offset_account;
                    $voucher->offset_transaction_text = $reversal->offset_transaction_text;
                } else if($voucher->credit_amount > 0) {
                    $voucher->debit_amount =  $voucher->credit_amount;
                    $voucher->credit_amount = 0.00;

                    $voucher->offset_company_accounts = '---';
                    $voucher->offset_account_type = '---';
                    $voucher->offset_account = '---';
                    $voucher->offset_transaction_text = '---';
                    $reversal = $vouchers_entries->where('debit_amount', '>' , 0.00)
                    ->first();
                   
                    $voucher->balance_journal = $reversal->balance_journal;
                    $voucher->account_type = $reversal->account_type;
                    $voucher->main_account =  $reversal->main_account;
                    $voucher->reverse_date = now();
                    $voucher->reverse_by = auth()->user()->id;
                }

                $voucher->general_journal_voucher_number .= ' (1)';

                $voucher = collect($voucher)->except(['updateUrl', 'id', 'created_at']);
                GeneralJournalVoucher::create($voucher->toArray());
            }
           
        }else {
            throw ValidationException::withMessages([
                'message' => ['General journal doesnt exist.']
            ]);
        }

        return response()->json([
            'message' => 'Successful Reversal'
        ]);
    }
}
