<?php

namespace App\Http\Controllers\Checks;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\AdminSetups\BankReason;
use App\Models\Checks\Check;
use App\Models\Users\User;

use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\CustomerBankAccount;
use App\Models\Vendors\VendorBankAccount;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\PurchaseOrders\VendorPayment;
use App\Models\SalesOrders\CustomerPayment;

use App\Models\Invoices\VendorInvoice;
use App\Models\Invoices\CustomerInvoice;

use Carbon\Carbon;

class CheckFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Check;
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
        $query = $query->where('company_id', auth()->user()->company_id);
        
        if($this->request->filled('customer_bank_account_number')) {
            $query = $query->where('customer_bank_account_number', $this->request->customer_bank_account_number);
        }

        if($this->request->filled('client_bank_account_number')) {
            $query = $query->where('client_bank_account_number', $this->request->client_bank_account_number);
        }

        if($this->request->filled('vendor_bank_account_number')) {
            $query = $query->where('vendor_bank_account_number', $this->request->vendor_bank_account_number);
        }

        if($this->request->filled('client_id')) {
            $query = $query->where('client_id', $this->request->client_id);
        }

        if($this->request->filled('check_number')) {
            $query = $query->where('check_number', $this->request->check_number);
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
                'client' => $item->client ? $item->client->name : '---',

                'client_bank_account_number' => $item->client_bank_account_number,
                'client_bank_account_holder' => $item->client_bank_account_holder,
                'client_bank_name' => $item->client_bank_name,

                'customer_company' => $item->customer_company,
                'customer_bank_account_number' => $item->customer_bank_account_number,
                'customer_bank_account_holder' => $item->customer_bank_account_holder,
                'customer_bank_account_type' => $item->customer_bank_account_type,
                'customer_bank_name' => $item->customer_bank_name,

                'vendor_bank_account_number' => $item->vendor_bank_account_number,
                'vendor_bank_account_holder' => $item->vendor_bank_account_holder,
                'vendor_bank_name' => $item->vendor_bank_name,
                'vendor_company' => $item->vendor_company,
                'vendor_contact' => $item->vendor_contact,

                'check_number' => $item->check_number,
                'issue_date' => $item->issue_date ? Carbon::parse($item->issue_date)->format('m/d/Y h:i A') : '---',
                'clearing_date' => $item->clearing_date ? Carbon::parse($item->clearing_date)->format('m/d/Y h:i A') : '---',
                'reconciled_date' => $item->reconciled_date ? Carbon::parse($item->reconciled_date)->format('m/d/Y h:i A') : '---',
                'check_amount' => $item->check_amount,
                'bank_posting_profile' => $item->bank_posting_profile,
                'method_of_payment_customer' => $item->method_of_payment_customer,
                'voucher_no' => $item->voucher_no,
                'postdated_check_status' => $item->postdated_check_status,
                'approved_date' => $item->approved_date ? Carbon::parse($item->approved_date)->format('m/d/Y h:i A') : '---',
                'posting_date' => $item->posting_date ? Carbon::parse($item->posting_date)->format('m/d/Y h:i A') : '---',
                'canceled' => $item->canceled,

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
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $client_bank_accounts = ClientBankAccount::all();
        $customer_bank_accounts = CustomerBankAccount::all();
        $vendor_bank_accounts = VendorBankAccount::all();
        $bank_reasons = BankReason::all();
        $vouchers = VendorPaymentJournalVoucher::all();

        $vendor_payment_methods = VendorPaymentMethod::all();
        $customer_payment_methods =CustomerPaymentMethod::all();

        $vendor_payments = VendorPayment::all();
        $customer_payments = CustomerPayment::all();

        $vendor_invoices = VendorInvoice::all();
        $customer_invoices = CustomerInvoice::all();

        if($id) {
            $item = Check::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
            'client_bank_accounts' => $client_bank_accounts,
            'customer_bank_accounts' => $customer_bank_accounts,
            'vendor_bank_accounts' => $vendor_bank_accounts,
            'bank_reasons' => $bank_reasons,
            'vouchers' => $vouchers,
            'vendor_payment_methods' => $vendor_payment_methods,
            'customer_payment_methods' => $customer_payment_methods,
            'vendor_payments' => $vendor_payments,
            'customer_payments' => $customer_payments,
            'vendor_invoices' => $vendor_invoices,
            'customer_invoices' => $customer_invoices,
        ]);
    }

    protected function formatView($item)
    {
        $item->cancelUrl = $item->renderCancelUrl();
        $item->approveUrl = $item->renderApproveUrl();
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->approved_by = $item->renderApprovedUser();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');
        $item->approved_date = $item->approved_date ? Carbon::parse($item->approved_date)->format('m/d/Y h:i A') : '';

        $item->user_cancelled = $item->cancelled_by_user ? $item->cancelled_by_user->fullname : '';

        return $item;
    }
}
