<?php

namespace App\Http\Controllers\CashflowTransactions;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\JournalLines\CustomerPaymentJournalVoucher;

use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;

use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;

use App\Models\CashflowTransactions\CashflowTransaction;

use App\Models\Vendors\Vendor;
use App\Models\Customers\Customer;
use App\Models\Invoices\VendorInvoice;
use App\Models\Invoices\CustomerInvoice;

use Carbon\Carbon;

class CashflowTransactionFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CashflowTransaction;
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
            $query = $query->where('matched', true);
        }

        if($this->request->filled('bank_account')) {
            $query = $query->where('bank_account', $this->request->bank_account);
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
                'vendor_payment_journal_voucher' => $item->vendor_payment_journal_voucher,
                'vendor_payment_journal_number' => $item->vendor_payment_journal_number,
                'customer_payment_journal_voucher_number' => $item->customer_payment_journal_voucher_number,
                'customer_payment_journal_number' => $item->customer_payment_journal_number,
                'vendor_account' => $item->vendor_account,
                'vendor_name' => $item->vendor_name,
                'invoice_number' => $item->invoice_number,
                'invoice_date' => $item->invoice_date,
                'customer_account' => $item->customer_account,
                'customer_name' => $item->customer_name,
                'vendor_invoice_number' => $item->vendor_invoice_number,
                'customer_invoice_number' => $item->customer_invoice_number,
                'invoice_date' => $item->invoice_date,
                'payment_due_date' => $item->payment_due_date,
                'method_of_payment' => $item->method_of_payment,
                'vendor_payment_id' => $item->vendor_payment_id,
                'customer_payment_id' => $item->customer_payment_id,
                'payment_status' => $item->payment_status,
                'deposit_slip_number' => $item->deposit_slip_number,
                'payment_specification' => $item->payment_specification,
                'payment_reference' => $item->payment_reference,
                'bank_transaction_type' => $item->bank_transaction_type,
                'bank_account' => $item->bank_account,
                'postdated_check_status' => $item->postdated_check_status,
                'check_number' => $item->check_number,
                'check_number_issued' => $item->check_number_issued,
                'maturity_date' => $item->maturity_date,
                'received_date' => $item->received_date,
                'cashier' => $item->cashier,
                'salesperson' => $item->salesperson,
                'issuing_bank_branch' => $item->issuing_bank_branch,
                'issuing_bank_name' => $item->issuing_bank_name,
                'stop_payment' => $item->stop_payment,
                'replacement_check' => $item->replacement_check,
                'original_check' => $item->original_check,
                'check_amount' => $item->check_amount,
                'recipient_name' => $item->recipient_name,
                'reconciled_checkbox' => $item->reconciled_checkbox,
                'reconciled_date' => $item->reconciled_date,
                'reconciled_by' => $item->reconciled_by,
                'adjustment_checkbox' => $item->adjustment_checkbox,
                'adjustment_date' => $item->adjustment_date,
                'adjusted_by' => $item->adjusted_by,
                'matched_checkbox' => $item->matched,
                'matched' => $item->matched,
                'type' => $item->type,

                'journal_name' => $item->journal_name,
                'debit_amount' => $item->debit_amount,
                'credit_amount' => $item->credit_amount,
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

        $vendor_vouchers = VendorPaymentJournalVoucher::whereNotNull('posted_on')->get();
        $customer_vouchers = CustomerPaymentJournalVoucher::whereNotNull('posted_on')->get();
        $vendor_payment_methods = VendorPaymentMethod::all();
        $customer_payment_methods = CustomerPaymentMethod::all();
        $mainAccounts = MainAccount::all();

        $vendors = Vendor::all();
        $customers = Customer::all();
        $vendorInvoices = VendorInvoice::all();
        $customerInvoices = CustomerInvoice::all();

        if($id) {
            $item = CashflowTransaction::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'vendor_vouchers' => $vendor_vouchers,
            'customer_vouchers' => $customer_vouchers,
            'vendor_payment_methods' => $vendor_payment_methods,
            'customer_payment_methods' => $customer_payment_methods,
            'vendors' => $vendors,
            'vendorInvoices' => $vendorInvoices,
            'customerInvoices' => $customerInvoices,
            'mainAccounts' => $mainAccounts,
            'customers' => $customers,
        ]);
    }

    protected function formatView($item)
    {
        $item->adjustment_by = $item->renderAdjustedBy();
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
