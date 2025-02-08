<?php

namespace App\Http\Controllers\BankAccountStatementLines;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\BankAccountStatements\BankAccountStatementLine;
use App\Models\AdminSetups\BankReason;
use App\Models\Users\User;

use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;

use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\JournalLines\CustomerPaymentJournalVoucher;

use Carbon\Carbon;

class BankAccountStatementLineFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankAccountStatementLine;
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
        if($this->request->matched) {
            $query = $query->where('matched_checkbox', true);
        }

        if($this->request->filled('bank_account')) {
            $bank_account = $this->request->bank_account;
            $query = $query->whereHas('statement', function($statement) use ($bank_account) {
                $statement->whereHas('transaction', function($transaction) use ($bank_account) {
                    $transaction->where('client_bank_account_number', $bank_account);
                });
            });
        }

        if($this->request->filled('reconciled')) {
            $query = $query->where('reconciled_checkbox', $this->request->input('reconciled'));
        }

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
                'transaction_date' => $item->transaction_date,
                'payment_reference' => $item->payment_reference,
                'bank_transaction_code' => $item->bank_transaction_code,
                'bank_reason' => $item->bankReason ? $item->bankReason->default_comment : '---',
                'withdrawal_debit_amount' => $item->withdrawal_debit_amount,
                'deposit_credit_amount' => $item->deposit_credit_amount,
                'reconciled_date' => $item->reconciled_date ? Carbon::parse($item->reconciled_date)->format('m/d/Y h:i A') : '---',
                'adjustment_date' => $item->adjustment_date ? Carbon::parse($item->adjustment_date)->format('m/d/Y h:i A') : '---',
                'matched_checkbox' => $item->matched_checkbox,
                'adjustment_checkbox' => $item->adjustment_checkbox,
                'statement_id' => $item->statement_id,
                'reconciled_checkbox' => $item->reconciled_checkbox,
                'check_number' => $item->check_number,
                'deposit_slip_number' => $item->deposit_slip_number,
                'outstanding_balance' => $item->renderEndingBalance(),

                'created_at' => $item->renderDate(),
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

        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $bank_reasons = BankReason::all();
        $vendor_payment_methods = VendorPaymentMethod::all();
        $customer_payment_methods = CustomerPaymentMethod::all();

        $vendor_payment_journal_vouchers = VendorPaymentJournalVoucher::all();
        $customer_payment_journal_vouchers = CustomerPaymentJournalVoucher::all();

        if($id) {
            $item = BankAccountStatementLine::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'bank_reasons' => $bank_reasons,
            'vendor_payment_methods' => $vendor_payment_methods,
            'customer_payment_methods' => $customer_payment_methods,
            'vendor_payment_journal_vouchers' => $vendor_payment_journal_vouchers,
            'customer_payment_journal_vouchers' => $customer_payment_journal_vouchers,
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
