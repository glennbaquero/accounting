<?php

namespace App\Http\Controllers\PurchaseOrders;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\AdminSetups\Client;
use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\Vendors\Vendor;
use App\Models\Users\User;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\JournalSetups\CostCenter;
use App\Models\PostingProfile\VendorPostingProfile;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Variant;
use App\Models\ProductInventories\Products\Specification;
use App\Models\SalesOrders\SalesOrder;
use App\Models\Services\Service;
use App\Models\Procurements\Procurement;
use App\Models\Charges\Charge;
use App\Models\Discounts\Discount;
use App\Models\PaymentSchedules\PaymentSchedule;

use App\Models\PromissoryNotes\PurchasePromissoryNote;
use Carbon\Carbon;

class PurchaseOrderFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new PurchaseOrder;
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
            $query = $query->where('client_id', $this->request->client);
        }

        if($this->request->filled('confirmed')) {
            $query = $query->whereNotNull('confirmed_by')->with('vendor_invoices')->doesnthave('vendor_invoices');
        }

        if($this->request->filled('invoiced')) {
            $query = $query->whereNotNull('confirmed_by')->with('vendor_invoices')->has('vendor_invoices');
        }

        if($this->request->filled('for_confirmation')) {
            $query = $query->whereNull('confirmed_by');
        }
        
        if($this->request->filled('vendor_account')) {
            $query = $query->where('vendor_account', $this->request->vendor_account);
        }

        return $query->withTrashed();
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
                'purchase_order_number' => $item->purchase_order_number,
                'vendor_account' => $item->vendor_account,
                'purchase_order_date' => Carbon::parse($item->purchase_order_date)->format('m/d/Y'),
                'delivery_date' => $item->delivery_date ? Carbon::parse($item->delivery_date)->format('m/d/Y') : '---',
                'due_date' => $item->due_date ? Carbon::parse($item->due_date)->format('m/d/Y') : '---',
                'approval_status_date' => Carbon::parse($item->approval_status_date)->format('m/d/Y'),
                'accounting_date' => $item->accounting_date ? Carbon::parse($item->accounting_date)->format('m/d/Y') : '---',
                'vendor_name' => $item->vendor_name,
                'total_amount' => $item->renderTotalAmount(),

                'invoice_account' => $item->invoice_account,
                'invoice_count' => $item->vendor_invoices->count(),
                'confirmed_date' => $item->confirmed_date ? Carbon::parse($item->confirmed_date)->format('m/d/Y') : '---',
                'approval_status' => $item->approval_status,
                'purchase_order_status' => $item->purchase_order_status,
                'purchase_type' => $item->purchase_type,
                'method_of_payment' => $item->payment_method ? $item->payment_method->method_of_payment : '---',
                'terms_of_payment' => $item->terms_of_payment_detail ? $item->terms_of_payment_detail->terms_of_payment : '---',
                'department' => $item->department_value ? $item->department_value->dimension_name : '',
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

        $payment_methods = VendorPaymentMethod::where('company_id', auth()->user()->company_id)->get();
        $terms_of_payments = TermsOfPayment::where('company_id', auth()->user()->company_id)->get();
        $vendors = Vendor::where('company_id', auth()->user()->company_id)->get();
        $products = Product::getData();
        $variants = Variant::getData();
        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $clients = User::getClients(1, 'App\Models\PurchaseOrders\PurchaseOrder');
        $payment_schedules = PaymentSchedule::getCompanyData();

        $financial_dimensions = FinancialDimensionValue::get();
        $sale_orders = SalesOrder::where('company_id', auth()->user()->company_id)->with('sales_order_lines')->get();
        $posting_profiles = VendorPostingProfile::get();

        $order_lines = [];
        
        if ($id) {
            $item = PurchaseOrder::withTrashed()->findOrFail($id);

            $item->letter_credit = $item->credits->first()?->amendment_number;
            $item->letter_credit_issue_date = Carbon::parse($item->credits->first()?->issue_date)->format('m-d-Y');
            $item->boe = PurchasePromissoryNote::where('company_id', auth()->user()->company_id)->first()?->bills_of_exchange;
            $item->boe_issue_date = Carbon::parse(PurchasePromissoryNote::where('company_id', auth()->user()->company_id)->first()?->issue_date)->format('m-d-Y');
            $item->guarantee = $item->guarantees->first()?->letter_of_guarantee_number;
            $item->guarantee_date = Carbon::parse($item->guarantees->first()?->issue_date)->format('m-d-Y');

            $item = $this->formatView($item);

            // $order_lines  = collect($item->purchase_order_lines)->map(function ($line) {
            //     return $line;            
            // });
            foreach ($item->purchase_order_lines as $key => $line) {
                array_push($order_lines, [
                    'amount' => $line->amount,
                    'approveUrl' => $line->renderApproveUrl(),
                    'approved_on' => $line->approved_on,
                    'batch_number' => $line->batch_number,
                    'charge_id' => $line->charge_id,
                    'charge_on_purchase' => $line->charge_on_purchase,
                    'charges_on_sales' => $line->charges_on_sales,
                    'client_id' => $line->client_id,
                    'company_id' => $line->company_id,
                    'confirmed_delivery_date' => $line->confirmed_delivery_date,
                    'cost_center_id' => $line->cost_center_id,
                    'created_at' => $line->created_at,
                    'created_by' => $line->created_by,
                    'customer_account' => $line->customer_account,
                    'customer_invoice_number' => $line->customer_invoice_number,
                    'deleted_at' => $line->deleted_at,
                    'delivery_date' => $line->delivery_date,
                    'delivery_type' => $line->delivery_type,
                    'department_id' => $line->department_id,
                    'discount' => $line->discount,
                    'discount_id' => $line->discount_id,
                    'discount_percentage' => $line->discount_percentage,
                    'existing_data' => $line->existing_data,
                    'expense_purpose_id' => $line->expense_purpose_id,
                    'finalized_checkbox' => $line->finalized_checkbox,
                    'id' => $line->id,
                    'invoice_account' => $line->invoice_account,
                    'item_number' => $line->item_number,
                    'item_sales_tax_group' => $line->item_sales_tax_group,
                    'ledger_account' => $line->ledger_account,
                    'line_number' => $line->line_number,
                    'line_status' => $line->line_status,
                    'matching_policy' => $line->matching_policy,
                    'number_of_hours' => $line->number_of_hours,
                    'overdelivery' => $line->overdelivery,
                    'procurement_id' => $line->procurement_id,
                    'product' => $line->product_relation,
                    'product_id' => $line->product_id,
                    'product_name' => $line->product_name,
                    'quantity' => $line->quantity,
                    'receive_now_quantity' => $line->receive_now_quantity,
                    'rejectUrl' => $line->renderRejectUrl(),
                    'rejected_on' => $line->rejected_on,
                    'removeUrl' => $line->removeUrl(),
                    'return_action' => $line->return_action,
                    'rpm_method' => $line->rpm_method,
                    'sales_category' => $line->sales_category,
                    'sales_order_line_number' => $line->sales_order_line_number,
                    'sales_order_number' => $line->sales_order_number,
                    'sales_tax_group' => $line->sales_tax_group,
                    'sales_unit' => $line->sales_unit,
                    'serial_number' => $line->serial_number,
                    'service_id' => $line->service_id,
                    'service_task' => $line->service_task,
                    'service_task_details' => $line->service_task_details,
                    'specification_id' => $line->specification_id,
                    'stopped_checkbox' => $line->stopped_checkbox,
                    'subledger_journal' => $line->subledger_journal,
                    'underdelivery' => $line->underdelivery,
                    'unit_price' => $line->unit_price,
                    'updated_at' => $line->updated_at,
                    'updated_by' => $line->updated_by,
                    'variant' => $line->variant_relation,
                    'variant_id' => $line->variant_id,
                    'variant_name' => $line->variant_name,
                    'variant_number' => $line->variant_number,

                ]);
            }
        }

        return response()->json([
            'item' => $item,
            'payment_methods' => $payment_methods,
            'terms_of_payments' => $terms_of_payments,
            'vendors' => $vendors,
            'products' => $products,
            'variants' => $variants,
            'cost_centers' => $cost_centers,
            'expense_purposes' => $expense_purposes,
            'departments' => $departments,
            'purchase_order_lines' => $order_lines,
            'financial_dimensions' => $financial_dimensions,
            'clients' => $clients,
            'posting_profiles' => $posting_profiles,
            'sale_orders' => $sale_orders,
            'specifications' => Specification::where('company_id', auth()->user()->company_id)->get(),
            'services' => Service::where('company_id', auth()->user()->company_id)->with('serviceTasks')->get(),
            'procurements' => Procurement::where('company_id', auth()->user()->company_id)->get(),
            'charges_on_lines' => Charge::where('status', 'Enabled')->where('level', 'Line')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'charges_on_header' => Charge::where('status', 'Enabled')->where('level', 'Main')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'discount_on_lines' => Discount::where('status', 'Enabled')->where('level', 'Line')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'discount_on_header' => Discount::where('status', 'Enabled')->where('level', 'Main')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'payment_schedules' => $payment_schedules,
        ]);
    }
    
    protected function formatView($item)
    {
        $item->confirmed_user = $item->confirmed_by_user ? $item->confirmed_by_user->fullname : '---';
        $item->is_already_confirmed = $item->confirmed_by ? true : false;
        $item->created_by = $item->created_by_user;
        $item->updated_by = $item->updated_by_user;
        $item->confirm_by = $item->confirmed_by_user;
        $item->client = $item->client;
        $item->formatted_created_at = $item->renderDate('created_at');
        $item->formatted_updated_at = $item->renderDate('updated_at');
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->confirmationUrl = $item->renderConfirmationUrl();
        $item->vendorInvoiceUrl = $item->renderVendorInvoiceUrl();
        $item->has_invoice = $item->vendor_invoice ? true : false;

        return $item;
    }
}
