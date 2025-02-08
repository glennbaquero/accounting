<?php

namespace App\Http\Controllers\BankReconciliations;

use Illuminate\Http\Request;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\BankReconciliations\BankReconciliation;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\CashflowTransactions\CashflowTransaction;
use App\Models\CashflowTransactions\CashflowTransactionAdjustment;
use App\Models\BankAccountStatements\BankAccountStatement;
use App\Models\BankAccountStatements\BankAccountStatementLine;
use App\Models\BankAccountStatements\BankAccountStatementLineAdjustment;
use App\Models\BankPostings\BankPosting;

use Carbon\Carbon;

class BankReconciliationFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankReconciliation;
    }

    /**
     * Custom filtering of query
     * 
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        /**
         * Queries
         * 
         */
        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

        $query = $query->where('company_id', auth()->user()->company_id);
        
        return $query;
    }

    /**
     * Custom formatting of data
     * 
     * @param Illuminate\Support\Collection $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'bank_reconciliation_id' => $item->bank_reconciliation_id,
                'name' => $item->name,
                'description' => $item->description,
                'reconciled_checkbox' => $item->reconciled_checkbox,
                'posted_checkbox' => $item->posted_checkbox,
                'approved_checkbox' => $item->approved_checkbox,
                'ending_balance' => $item->ending_balance,
                'reconciled_transactions' => $item->reconciled_transactions,
                'unreconciled_transactions' => $item->unreconciled_transactions,
                'bank_statement_id' => $item->bank_statement ? $item->bank_statement->bank_statement_id : $item->bank_statement_id,
                'cash_register_id' => $item->cash_register ? $item->cash_register->cashflow_transaction_id : $item->cash_register_id,
                'deleted_at' => $item->deleted_at,
            ]);

            array_push($result, $data);
        }

        return $result;
    }

    /**
     * Build array data
     * 
     * @param  App\Contracts\AvailablePosition
     * @return array
     */
    protected function formatItem($item)
    {
        return [
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        $client_banks = ClientBankAccount::all();
        $cash_registers = CashflowTransaction::all();
        $bank_statements = BankAccountStatement::all();

        if ($id) {
            $item = BankReconciliation::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'client_banks' => $client_banks,
            'cash_registers' => $cash_registers,
            'bank_statements' => $bank_statements,
        ]);
    }

    protected function formatView($item)
    {
        $item->approved_by = $item->renderApprovedBy();
        $item->posted_by = $item->renderPostedBy();
        $item->reconciled_by = $item->renderReconciledBy();

        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();

        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }

    public function fetchDetails(Request $request) {

        # To Reoncile Bank Statement

        $toReconcilePosting = BankPosting::whereHas('bank_statement_line_adjustment', function($lineAdjustment) {
            $lineAdjustment->where('reconciled_checkbox', false)->where('matched_checkbox', false);
        });

        $toReconcilePostingAmount = 0;

        foreach($toReconcilePosting->get() as $posting) {
            $toReconcilePostingAmount += $posting->bank_statement_line_adjustment->deposit_credit_amount;
        }

        $reconcile_bank_statement = [
            'deposit_count' => BankAccountStatementLine::where('reconciled_checkbox', false)->where('matched_checkbox', false)->where('deposit_credit_amount', '!=', '0')->count(),
            'deposit_amount' => BankAccountStatementLine::where('reconciled_checkbox', false)->where('matched_checkbox', false)->whereNotNull('deposit_credit_amount')->sum('deposit_credit_amount'),

            'witddrawal_count' => BankAccountStatementLine::where('reconciled_checkbox', false)->where('matched_checkbox', false)->where('withdrawal_debit_amount', '!=', '0')->count(),
            'witddrawal_amount' => BankAccountStatementLine::where('reconciled_checkbox', false)->where('matched_checkbox', false)->whereNotNull('withdrawal_debit_amount')->sum('withdrawal_debit_amount'),

            'statement_check_count' => BankAccountStatementLine::where('reconciled_checkbox', false)->where('matched_checkbox', false)->whereNotNull('check_id')->count(),
            'statement_check_amount' => BankAccountStatementLine::where('reconciled_checkbox', false)->where('matched_checkbox', false)->whereNotNull('check_id')->sum('deposit_credit_amount'),

            'bank_posting_count' => $toReconcilePosting->count(),
            'bank_posting_amount' => $toReconcilePostingAmount,

        ];

        # To Reconcile Cash Register

        $reconcile_cash_register = [
            'customer_payment_count' => CashflowTransaction::where('reconciled_checkbox', false)->where('matched', false)->whereNotNull('customer_payment_journal_voucher')->count(),
            'customer_payment_amount' => CashflowTransaction::where('reconciled_checkbox', false)->where('matched', false)->sum('debit_amount'),

            'vendor_payment_count' => CashflowTransaction::where('reconciled_checkbox', false)->where('matched', false)->whereNotNull('vendor_payment_journal_voucher')->count(),
            'vendor_payment_amount' => CashflowTransaction::where('reconciled_checkbox', false)->where('matched', false)->whereNotNull('vendor_payment_journal_voucher')->sum('debit_amount'),
            
            'deposit_slip_count' => CashflowTransaction::where('reconciled_checkbox', false)->where('matched', false)->whereNotNull('deposit_slip_number')->count(),
            'deposit_slip_amount' => CashflowTransaction::where('reconciled_checkbox', false)->where('matched', false)->whereNotNull('deposit_slip_number')->sum('debit_amount'),

            'check_count' => CashflowTransaction::where('reconciled_checkbox', false)->where('matched', false)->whereNotNull('check_number')->count(),
            'check_amount' => CashflowTransaction::where('reconciled_checkbox', false)->where('matched', false)->whereNotNull('check_number')->sum('check_amount'),
        ];

        # Matched Bank Statement

        $matchedPosting = BankPosting::whereHas('bank_statement_line_adjustment', function($lineAdjustment) {
            $lineAdjustment->where('matched_checkbox', true);
        });

        $matchedPostingAmount = 0;

        foreach($matchedPosting->get() as $posting) {
            $matchedPostingAmount += $posting->bank_statement_line_adjustment->deposit_credit_amount;
        }

        $matched_bank_statement = [
            'total_matched_count' => BankAccountStatementLine::where('matched_checkbox', true)->count() + CashflowTransaction::where('matched', true)->count(),

            'deposit_matched_count' => BankAccountStatementLine::where('matched_checkbox', true)->where('deposit_credit_amount', '!=', '0')->count(),
            'deposit_matched_amount' => BankAccountStatementLine::where('matched_checkbox', true)->whereNotNull('deposit_credit_amount')->sum('deposit_credit_amount'),

            'withdrawal_matched_count' => BankAccountStatementLine::where('matched_checkbox', true)->where('withdrawal_debit_amount', '!=', '0')->count(),
            'withdrawal_matched_amount' => BankAccountStatementLine::where('matched_checkbox', true)->whereNotNull('withdrawal_debit_amount')->sum('withdrawal_debit_amount'),

            'statement_check_matched_count' => BankAccountStatementLine::where('matched_checkbox', true)->whereNotNull('check_id')->count(),
            'statement_check_matched_amount' => BankAccountStatementLine::where('matched_checkbox', true)->whereNotNull('check_id')->sum('deposit_credit_amount'),

            'bank_posting_matched_count' => $matchedPosting->count(),
            'bank_posting_matched_amount' => $matchedPostingAmount,

        ];

        # Matched Cash Register

        $matched_cash_register = [
            'total_matched_amount' => BankAccountStatementLine::where('matched_checkbox', true)->sum('deposit_credit_amount') + CashflowTransaction::where('matched', true)->sum('check_amount'),
            'customer_payment_matched_count' => CashflowTransaction::where('matched', true)->whereNotNull('customer_payment_journal_voucher')->count(),
            'customer_payment_matched_amount' => CashflowTransaction::where('matched', true)->sum('debit_amount'),

            'vendor_payment_matched_count' => CashflowTransaction::where('matched', true)->whereNotNull('vendor_payment_journal_voucher')->count(),
            'vendor_payment_matched_amount' => CashflowTransaction::where('matched', true)->whereNotNull('vendor_payment_journal_voucher')->sum('debit_amount'),
            
            'deposit_slip_matched_count' => CashflowTransaction::where('matched', true)->whereNotNull('deposit_slip_number')->count(),
            'deposit_slip_matched_amount' => CashflowTransaction::where('matched', true)->whereNotNull('deposit_slip_number')->sum('debit_amount'),

            'check_matched_count' => CashflowTransaction::where('matched', true)->whereNotNull('check_number')->count(),
            'check_matched_amount' => CashflowTransaction::where('matched', true)->whereNotNull('check_number')->sum('check_amount'),
        ];

        # Adjustment Bank Statement

        $adjustmentPosting = BankPosting::whereHas('bank_statement_line_adjustment', function($lineAdjustment) {
            $lineAdjustment->where('reconciled_checkbox', false)->where('matched_checkbox', false);
        });

        $adjustmentPostingAmount = 0;

        foreach($adjustmentPosting->get() as $posting) {
            $adjustmentPostingAmount += $posting->bank_statement_line_adjustment->deposit_credit_amount;
        }

        $adjustment_bank_statement = [
            'statement_adjustment_count' => BankAccountStatementLineAdjustment::where('adjustment_checkbox', true)->count(),
            'statement_adjustment_amount' => BankAccountStatementLineAdjustment::where('adjustment_checkbox', true)->sum('deposit_credit_amount'),

            'statement_adjustment_check_count' => BankAccountStatementLine::where('adjustment_checkbox', true)->whereNotNull('check_id')->count(),
            'statement_adjustment_check_amount' => BankAccountStatementLine::where('adjustment_checkbox', true)->whereNotNull('check_id')->sum('deposit_credit_amount'),

            'bank_posting_adjustment_count' => $adjustmentPosting->count(),
            'bank_posting_adjustment_amount' => $adjustmentPostingAmount,
        ];

        # Cash Register Adjustments

        $adjustment_cash_register = [
            'cash_register_adjustment_count' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->count(),
            'cash_register_adjustment_amount' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->sum('debit_amount'),

            'customer_payment_adjustment_count' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->whereNotNull('customer_payment_journal_voucher')->count(),
            'customer_payment_adjustment_amount' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->whereNotNull('customer_payment_journal_voucher')->sum('debit_amount'),

            'vendor_payment_adjustment_count' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->whereNotNull('vendor_payment_journal_voucher')->count(),
            'vendor_payment_adjustment_amount' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->whereNotNull('vendor_payment_journal_voucher')->sum('debit_amount'),

            'outstanding_checks_count' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->whereNotNull('check_number')->count(),
            'outstanding_checks_amount' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->whereNotNull('check_number')->sum('check_amount'),

            'deposit_transit_count' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->whereNotNull('deposit_slip_number')->count(),
            'deposit_transit_amount' => CashflowTransactionAdjustment::where('adjustment_checkbox', true)->whereNotNull('deposit_slip_number')->sum('debit_amount'),
        ];

        return response()->json([
            'reconcile_bank_statement' => $reconcile_bank_statement,
            'reconcile_cash_register' => $reconcile_cash_register,
            'matched_bank_statement' => $matched_bank_statement,
            'matched_cash_register' => $matched_cash_register,
            'adjustment_bank_statement' => $adjustment_bank_statement,
            'adjustment_cash_register' => $adjustment_cash_register,
            'status' => 200,
        ]);
    }
}
