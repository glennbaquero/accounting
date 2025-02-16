<?php

namespace App\Http\Controllers\PromissoryNotes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\PromissoryNotes\PromissoryNoteStoreRequest;

use App\Models\Journals\PromissoryNote;
use App\Models\JournalLines\PromissoryNoteVoucher;
use App\Models\Invoices\CustomerInvoice;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\Ledgers\Ledger;
use App\Models\Users\User;

use DB;

class PromissoryNoteController extends Controller
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
	 
	 	return view('promissory-notes.index', [
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

	 	return view('promissory-notes.create', [
	     	//
	 	]);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(PromissoryNoteStoreRequest $request)
	{
	 	$count = PromissoryNote::withTrashed()->count();
	 	$number = str_pad($count, 4, '0', STR_PAD_LEFT);
	 	$request['created_by'] = auth()->user()->fullname;
	 	$request['updated_at'] = null;
	 	$request['journal_status'] = 'Open';
	 	$request['protest_settlements'] = '---';
	 	$request['protest_settled_process'] = '---';
	 	$request['locked_by_system'] = '---';
	 	$request['private_for_user_group'] = '---';
	 	$request['financial_dimensions'] = '---';
	 	$item = PromissoryNote::store($request);

	 	$message = "You have successfully created {$item->bill_exchange_journal_number}";
	 	$redirect = $item->renderShowUrl();

	 	return response()->json([
	     	'message' => $message,
	     	'redirect' => $redirect,
	 	]);
	}

	/**
	* Display the specified resource.
	*
	* @param  \App\PromissoryNote  $sampleItem
	* @return \Illuminate\Http\Response
	*/
	public function show($id)
	{
	 	$item = PromissoryNote::withTrashed()->findOrFail($id);

	 	return view('promissory-notes.show', [
	     	'item' => $item,
	 	]);
	}

    public function edit($id)
    {
        $item = PromissoryNote::withTrashed()->findOrFail($id);
        return view('promissory-notes.update', [
            'item' => $item,
        ]);
    }

	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\PromissoryNote  $sampleItem
	* @return \Illuminate\Http\Response
	*/
	public function update(PromissoryNoteStoreRequest $request, $id)
	{
	 	$item = PromissoryNote::withTrashed()->findOrFail($id);
	 	$message = "You have successfully updated {$item->bill_exchange_journal_number}";
	 	$request['updated_by'] = auth()->user()->fullname;

	 	$item = PromissoryNote::store($request, $item);

	 	return response()->json([
	     	'message' => $message,
	 	]);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\PromissoryNote  $sampleItem
	* @return \Illuminate\Http\Response
	*/
	public function archive($id)
	{
	 	$item = PromissoryNote::withTrashed()->findOrFail($id);
	 	$item->archive();

	 	return response()->json([
	     	'message' => "You have successfully archived {$item->bill_exchange_journal_number}",
	 	]);
	}

	/**
	* Restore the specified resource from storage.
	*
	* @param  \App\PromissoryNote  $sampleItem
	* @return \Illuminate\Http\Response
	*/
	public function restore($id)
	{
	 	$item = PromissoryNote::withTrashed()->findOrFail($id);
	 	$item->unarchive();

	 	return response()->json([
	     	'message' => "You have successfully restored {$item->bill_exchange_journal_number}",
	 	]);
	}


	public function fetch(Request $request) 
	{
	 	$items = new PromissoryNote;

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

	public function createVouchers(Request $request, $id)
	{
	 	$item = PromissoryNote::withTrashed()->findOrFail($id);
	 	$request['promissory_note_journal_number'] = $item->promissory_note_journal_number;
	 	$request['invoice_journal_batch_number'] = $item->invoice_journal_batch_number;
	 	$request['journal_name'] = $item->journal_name;
	 	$request['voucher_line_number'] = $item->promissory_note_vouchers()->count() + 1;
	 	$request['voucher_date'] = now();
	 	$request['vendor_invoice_number'] = '---';
	 	$request['created_by'] = auth()->user()->fullname;
	 	PromissoryNoteVoucher::create($request->all());


	 	return response()->json([
	     	'message' => "Successfully added a new voucher in {$item->promissory_note_journal_number}",
	 	]);
	}

	public function updateVoucher(Request $request, $id) 
	{
	 	$request['updated_by'] = auth()->user()->fullname;
	 	$request['updated_at'] = now();
	 	$item = PromissoryNoteVoucher::withTrashed()->findOrFail($id);
	 	$item->update($request->except(['selected', 'updateUrl', 'pending_customer_invoice', 'sales_tax_included_in_amount', 'alreadyInSelectedItem', 'promissory_note_vouchers', 'created_by', 'created_date', 'updated_date', 'posted_by_name', 'offset_account_id', 'normal_balance', 'increase_rule', 'decrease_rule', 'client']));
	 	// $item->;

	 	return response()->json([
	     	'message' => "Successfully update a new voucher in {$item->invoice_voucher_number}",
	 	]);
	}

	public function updateStatusVoucher(Request $request) 
    {
        foreach ($request->selectedIds as $id) {
            $item = PromissoryNoteVoucher::withTrashed()->findOrFail($id);
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
            $item = PromissoryNote::withTrashed()->findOrFail($id);
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
        $item = PromissoryNote::withTrashed()->findOrFail($id);
        $total_credit = $item->promissory_note_vouchers->sum('credit_amount');
        $total_debit = $item->promissory_note_vouchers->sum('debit_amount');

        if(!$request->filled('withOffsetAccount')) {
            if($total_debit != $total_credit) {

                $item->promissory_note_vouchers()->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Error: Total not balance',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
                
                throw ValidationException::withMessages([
                    'message' => ['Total not balance']
                ]);
            } else {
                $item->promissory_note_vouchers()->update([
                    'logged_by' => auth()->user()->fullname,
                    'log_message' => 'Success: Ready for posting',
                    'log_in_checkbox' => true,
                    'log_date' => now(),
                ]);
            }    
        } else {
            $offset_account = $item->offset_account;
            $lines_not_same_offset = $item->promissory_note_vouchers()->where('offset_account', '!=', $offset_account)->get();

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
        $vouchers = PromissoryNoteVoucher::withTrashed()->whereIn('id', $request->ids);

        if($vouchers->count()  % 2) {
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
                    'message' => ['Validation error: Debit amount and credit amount has no amount in '.$voucher->invoice_voucher_number.'.']
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
                        'message' => ['Validation error: Debit amount is zero in '.$voucher->invoice_voucher_number.'.']
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
                        'message' => ['Validation error: Credit amount must be zero in '.$voucher->invoice_voucher_number.'.']
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
                        'message' => ['Validation error: Credit amount is zero in '.$voucher->invoice_voucher_number.'.']
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
                        'message' => ['Validation error: Debit amount must be zero in '.$voucher->invoice_voucher_number.'.']
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
                    'message' => ['Validation error: Description is empty in '.$voucher->invoice_voucher_number.'.']
                ]);
            }

            if($key > 0) {
                $voucher_for_validation = $list_vouchers[$key-1];

                if($voucher_for_validation->invoice_number != $voucher->invoice_number) {
                    $voucher_for_validation->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice number is not match with '.$voucher->invoice_voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice number is not match with '.$voucher_for_validation->invoice_voucher_number.'.',
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
                        'log_message' => 'Error: Invoice date is not match with '.$voucher->invoice_voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Invoice date is not match with '.$voucher_for_validation->invoice_voucher_number.'.',
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
                        'log_message' => 'Error: Payment ID is not match with '.$voucher->invoice_voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Payment ID is not match with '.$voucher_for_validation->invoice_voucher_number.'.',
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
                        'log_message' => 'Error: Entry pair is not match with '.$voucher->invoice_voucher_number.'.',
                        'log_in_checkbox' => true,
                        'log_date' => now(),
                    ]);

                    $voucher->update([
                        'logged_by' => auth()->user()->fullname,
                        'log_message' => 'Error: Entry pair is not match with '.$voucher_for_validation->invoice_voucher_number.'.',
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
            'message' => 'Voucher is validated!'
        ]);
    }
    
    public function post(Request $request, $id)
    {
        $item = PromissoryNote::withTrashed()->findOrFail($id);
         
        // get params from logged in user
        $login_user_company_id = auth()->user()->company_id;

        DB::beginTransaction();
            foreach ($item->promissory_note_vouchers()->whereNotNull('approved_date') as $key => $voucher) {

                $client_id = $voucher->client_id;

                // getting available ledger 
                $ledger = Ledger::where('client_id', $client_id)->where('company_id', $login_user_company_id)->whereDate('active_from', '<=', $voucher->invoice_date)->whereDate('active_to', '>=', $voucher->invoice_date)->first();
                

                if(!$ledger) {
                    throw ValidationException::withMessages([
                        'message' => ['No available ledger found for the of voucher line: '. $voucher->invoice_voucher_number]
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
                        'posted_on' => now(),
                        'posted_by' => auth()->user()->id
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
}
