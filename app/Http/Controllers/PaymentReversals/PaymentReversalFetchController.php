<?php

namespace App\Http\Controllers\PaymentReversals;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\PaymentReversals\PaymentReversal;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Vendors\VendorBankAccount;
use App\Models\Customers\CustomerBankAccount;
use App\Models\BankAccountStatements\BankAccountStatement;
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

use Carbon\Carbon;

class PaymentReversalFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new PaymentReversal;
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
                'payment_reversal_id' => $item->payment_reversal_id,
                'reversed_date' => $item->reversed_date,
                'reason' => $item->reason,
                'status' => $item->status,
                'approved_checkbox' => $item->approved_checkbox,
                'approved_by' => $item->approved_by,
                'approved_date' => $item->approved_date,
                'posted_checkbox' => $item->posted_checkbox,
                'posted_by' => $item->posted_by,
                'posted_date' => $item->posted_date,
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
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        $client_banks = ClientBankAccount::all();
        $vendor_banks = VendorBankAccount::all();
        $customer_banks = CustomerBankAccount::all();
        $bank_statements = BankAccountStatement::all();
        $cash_registers = CashflowTransaction::all();
        $bank_reconciliations = BankReconciliation::all();
        $checks = Check::all();
        $deposits = Deposit::all();
        $vendor_payments = VendorPayment::all();
        $customer_payments = CustomerPayment::all();

        $vendor_payment_methods = VendorPaymentMethod::all();
        $customer_payment_methods = CustomerPaymentMethod::all();

        $bank_postings = BankPosting::all();
        $bank_reasons = BankReason::all();

        if ($id) {
            $item = PaymentReversal::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'client_banks' => $client_banks,
            'vendor_banks' => $vendor_banks,
            'customer_banks' => $customer_banks,
            'bank_statements' => $bank_statements,
            'cash_registers' => $cash_registers,
            'bank_reconciliations' => $bank_reconciliations,
            'checks' => $checks,
            'deposits' => $deposits,
            'vendor_payments' => $vendor_payments,
            'customer_payments' => $customer_payments,
            'vendor_payment_methods' => $vendor_payment_methods,
            'customer_payment_methods' => $customer_payment_methods,
            'bank_postings' => $bank_postings,
            'bank_reasons' => $bank_reasons,
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
