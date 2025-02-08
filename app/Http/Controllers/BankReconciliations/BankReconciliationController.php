<?php

namespace App\Http\Controllers\BankReconciliations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\JournalLines\CustomerPaymentJournalVoucher;

use App\Http\Controllers\VendorPaymentJournals\VendorPaymentJournalController;
use App\Http\Controllers\CustomerPaymentJournals\CustomerPaymentJournalController;

use App\Models\AdminSetups\ClientBankAccount;
use App\Models\CashflowTransactions\CashflowTransaction;

use App\Models\CashflowTransactions\CashflowTransactionAdjustment;
use App\Models\AdminSetups\Client;

use App\Models\BankAccountStatements\BankAccountStatementLineAdjustment;
use App\Models\BankAccountStatements\BankAccountStatementLine;
use App\Models\BankAccountStatements\BankAccountStatement;
use App\Http\Requests\BankReconciliations\BankReconciliationStoreRequest;

use App\Models\BankReconciliations\BankReconciliation;
use App\Models\BankReconciliations\BankReconciliationLine;
use App\Models\BankReconciliationJournals\BankReconciliationJournal;
use App\Models\BankAccountTransactions\BankAccountTransaction;

use App\Models\BankPostings\BankPosting;

use App\Models\SalesOrders\CustomerPayment;
use App\Models\SalesOrders\Checks\Check;

use App\Http\Controllers\BankAccountStatementLines\BankAccountStatementLineAdjustmentController;
use App\Http\Controllers\CashflowTransactions\CashflowTransactionAdjustmentController;

use Illuminate\Validation\ValidationException;

use DB;

class BankReconciliationController extends Controller
{
    public function index()
    {
        $client_banks = ClientBankAccount::all();
        $bank_statements = BankAccountStatement::all();
        $clients = Client::all();

        return view('bank-reconciliations.index', [
            'bank_statements' => $bank_statements,
            'client_banks' => $client_banks,
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
        $clients = Client::all();
        return view('bank-reconciliations.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankReconciliationStoreRequest $request)
    {
        $item = BankReconciliation::store($request);

        $message = "You have successfully created {$item->name}";
        $redirect = $item->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankReconciliation  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = BankReconciliation::withTrashed()->findOrFail($id);
        $clients = Client::all();

        return view('bank-reconciliations.show', [
            'item' => $item,
            'clients' => $clients,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankReconciliation  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function update(BankReconciliationStoreRequest $request, $id)
    {
        $item = BankReconciliation::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->name}";

        $item = BankReconciliation::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankReconciliation  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = BankReconciliation::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\BankReconciliation  $sampleItem
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = BankReconciliation::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }

    public function showForm()
    {
    	$client_banks = ClientBankAccount::all();
        $bank_statements = BankAccountStatement::all();
        $clients = Client::all();

        return view('bank-reconciliations.form', [
        	'bank_statements' => $bank_statements,
            'client_banks' => $client_banks,
            'clients' => $clients,
        ]);
    }

    public function generateCashRegisters(Request $request) 
    {
    	$customer_controller = new CustomerPaymentJournalController;
    	$customer_vouchers = CustomerPaymentJournalVoucher::where('bank_account', $request->client_bank)->get();

    	$vendor_vouchers = VendorPaymentJournalVoucher::where('bank_account', $request->client_bank)->get();
    	$vendor_controller = new VendorPaymentJournalController;

    	foreach($vendor_vouchers as $voucher) {
    		$vendor_controller->insertCashflow($voucher, $request);
    	}

    	foreach($customer_vouchers as $voucher) {
    		$customer_controller->insertCashflow($voucher, $request);
    	}

    	$count = count($vendor_vouchers) + count($customer_vouchers);

    	return response()->json([
    		'created_registers' => $count,
    		'status' => 200,
    	]);
    }

    public function generateMatch(Request $request) {
        $request->validate([
            'client_bank' => ['required'],
            'withdrawal' => ['required_without_all: deposit,description,payment_reference,check_number,deposit_slip_number'],
            'deposit' => ['required_without_all: withdrawal,description,payment_reference,check_number,deposit_slip_number'],
            'description' => ['required_without_all: withdrawal,deposit,payment_reference,check_number,deposit_slip_number'],
            'payment_reference' => ['required_without_all: withdrawal,deposit,description,check_number,deposit_slip_number'],
            'check_number' => ['required_without_all: withdrawal,deposit,description,payment_reference,deposit_slip_number'],
            'deposit_slip_number' => ['required_without_all: withdrawal,deposit,description,payment_reference,check_number'],
        ]);

        if(
            !$request->input('withdrawal') &&
            !$request->input('deposit') &&
            !$request->input('description') &&
            !$request->input('payment_reference') &&
            !$request->input('check_number') &&
            !$request->input('deposit_slip_number')
        ) {
            throw ValidationException::withMessages([
                'message' => 'At Least 1 filter is required for the match',
            ]);
        }

        $client_bank = $request->client_bank;

        $statementLines = BankAccountStatementLine::whereHas('statement', function($statement) use ($client_bank) {
            $statement->whereHas('transaction', function($transaction) use ($client_bank) {
                $transaction->where('client_bank_account_number', $client_bank);
            });
        })->where('matched_checkbox', false)->get();

        DB::beginTransaction();

        $total_matched_lines = 0;
        $total_matched_cash = 0;

        foreach($statementLines as $key => $line) {
            $cashes = CashflowTransaction::where('bank_account', $client_bank)
                    ->where('matched', false);
            
            $cashes = $request->check_number ? $cashes->where('check_number', $line->check_id) : $cashes;
            $cashes = $request->deposit ? $cashes->where('credit_amount', $line->deposit_credit_amount) : $cashes;
            $cashes = $request->withdrawal ? $cashes->where('debit_amount', $line->withdrawal_debit_amount) : $cashes;
            $cashes = $request->deposit_slip_number ? $cashes->where('deposit_slip_number', $line->deposit_id) : $cashes;
            $cashes = $request->payment_reference ? $cashes->where('payment_reference', $line->payment_reference) : $cashes;
            $cashes = $request->description && $request->option ? $cashes->where($request->option, 'LIKE', '%'.$line->description.'%') : $cashes;

            if($cashes->count()) {
                $total_matched_cash += $cashes->count();
                $total_matched_lines += 1;

                $cashes->update(['matched' => true]);
                $line->update(['matched_checkbox' => true]);
            }
        
        }

        $message = "Total matched statements: $total_matched_lines. Total matched cash registers: $total_matched_cash.";

        // DB::commit();

        return response()->json([
            'status' => 200,
            'message' => $message,
        ]);

    }

    public function generateAdjustments(Request $request) {
        $request->validate([
            'client_bank' => ['required'],
        ]);

        $client_bank = $request->client_bank;

        $statementLines = BankAccountStatementLine::whereHas('statement', function($statement) use ($client_bank) {
            $statement->whereHas('transaction', function($transaction) use ($client_bank) {
                $transaction->where('client_bank_account_number', $client_bank);
            });
        })->where('matched_checkbox', false)->where('adjustment_checkbox', false)->doesnthave('cashRegisters')->get();

        $cashRegisters = CashflowTransaction::where('bank_account', $client_bank)
                ->where('adjustment_checkbox', false)
                ->doesnthave('statementLines')
                ->get();

        DB::beginTransaction();

        foreach($cashRegisters as $cashRegister) {
            $this->createCashRegisterAdjustments($request, $cashRegister);
        }

        foreach($statementLines as $statementLine) {
            $this->createStatementLineAdjustments($request, $statementLine);
        }

        $total_cash = $cashRegisters->count();
        $total_lines = $statementLines->count();

        $message = "Total statement lines adjusted: $total_lines. Total cash register adjusted: $total_cash.";
        
        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => $message,
        ]);
    }

    public function createStatementLineAdjustments($request, $statementLine) {
        $statementLine->update([
            'adjustment_checkbox' => true,
            'adjustment_date' => now(),
            'adjusted_by' => $request->user()->id,
        ]);

        $item = BankAccountStatementLineAdjustment::firstOrCreate([
            'statement_line_id' => $statementLine->statement_line_id,
        ], [
            'bank_posting' => $statementLine->bank_posting,
            'statement_id' => $statementLine->statement_id,
            'line_number' => $statementLine->line_number,
            'transaction_date' => $statementLine->transaction_date,
            'payment_reference' => $statementLine->payment_reference,
            'bank_transaction_code' => $statementLine->bank_transaction_code,
            'bank_reason' => $statementLine->bank_reason,
            'withdrawal_debit_amount' => $statementLine->withdrawal_debit_amount,
            'deposit_credit_amount' => $statementLine->deposit_credit_amount,
            'cost_center' => $statementLine->cost_center,
            'department' => $statementLine->department,
            'reconciled_checkbox' => $statementLine->reconciled_checkbox,
            'reconciled_date' => $statementLine->reconciled_date,
            'reconciled_by' => $statementLine->reconciled_by,
            'vendor_payment_journal_voucher' => $statementLine->vendor_payment_journal_voucher,
            'vendor_payment_id' => $statementLine->vendor_payment_id,
            'vendor_account' => $statementLine->vendor_account,
            'vendor_name' => $statementLine->vendor_name,
            'method_of_payment_vendor' => $statementLine->method_of_payment_vendor,
            'customer_payment_journal_voucher' => $statementLine->customer_payment_journal_voucher,
            'customer_payment_id' => $statementLine->customer_payment_id,
            'customer_account' => $statementLine->customer_account,
            'customer_name' => $statementLine->customer_name,
            'method_of_payment_customer' => $statementLine->method_of_payment_customer,
            'bank_reconciliation_id' => $statementLine->bank_reconciliation_id,
            'bank_account_transaction_id' => $statementLine->bank_account_transaction_id,
            'deposit_id' => $statementLine->deposit_id,
            'check_id' => $statementLine->check_id,
            'description' => $statementLine->description,
            'settlement_type' => $statementLine->settlement_type,
            'matched_checkbox' => $statementLine->matched_checkbox,
            'subledger_journal' => $statementLine->subledger_journal,
            'ledger_account' => $statementLine->ledger_account,

            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        if(!$item->bank_statement_adjustment_id) {
            $bank_statement_adjustment_id = 'statement-line-adjustment-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $item->update([
                'bank_statement_adjustment_id' => $bank_statement_adjustment_id,
            ]);
        }

    }
    
    public function createCashRegisterAdjustments($request, $cashRegister) {
        $cashRegister->update([
            'adjustment_checkbox' => true,
            'adjustment_date' => now(),
            'adjustment_by' => $request->user()->id,
        ]);
        $item = CashflowTransactionAdjustment::firstOrCreate([
            'cashflow_transaction_id' => $cashRegister->cashflow_transaction_id,
        ], [
            'company_id' => $cashRegister->company_id,
            'cashflow_transaction_name' => $cashRegister->cashflow_transaction_name,
            'type' => $cashRegister->type,
            'vendor_payment_journal_voucher' => $cashRegister->vendor_payment_journal_voucher,
            'vendor_payment_journal_number' => $cashRegister->vendor_payment_journal_number,
            'customer_payment_journal_voucher' => $cashRegister->customer_payment_journal_voucher,
            'customer_payment_journal_number' => $cashRegister->customer_payment_journal_number,
            'journal_name' => $cashRegister->journal_name,
            'voucher_date' => $cashRegister->voucher_date,
            'description' => $cashRegister->description,
            'debit_amount' => $cashRegister->debit_amount,
            'credit_amount' => $cashRegister->credit_amount,
            'posted_checkbox' => $cashRegister->posted_checkbox,
            'posted_on' => $cashRegister->posted_on,
            'posted_by' => $cashRegister->posted_by,
            'vendor_account' => $cashRegister->vendor_account,
            'vendor_name' => $cashRegister->vendor_name,
            'vendor_invoice_number' => $cashRegister->vendor_invoice_number,
            'customer_account' => $cashRegister->customer_account,
            'customer_name' => $cashRegister->customer_name,
            'customer_invoice_number' => $cashRegister->customer_invoice_number,
            'invoice_date' => $cashRegister->invoice_date,
            'payment_due_date' => $cashRegister->payment_due_date,
            'settlement_type' => $cashRegister->settlement_type,
            'method_of_payment_vendor' => $cashRegister->method_of_payment_vendor,
            'vendor_payment_id' => $cashRegister->vendor_payment_id,
            'method_of_payment_customer' => $cashRegister->method_of_payment_customer,
            'customer_payment_id' => $cashRegister->customer_payment_id,
            'payment_status' => $cashRegister->payment_status,
            'deposit_slip_number' => $cashRegister->deposit_slip_number,
            'payment_specification' => $cashRegister->payment_specification,
            'payment_reference' => $cashRegister->payment_reference,
            'bank_transaction_type' => $cashRegister->bank_transaction_type,
            'bank_account' => $cashRegister->bank_account,
            'postdated_check_status' => $cashRegister->postdated_check_status,
            'check_number' => $cashRegister->check_number,
            'check_number_issued' => $cashRegister->check_number_issued,
            'maturity_date' => $cashRegister->maturity_date,
            'received_date' => $cashRegister->received_date,
            'cashier' => $cashRegister->cashier,
            'salesperson' => $cashRegister->salesperson,
            'issuing_bank_branch' => $cashRegister->issuing_bank_branch,
            'issuing_bank_name' => $cashRegister->issuing_bank_name,
            'stop_payment' => $cashRegister->stop_payment,
            'replacement_check' => $cashRegister->replacement_check,
            'original_check' => $cashRegister->original_check,
            'check_amount' => $cashRegister->check_amount,
            'recipient_name' => $cashRegister->recipient_name,
            'reconciled_checkbox' => $cashRegister->reconciled_checkbox,
            'reconciled_date' => $cashRegister->reconciled_date,
            'reconciled_by' => $cashRegister->reconciled_by,
            // 'adjustment_checkbox' => $cashRegister->adjustment_checkbox,
            // 'adjustment_date' => $cashRegister->adjustment_date,
            // 'adjustment_by' => $cashRegister->adjustment_by,
            'matched' => $cashRegister->matched,
            'main_account' => $cashRegister->main_account,
            'account_type' => $cashRegister->account_type,
            'offset_company_accounts' => $cashRegister->offset_company_accounts,
            'offset_account_type' => $cashRegister->offset_account_type,
            'offset_account' => $cashRegister->offset_account,
            'offset_transaction_text' => $cashRegister->offset_transaction_text,
            'sales_tax_direction' => $cashRegister->sales_tax_direction,
            'sales_tax_group' => $cashRegister->sales_tax_group,
            'item_sales_tax_group' => $cashRegister->item_sales_tax_group,
            'withholding_tax_group' => $cashRegister->withholding_tax_group,
            'fee_account' => $cashRegister->fee_account,
            'fee_id' => $cashRegister->fee_id,
            'fee_amount' => $cashRegister->fee_amount,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        if(!$item->cashflow_adjustment_id) {
            $cashflow_adjustment_id = 'cash-register-adjustment-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $item->update([
                'cashflow_adjustment_id' => $cashflow_adjustment_id,
            ]);
        }
    }

    public function generateReconciliation(Request $request) {
        DB::beginTransaction();

        $bank_account = $request->client_bank;
        $bank_statement_id = $request->bank_statement_id;

        $clientBank = ClientBankAccount::withTrashed()->where('bank_account', $bank_account)->firstOrFail();

        $statements = BankAccountStatementLine::whereHas('statement', function($statement) use ($bank_account) {
            $statement->whereHas('transaction', function($transaction) use ($bank_account) {
                $transaction->where('client_bank_account_number', $bank_account);
            });
        })->where('matched_checkbox', true)->where('reconciled_checkbox', false);

        $cashes = CashflowTransaction::where('bank_account', $bank_account)->where('matched', true)->where('reconciled_checkbox', false);

        $statement_adjustments = BankAccountStatementLineAdjustment::whereHas('statement', function($statement) use ($bank_account) {
            $statement->whereHas('transaction', function($transaction) use ($bank_account) {
                $transaction->where('client_bank_account_number', $bank_account);
            });
        })->where('reconciled_checkbox', false)->where('adjustment_checkbox', true);

        $cash_adjustments = CashflowTransactionAdjustment::where('bank_account', $bank_account)->where('reconciled_checkbox', false)->where('adjustment_checkbox', true);

        if(!$statements->count() && !$cashes->count()) {
            throw ValidationException::withMessages([
                'message' => 'Neither Bank statement or Cash register has match'
            ]);
        }

        $statement_ending_balance = $statements->sum('withdrawal_debit_amount');
        $ending_balance = $statements->sum('withdrawal_debit_amount') + $cashes->sum('debit_amount');
        $reconciled_transactions = $statements->count() + $cashes->count() + $statement_adjustments->count() + $cash_adjustments->count();

        $reconciliation = BankReconciliation::create([
            'company_id' => $request->user()->company_id,
            'client_id' => $request->user()->client_id,
            'reconciled_date' => now(),
            'reconciled_by' => $request->user()->id,
            'reconciled_checkbox' => true,

            'ending_balance' => $ending_balance,
            'reconciled_transactions' => $reconciled_transactions,
            'unreconciled_transactions' => 0,

            'client_bank_account' => $clientBank->bank_account,
            'bank_account_number' => $clientBank->bank_account_number,
            'bank_account_type' => $clientBank->bank_account_type,

            'bank_statement_id' => $bank_statement_id,
            'statement_as_of_date' => now(),
            'statement_ending_balance' => $statement_ending_balance,
            'statement_open_amount' => 0,

            'cash_register_id' => $cashes->first() ? $cashes->first()->cashflow_transaction_id : null,
            'cash_register_as_of_date' => now(),

            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $bank_reconciliation_id = 'BA-reconciliation-id-' . date('Ymd') . '-' . str_pad($reconciliation->id, 4, '0', STR_PAD_LEFT);
        $reconciliation->update([
            'bank_reconciliation_id' => $bank_reconciliation_id,
        ]);

        foreach($statement_adjustments->get() as $statement) {
            $controller = new BankAccountStatementLineAdjustmentController;

            $result = $controller->approve($request, $statement->id);

            $posting = BankPosting::where('bank_statement_line_adjustment_id', $statement->id)->first();

            $recon_line = BankReconciliationLine::create([
                'company_id' => $request->user()->company_id,
                'client_id' => $request->user()->client_id,
                'bank_reconciliation_id' => $bank_reconciliation_id,
                'source' => 'Bank Statement',
                'statement_adjustment_id' => $statement->bank_statement_adjustment_id,
                'bank_posting_id' => $posting ? $posting->id : nuil,
            ]);

            $bank_reconciliation_line_id = 'BA-reconciliation-line-id-' . date('Ymd') . '-' . str_pad($recon_line->id, 4, '0', STR_PAD_LEFT);

            $recon_line->update([
                'bank_reconciliation_line_id' => $bank_reconciliation_line_id,
            ]);
        }

        foreach($cash_adjustments->get() as $cash) {
            $controller = new CashflowTransactionAdjustmentController;

            $result = $controller->approve($request, $cash->id);

            $posting = BankPosting::where('cash_register_adjustment_id', $cash->id)->first();

            $recon_line = BankReconciliationLine::create([
                'company_id' => $request->user()->company_id,
                'client_id' => $request->user()->client_id,
                'bank_reconciliation_id' => $bank_reconciliation_id,
                'source' => 'Cash Register',
                'cash_register_adjustment_id' => $cash->cashflow_adjustment_id,
                'bank_posting_id' => $posting ? $posting->id : nuil,
            ]);

            $bank_reconciliation_line_id = 'BA-reconciliation-line-id-' . date('Ymd') . '-' . str_pad($recon_line->id, 4, '0', STR_PAD_LEFT);

            $recon_line->update([   
                'bank_reconciliation_line_id' => $bank_reconciliation_line_id,
            ]);
        }

        $statements->update(['reconciled_checkbox' => true]);
        $statement_adjustments->update(['reconciled_checkbox' => true, 'approved_date' => now(), 'approved_by' => $request->user()->id]);

        $cash_adjustments->update(['reconciled_checkbox' => true, 'approved_date' => now(), 'approved_by' => $request->user()->id]);
        $cashes->update(['reconciled_checkbox' => true]);

        DB::commit();

        return response()->json([
            'redirect' => $reconciliation->renderShowUrl(),
        ]);
    }

    public function post(Request $request, $id) 
    {
        $item = BankReconciliation::withTrashed()->findOrFail($id);

        $journal_header = BankReconciliationJournal::where('bank_account', $item->client_bank_account)->first();

        if($request->filled('ids')) {
            $lines = $item->bank_reconciliation_lines()->whereIn('id', $request->ids)->get();
        } else {
            $lines = $item->bank_reconciliation_lines;
        }

        foreach ($lines as $key => $line) {
            $journal_header->vouchers()->create([
                'bank_posting' => $line->bank_posting_id,
                'bank_statement_id' => $item->bank_statement_id,
                'cash_register_id' => $item->cash_register_id,
                'bank_reconciliation_id' => $item->bank_reconciliation_id,
                'reconcile_date' => now(),
                'client_id' => $journal_header->client_id,
                'company_id' => $journal_header->company_id,
                'cash_register_adjustment_id' => $item->cash_register_adjustment_id,
                'statement_adjustment_id' => $item->statement_adjustment_id,
                'bank_posting' => $item->bank_posting_id,
                'customer_payment_id' => CustomerPayment::first()->id,
                'customer_payment_issued_date' => CustomerPayment::first()->issue_date,
                'customer_name' => CustomerPayment::first()->customer->fullname,
                'customer_payment_method' => CustomerPayment::first()->method_of_payment->name,
                'check_id' => Check::first()->check_id,
                'check_number' => Check::first()->check_number,
                'check_amount' => Check::first()->check_amount,
                'bank_account_transaction_id' => BankAccountTransaction::first()->id,
            ]);
        }

        $item->update([
            'posted_date' => now(),
            'posted_by' => auth()->user()->id,
            'posted_checkbox' => true,
            'approved_date' => now(),
            'approved_by' => auth()->user()->id,
            'approved_checkbox' => true
        ]);

        $redirect = $journal_header->renderShowUrl().'#bank_recon';

        return response()->json([
            'message' => "You have successfully posted {$item->bank_reconciliation_id}",
            'redirect' => $redirect
        ]);
    }

    public function approved(Request $request, $id) 
    {
        $item = BankReconciliation::withTrashed()->findOrFail($id);

        $item->update([
            'posted_date' => now(),
            'posted_by' => auth()->user()->id,
            'posted_checkbox' => true,
            'approved_date' => now(),
            'approved_by' => auth()->user()->id,
            'approved_checkbox' => true
        ]);

        foreach($item->client_bank_account->invoice_approval_journals as $invoice_approval_journal) {
            foreach ($invoice_approval_journal->invoice_approval_journal_vouchers as $voucher) {

                foreach($voucher->vendor_invoice->payments as $payment) {
                    $payment->update([
                        'payment_status' => 'Approved'
                    ]);
                }

                foreach($voucher->vendor_invoice->checks as $check) {
                    $check->update([
                        'approved_date' => now(),
                        'approved_by' => auth()->user()->id
                    ]);
                }

                $voucher->vendor_invoice->purchase_order->update([
                    'purchase_order_status' => 'Invoiced'
                ]);

                $voucher->vendor_invoice->update([
                    'invoice_status' => 'Approved'
                ]);
            }

            $invoice_approval_journal->update([
                'journal_status' => 'Approved'
            ]);
        }


        foreach($item->client_bank_account->customer_invoice_approval_journals as $customer_invoice_journal) {
            foreach ($customer_invoice_journal->customer_invoice_approval_journal_vouchers as $voucher) {

                foreach($voucher->customer_invoice->payments as $payment) {
                    $payment->update([
                        'payment_status' => 'Approved'
                    ]);
                }


                foreach($voucher->customer_invoice->checks as $check) {
                    $check->update([
                        'approved_date' => now(),
                        'approved_by' => auth()->user()->id
                    ]);
                }

                $voucher->customer_invoice->sales_order->update([
                    'sales_order_status' => 'Invoiced'
                ]);

                $voucher->customer_invoice->update([
                    'invoice_status' => 'Approved'
                ]);
            }

            $customer_invoice_journal->update([
                'journal_status' => 'Approved'
            ]);
        }
        return response()->json([
            'message' => "You have successfully posted {$item->bank_reconciliation_id}",
            'redirect' => $redirect
        ]);
    }

}
