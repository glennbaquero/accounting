<?php

namespace App\Http\Controllers\VendorPayments;

use App\Models\Vendors\Vendor;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Variant;
use App\Models\Invoices\VendorInvoice;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\PurchaseOrders\VendorPayment;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Extenders\Controllers\FetchController as Controller;
use App\Models\PurchaseOrders\VendorPaymentLine;
use App\Models\Users\User;
use App\Models\PostingProfile\VendorPostingProfile;
use App\Models\PostingProfile\VendorPostingProfileHeader;
use App\Models\Services\Service;
use App\Models\Procurements\Procurement;
use App\Models\ProductInventories\Products\Specification;

use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Vendors\VendorBankAccount;
use App\Models\Checks\Check;
use App\Models\Deposits\Deposit;
use App\Models\BankAccountStatements\BankAccountStatement;
use App\Models\BankPostings\BankPosting;
use App\Models\AdminSetups\BankReason;
use App\Models\BankReconciliations\BankReconciliation;

use App\Models\Charges\Charge;


class VendorPaymentFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new VendorPayment;
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
        
        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client['id']);
        }

        if($this->request->filled('pending')) {
            $query = $query->where('approved_payment', false)->where('posted_payment', false);
        }

        if($this->request->filled('approved_payment')) {
            $query = $query->where('approved_payment', true)->where('posted_payment', false);
        }
        
        if($this->request->filled('posted_payment')) {
            $query = $query->where('approved_payment', true)->where('posted_payment', true);
        }

        if($this->request->filled('invoice_id')) {
            $query = $query->where('vendor_invoice_id', $this->request->invoice_id);
        }

        if($this->request->filled('po_number')) {
            $po_number = $this->request->po_number;
            $query = $query->whereHas('vendor_invoice', function($q) use ($po_number) {
                $q->where('purchase_order_number', $po_number);
            });
        }

        if($this->request->filled('vendor_account')) {
            $query = $query->where('vendor_account', $this->request->vendor_account);
        }

        if($this->request->filled('bank_account')) {
            $query = $query->where('bank_account', $this->request->bank_account);
        }

        if($this->request->filled('check_number')) {
            $query = $query->where('check_number', $this->request->check_number);
        }

        return $query->withTrashed();
    }

    /**
     * Custom formatting of data
     * 
     * @param VendorPayment[]
     * @return array $result
     */
    public function formatData($items) {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'client' => $item->client ? $item->client->name : '---',
                'vendor_payment_number' => $item->vendor_payment_number,
                'vendor_invoice_id' => $item->vendor_invoice_id,
                'vendor_invoice_number' => $item->vendor_invoice ? $item->vendor_invoice->vendor_invoice_number : null,
                'issue_date' => $item->issue_date,
                'payment_release_date' => $item->payment_release_date,
                'clearing_date' => $item->clearing_date,
                'due_date' => $item->due_date,
                'invoice_date' => $item->invoice_date,
                'payee' => $item->payee ? $item->payee : '---',
                'description' => $item->description,
                'payment_status' => $item->payment_status,
                'approved_payment' => $item->approved_payment,
                'approved_date' => $item->approved_date,
                'approved_by' => $item->approved_by,
                'posted_payment' => $item->posted_payment,
                'posting_date' => $item->posting_date,
                'posted_by' => $item->posted_by,
                'sales_tax_group' => $item->sales_tax_group,
                'tax_exempt_group' => $item->tax_exempt_group,
                'prices_included_sales_tax' => $item->prices_included_sales_tax,
                'ignore_calculated_tax' => $item->ignore_calculated_tax,
                'cash_discount_code' => $item->cash_discount_code,
                'cash_discount_percentage' => $item->cash_discount_percentage,
                'charges_group' => $item->charges_group,
                'vendor_account_id' => $item->vendor_account_id,
                'vendor_account' => $item->vendor_account,
                'invoice_account' => $item->invoice_account,
                'vendor_name' => $item->vendor_name,
                'vendor_address' => $item->vendor_address,
                'vendor_contact_id' => $item->vendor_contact_id,
                'dimension_value_cost_center_id' => $item->dimension_value_cost_center_id,
                'dimension_value_cost_center_name' => $item->cost_center ? $item->cost_center->dimension_name : null,
                'dimension_value_department_id' => $item->dimension_value_department_id,
                'dimension_value_department_name' => $item->department ? $item->department->dimension_name : null,
                'dimension_value_expense_purpose_id' => $item->dimension_value_expense_purpose_id,
                'dimension_value_expense_purpose_name' => $item->expense_purpose ? $item->expense_purpose->dimension_name : null,
                'posting_profile' => $item->posting_profile,
                'accounting_distribution' => $item->accounting_distribution,
                'created_at' => $item->renderDate(),
                'created_by' => $item->created_by,
                'created_by_name' => $item->created_by_user->renderName(),
                'updated_by' => $item->updated_by,
                'updated_at' => $item->updated_at,
                'settlement_type' => $item->settlement_type,
                'method_of_payment_id' => $item->method_of_payment_id,
                'method_of_payment_name' => $item->method_of_payment ? $item->method_of_payment->name : null,
                'payment_specification' => $item->payment_specification,
                'payment_reference' => $item->payment_reference,
                'bank_transaction_type' => $item->bank_transaction_type,
                'bank_account' => $item->bank_account,
                'total_quantity' => $item->total_quantity,
                'total_discount' => $item->total_discount,
                'total_cash_discount' => $item->total_cash_discount,
                'total_charges' => $item->total_charges,
                'total_sales_tax' => $item->total_sales_tax,
                'total_round_off' => $item->total_round_off,
                'sub_total_amount' => $item->sub_total_amount,
                'total_amount' => $item->total_amount,
            ]);

            array_push($result, $data);
        }

        return $result;
    }

    /**
     * Build array data
     * @param VendorPayment
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

    public function fetchView($id = null) {
        $item = null;
        $vendor_invoices =  VendorInvoice::with('vendor_invoice_lines')
        ->where('company_id', auth()->user()->company_id)
        ->whereNotNull('approved_by')
        ->orderBy('id', 'DESC')
        ->with('client')->get();
        
        if ($id) {
            $item = VendorPayment::withTrashed()->findOrFail($id);


            $item->letter_credit = $item->vendor_invoice->purchase_order->credits->first()->amendment_number;
            $item->letter_credit_issue_date = Carbon::parse($item->vendor_invoice->purchase_order->credits->first()->issue_date)->format('m-d-Y');
            $item->boe = PurchasePromissoryNote::where('company_id', auth()->user()->company_id)->first()->bills_of_exchange;
            $item->boe_issue_date = Carbon::parse(PurchasePromissoryNote::where('company_id', auth()->user()->company_id)->first()->issue_date)->format('m-d-Y');
            $item->guarantee = $item->vendor_invoice->purchase_order->guarantees->first()->letter_of_guarantee_number;
            $item->guarantee_date = Carbon::parse($item->vendor_invoice->purchase_order->guarantees->first()->issue_date)->format('m-d-Y');

            $item = $this->formatView($item);

            $item['created_by_name'] = $item->created_by_user->renderName();
        
            if ($item->updated_by) {
                $item['updated_by_name'] = $item->updated_by_user->renderName();
            }
            $item['itemLines'] = VendorPaymentLine::where('vendor_payment_id', $id)
            ->where('is_rejected', 0)
            ->with(['created_by_user' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }])
            ->with(['updated_by_user' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }])
            ->get();
        }

        return response()->json([
            'item' => $item,
            'vendors' => Vendor::get(),
            'items' => Product::getData(),
            'variants' => Variant::getData(),
            'vendor_invoices' => $vendor_invoices,
            'method_of_payments' => VendorPaymentMethod::get(),
            'cost_centers' => FinancialDimension::renderFinancialDimensionValues('Cost centers'),
            'expense_purposes' => FinancialDimension::renderFinancialDimensionValues('Expense purposes'),
            'departments' => FinancialDimension::renderFinancialDimensionValues('Departments'),
            'clients' => User::getClients(3, 'App\Models\PurchaseOrders\VendorPayment'),
            'posting_profiles' => VendorPostingProfileHeader::getCompanyData(),
            'client_banks' => ClientBankAccount::all(),
            'vendor_banks' => VendorBankAccount::all(),
            'checks' => Check::all(),
            'deposits' => Deposit::all(),
            'bank_statements' => BankAccountStatement::all(),
            'bank_postings' => BankPosting::all(),
            'bank_reasons' => BankReason::all(),
            'bank_reconciliations' => BankReconciliation::all(),
            'services' => Service::where('company_id', auth()->user()->company_id)->with('serviceTasks')->get(),
            'procurements' => Procurement::where('company_id', auth()->user()->company_id)->get(),
            'specifications' => Specification::where('company_id', auth()->user()->company_id)->get(),
            'charges_on_lines' => Charge::where('level', 'Line')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'charges_on_header' => Charge::where('level', 'Main')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
        ]);
    }

    protected function formatView($item) {
        $item->client = $item->client;
        $item->generatePurchaseDeliveryReceiptUrl = route('purchase-delivery-receipts.create', $item->id);
        $item->generatePaymentScheduleUrl = route('payment-schedules.create-pn', $item->vendor_payment_number);
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->approvalUrl = $item->renderApprovalUrl();
        $item->postUrl = $item->renderPostUrl();
        $item->cancelUrl = $item->renderCancelUrl();

        return $item;
    }
    
}
