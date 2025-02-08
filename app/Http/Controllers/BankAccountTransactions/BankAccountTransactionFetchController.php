<?php

namespace App\Http\Controllers\BankAccountTransactions;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Customers\CustomerBankAccount;
use App\Models\Vendors\VendorBankAccount;
use App\Models\AdminSetups\BankReason;
use App\Models\BankAccountTransactions\BankAccountTransaction;
use App\Models\Users\User;

use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;

use Carbon\Carbon;

class BankAccountTransactionFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new BankAccountTransaction;
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
                'client_bank_branch' => $item->client_bank_branch,

                'customer_company' => $item->customer_company,
                'customer_bank_account_number' => $item->customer_bank_account_number,
                'customer_bank_account_holder' => $item->customer_bank_account_holder,
                'customer_bank_account_type' => $item->customer_bank_account_type,
                'customer_bank_branch' => $item->customer_bank_branch,
                'payment_method_customer' => $item->customer_payment_method ? $item->customer_payment_method->method_of_payment : '---',

                'vendor_company' => $item->vendor_company,
                'vendor_bank_account_number' => $item->vendor_bank_account_number,
                'vendor_bank_account_holder' => $item->vendor_bank_account_holder,
                'vendor_bank_branch' => $item->vendor_bank_branch,
                'payment_method_vendor' => $item->vendor_payment_method ? $item->vendor_payment_method->method_of_payment : '---',

                'bank_statement_date' => $item->bank_statement_date ? Carbon::parse($item->bank_statement_date)->format('m/d/Y h:i A') : '---',
                'transaction_date' => $item->transaction_date ? Carbon::parse($item->transaction_date)->format('m/d/Y h:i A') : '---',
                'bank_posting_profile' => $item->bank_posting_profile,
                'deposit_slip_number' => $item->deposit_slip_number,
                'check_number' => $item->check_number,
                
                'accounting_date' => $item->accounting_date ? Carbon::parse($item->accounting_date)->format('m/d/Y h:i A') : '---',

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
        $customer_payment_methods = CustomerPaymentMethod::all();

        if($id) {
            $item = BankAccountTransaction::withTrashed()->findOrFail($id);
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
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->approved_by = $item->renderApprovedUser();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
