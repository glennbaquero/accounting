<?php

namespace App\Http\Controllers\Deposits;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\Users\User;
use App\Models\AdminSetups\BankReason;
use App\Models\Customers\Customer;
use App\Models\Deposits\Deposit;

use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\CustomerBankAccount;
use App\Models\Vendors\VendorBankAccount;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\PurchaseOrders\VendorPayment;
use App\Models\SalesOrders\CustomerPayment;

use Carbon\Carbon;

class DepositFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Deposit;
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
        if($this->request->filled('customer_account')) {
            $query = $query->where('customer_account', $this->request->customer_account);
        }

        if($this->request->filled('client_bank_account_number')) {
            $query = $query->where('client_bank_account_number', $this->request->client_bank_account_number);
        }

        if($this->request->filled('client_id')) {
            $query = $query->where('client_id', $this->request->client_id);
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
                'client_bank_account_type' => $item->client_bank_account_type,
                'client_bank_name' => $item->client_bank_name,
                'client_bank_branch' => $item->client_bank_branch,
                'client_bank_account_expiry' => $item->client_bank_account_expiry,

                'customer_company' => $item->customer_company,
                'customer_contact' => $item->customer_contact,
                'deposit_slip_number' => $item->deposit_slip_number,
                'deposit_amount' => $item->deposit_amount,
                'bank_posting_profile' => $item->bank_posting_profile,
                'payment_reference' => $item->payment_reference,
                'method_of_payment_customer' => $item->method_of_payment_customer,

                'voucher_no' => $item->voucher_no,
                'approved_date' => $item->approved_date ? Carbon::parse($item->approved_date)->format('m/d/Y h:i A') : '---',
                'posting_date' => $item->posting_date ? Carbon::parse($item->posting_date)->format('m/d/Y h:i A') : '---',
                'canceled_date' => $item->canceled_date ? Carbon::parse($item->canceled_date)->format('m/d/Y h:i A') : '---',

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
        $bank_reasons = BankReason::all();
        $customers = Customer::all();
        $vouchers = VendorPaymentJournalVoucher::all();

        $client_bank_accounts = ClientBankAccount::all();
        $customer_bank_accounts = CustomerBankAccount::all();
        $vendor_bank_accounts = VendorBankAccount::all();
        $vendor_payment_methods = VendorPaymentMethod::all();
        $customer_payment_methods =CustomerPaymentMethod::all();

        $vendor_payments = VendorPayment::all();
        $customer_payments = CustomerPayment::all();

        if ($id) {
            $item = Deposit::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'customers' => $customers,
            'cost_centers' => $cost_centers,
            'departments' => $departments,
            'expense_purposes' => $expense_purposes,
            'bank_reasons' => $bank_reasons,
            'vouchers' => $vouchers,
            'client_bank_accounts' => $client_bank_accounts,
            'customer_bank_accounts' => $customer_bank_accounts,
            'vendor_bank_accounts' => $vendor_bank_accounts,
            'vendor_payment_methods' => $vendor_payment_methods,
            'customer_payment_methods' => $customer_payment_methods,
            'vendor_payments' => $vendor_payments,
            'customer_payments' => $customer_payments,
        ]);
    }

    protected function formatView($item)
    {
        $item->canceled_by = $item->renderCanceledBy();
        $item->cancelUrl = $item->renderCancelUrl();
        $item->approveUrl = $item->renderApproveUrl();
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->approved_by = $item->renderApprovedBy();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');
        $item->approved_date = $item->approved_date ? Carbon::parse($item->approved_date) : '---';

        return $item;
    }
}
