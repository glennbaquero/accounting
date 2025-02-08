<?php

namespace App\Http\Controllers\BankReconciliationJournals;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\BankReconciliationJournals\BankReconciliationJournalVoucher;

use App\Models\AdminSetups\ClientBankAccount;
use App\Models\CashflowTransactions\CashflowTransaction;
use App\Models\BankReconciliations\BankReconciliation;
use App\Models\Checks\Check;
use App\Models\Deposits\Deposit;
use App\Models\PurchaseOrders\VendorPayment;
use App\Models\SalesOrders\CustomerPayment;

use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\BankPostings\BankPosting;
use App\Models\AdminSetups\BankReason;

use App\Models\CashflowTransactions\CashflowTransactionAdjustment;
use App\Models\BankAccountStatements\BankAccountStatementLineAdjustment;
use App\Models\BankAccountStatements\BankAccountStatement;

use App\Models\BankAccountTransactions\BankAccountTransaction;

use Carbon\Carbon;

class BankReconciliationJournalVoucherFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankReconciliationJournalVoucher;
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
        
        return $query->where('bank_reconciliation_journal_id', $this->request->bank_reconciliation_id);
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
                'voucher_number' => $item->voucher_number,
                'customer_payment_id' => $item->customer_payment_id,
                'customer_payment_issued_date' => $item->customer_payment_issued_date,
                'customer_name' => $item->customer_name,
                'vendor_payment_id' => $item->vendor_payment_id,
                'vendor_payment_issued_date' => $item->vendor_payment_issued_date,
                'vendor_name' => $item->vendor_name,
                'check_id' => $item->check_id,
                'check_amount' => $item->check_amount,
                'deposit_id' => $item->deposit_id,
                'payment_reference' => $item->payment_reference,
                'created_at' => $item->renderDate('created_at', 'm/d/Y h:i A'),
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
            'updateUrl' => $item->renderUpdateUrl(),
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        $client_banks = ClientBankAccount::all();
        $bank_statement_adjustments = BankAccountStatementLineAdjustment::all();
        $cash_register_adjustments = CashflowTransactionAdjustment::all();
        $bank_statements = BankAccountStatement::all();

        $bank_reconciliations = BankReconciliation::all();
        $checks = Check::all();
        $deposits = Deposit::all();
        $vendor_payments = VendorPayment::all();
        $customer_payments = CustomerPayment::all();

        $vendor_payment_methods = VendorPaymentMethod::all();
        $customer_payment_methods = CustomerPaymentMethod::all();

        $bank_postings = BankPosting::all();
        $bank_reasons = BankReason::all();
        $bank_transactions = BankAccountTransaction::all();

        if ($id) {
            $item = BankReconciliationJournalVoucher::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'client_banks' => $client_banks,
            'bank_statement_adjustments' => $bank_statement_adjustments,
            'cash_register_adjustments' => $cash_register_adjustments,
            'bank_reconciliations' => $bank_reconciliations,
            'checks' => $checks,
            'deposits' => $deposits,
            'vendor_payments' => $vendor_payments,
            'customer_payments' => $customer_payments,
            'vendor_payment_methods' => $vendor_payment_methods,
            'customer_payment_methods' => $customer_payment_methods,
            'bank_postings' => $bank_postings,
            'bank_reasons' => $bank_reasons,
            'bank_transactions' => $bank_transactions,
            'bank_statements' => $bank_statements,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
