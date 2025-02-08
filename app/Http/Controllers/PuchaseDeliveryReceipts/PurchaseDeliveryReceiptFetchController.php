<?php

namespace App\Http\Controllers\PurchaseDeliveryReceipts;

use Carbon\Carbon;

use App\Models\Users\User;
use App\Models\Vendors\Vendor;
use App\Models\Invoices\PurchaseDeliveryReceipt;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\PurchaseOrders\PurchaseOrderReturn;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Specification;
use App\Models\ProductInventories\Products\Variant;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\PostingProfile\VendorPostingProfile;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\Services\Service;
use App\Models\Procurements\Procurement;
use App\Models\Charges\Charge;
use App\Models\Discounts\Discount;
use App\Models\PaymentSchedules\PaymentSchedule;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\PostingProfile\VendorPostingProfileHeader;

class PurchaseDeliveryReceiptFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new PurchaseDeliveryReceipt;
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

        if($this->request->filled('approved')) {
            $query = $query->whereNotNull('approved_by')->whereNull('posted_by');
        }

        if($this->request->filled('for_approval')) {
            $query = $query->whereNull('approved_by')->whereNull('posted_by');
        }

        if($this->request->filled('posted')) {
            $query = $query->whereNotNull('posted_by');
        }

        if($this->request->filled('po_number')) {
            $query = $query->where('purchase_order_number', $this->request->po_number);
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
                'purchase_delivery_receipt_number' => $item->purchase_delivery_receipt_number,
                'invoice_status' => $item->invoice_status,
                'purchase_delivery_receipt_number' => $item->purchase_delivery_receipt_number,
                'purchase_order_number' => $item->purchase_order_number,
                'vendor_account' => $item->vendor_account,
                'invoice_account' => $item->invoice_account,
                'payment_id' => $item->payment_id,
                'vendor_name' => $item->vendor_name,
                'invoice_date' => Carbon::parse($item->invoice_date)->format('m/d/Y'),
                'invoice_status' => $item->invoice_status,
                'invoice_onhold_checkbox' => $item->invoice_onhold_checkbox,
                'posting_date' => $item->posting_date ? Carbon::parse($item->posting_date)->format('m/d/Y') : '---'    ,
                'approved_date' => $item->approved_date ? Carbon::parse($item->approved_date)->format('m/d/Y') : '---',
                'payment_due_date' => Carbon::parse($item->payment_due_date)->format('m/d/Y'),
                'invoice_payment_received_date' => Carbon::parse($item->invoice_payment_release_date)->format('m/d/Y'),
                'method_of_payment' => $item->payment_method ? $item->payment_method->method_of_payment : '---',
                'terms_of_payment' => $item->terms_of_payment,
                'bank_account' => $item->bank_account,
                'total_amount' => $item->renderTotalAmount(),
                'department' => $item->department ? $item->department->dimension_name : '---',
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

    public function fetchView($purchase_order_number = null, $id = null)
    {
        $item = null;

        $payment_methods = VendorPaymentMethod::getCompanyData();
        $terms_of_payments = TermsOfPayment::getCompanyData();
        $vendors = Vendor::getCompanyData();
        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $posting_profiles = VendorPostingProfileHeader::getCompanyData();
        $procurements = Procurement::getCompanyData();
        $services = Service::with('serviceTasks')->where('company_id', auth()->user()->company_id)->get();
        
        $purchase_order = [];
        $order_lines = [];
        if ($purchase_order_number) {
            $purchase_order = PurchaseOrder::where('purchase_order_number', $purchase_order_number)->first();
            
            // if ($purchase_order) {
            //     $order_lines = $purchase_order->purchase_order_lines;
            // }
        }

        if ($id) {
            $item = PurchaseDeliveryReceipt::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
            $purchase_order = $item->purchase_order;

            $vouchers = collect($item->vouchers)->map(function($voucher) {
                return $voucher->invoice_voucher_number;
            });

            // $order_lines  = collect($item->vendor_invoice_lines)->map(function ($line) use ($vouchers) {
            //     $line->product = $line->product;
            //     $line->vouchers = $vouchers ?? [];
            //     return $line;            
            // });
            foreach ($item->vendor_invoice_lines as $key => $line) {
                array_push($order_lines, [
                    'amount' => $line->amount,
                    'approveUrl' => $line->renderApproveUrl(),
                    'approved_on' => $line->approved_on,
                    'batch_number' => $line->batch_number, 
                    'charge_id' => $line->charge_id,
                    'charge_on_purchase' => $line->charge_on_purchase,
                    'charges_on_sales' => $line->charges_on_sales,
                    'client_id' => $line->client_id,
                    'close_for_receipt_checkbox' => $line->close_for_receipt_checkbox,
                    'color' => $line->color,
                    'company_id' => $line->company_id,
                    'cost_center_id' => $line->cost_center_id,
                    'created_at' => $line->created_at,
                    'created_by' => $line->created_by,
                    'created_by_user' => $line->created_by_user,
                    'creator' => $line->created_by_user->fullname,
                    'customer_account' => $line->customer_account,
                    'customer_invoice_line_number' => $line->customer_invoice_line_number,
                    'customer_invoice_number' => $line->customer_invoice_number,
                    'customer_name' => $line->customer_name,
                    'deleted_at' => $line->deleted_at,
                    'deliver_remainder' => $line->deliver_remainder,
                    'delivery_date' => $line->delivery_date,
                    'department_id' => $line->department_id,
                    'description' => $line->description,
                    'discount' => $line->discount,
                    'discount_id' => $line->discount_id,
                    'discount_percentage' => $line->discount_percentage,
                    'expense_purpose_id' => $line->expense_purpose_id,
                    'formatted_created_date' => $line->formatted_created_date,
                    'formatted_updated_date' => $line->formatted_updated_date,
                    'id' => $line->id,
                    'invoice_account' => $line->invoice_account,
                    'invoice_line_status' => $line->invoice_line_status,
                    'invoice_quantity_inventory_unit' => $line->invoice_quantity_inventory_unit,
                    'invoice_quantity_sales_unit' => $line->invoice_quantity_sales_unit,
                    'is_approved' => $line->is_approved,
                    'item_name' => $line->item_name,
                    'item_number' => $line->item_number,
                    'item_sales_tax_group' => $line->item_sales_tax_group,
                    'ledger_account' => $line->ledger_account,
                    'line_number' => $line->line_number,
                    'line_status' => $line->line_status,
                    'number_of_hours' => $line->number_of_hours,
                    'posted_by' => $line->posted_by,
                    'posted_invoice_checkbox' => $line->posted_invoice_checkbox,
                    'posting_date' => $line->posting_date,
                    'price_match' => $line->price_match,
                    'price_per_unit' => $line->price_per_unit,
                    'price_total_match' => $line->price_total_match,
                    'procurement_id' => $line->procurement_id,
                    'product' => $line->product_relation,
                    'product_id' => $line->product_id,
                    'quantity' => $line->quantity,
                    'receive_now_quantity' => $line->receive_now_quantity,
                    'rejectUrl' => $line->renderRejectUrl(),
                    'rejected_on' => $line->rejected_on,
                    'removeUrl' => $line->renderArchiveUrl(),
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
                    'set_unit_price' => $line->set_unit_price,
                    'size' => $line->size,
                    'specification_id' => $line->specification_id,
                    'subledger_journal' => $line->subledger_journal,
                    'unit_price' => $line->unit_price,
                    'updated_at' => $line->updated_at,
                    'updated_by' => $line->updated_by,
                    'updater' => $line->updater,
                    'variant' => $line->variant_relation,
                    'variant_id' => $line->variant_id,
                    'variant_name' => $line->variant_name,
                    'variant_number' => $line->variant_number,

                ]);
            }
        }

        if ($purchase_order) {
            $purchase_order['payment_method'] = $purchase_order->payment_method;
        }

        $purchase_orders = PurchaseOrder::with('purchase_order_lines')
            ->where('company_id', auth()->user()->company_id)
            ->whereNotNull('confirmed_by')
            ->orderBy('id', 'DESC')
            ->with('client')->get();

        $purchase_order_returns = PurchaseOrderReturn::with('purchase_order_return_lines')
            ->where('company_id', auth()->user()->company_id)
            ->whereNotNull('confirmed_by')
            ->orderBy('id', 'DESC')
            ->with('client')->get();

        $payment_schedules = PaymentSchedule::getCompanyData();

        return response()->json([
            'item' => $item,
            'purchase_order' => (object) $purchase_order,
            'purchase_orders' => $purchase_orders,
            'purchase_order_returns' => $purchase_order_returns,
            'payment_methods' => $payment_methods,
            'terms_of_payments' => $terms_of_payments,
            'vendors' => $vendors,
            'products' => Product::getData(),
            'variants' => Variant::getData(),
            'vendor_invoice_lines' => $order_lines,
            'cost_centers' => $cost_centers,
            'expense_purposes' => $expense_purposes,
            'departments' => $departments,
            'specifications' => Specification::get(),
            'clients' => User::getClients(2 ,'App\Models\Invoices\PurchaseDeliveryReceipt'),
            'posting_profiles' => $posting_profiles,
            'services' => Service::where('company_id', auth()->user()->company_id)->with('serviceTasks')->get(),
            'procurements' => Procurement::where('company_id', auth()->user()->company_id)->get(),
            'discount_on_lines' => Discount::where('company_id', auth()->user()->company_id)->where('level', 'Line')->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'discount_on_header' => Discount::where('company_id', auth()->user()->company_id)->where('level', 'Main')->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'charges_on_lines' => Charge::where('level', 'Line')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'charges_on_header' => Charge::where('level', 'Main')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'payment_schedules' => $payment_schedules,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->confirmUrl = $item->renderApprovalUrl();
        $item->cancelUrl = $item->renderCancelUrl();
        $item->postUrl = $item->renderPostUrl();
        $item->client = $item->client;
        $item->purchaseOrderShowUrl = $item->purchase_order ? $item->purchase_order->renderShowUrl() : '';
        $item->approver = $item->approved_by_user ? $item->approved_by_user->fullname : '---';
        $item->cancelled_user_name = $item->cancelled_by_user ? $item->cancelled_by_user->fullname : '---';
        $item->poster = $item->posted_by_user ? $item->posted_by_user->fullname : '---';
        $item->creator = $item->created_by_user ? $item->created_by_user->fullname : '---';
        $item->updater = $item->updated_by_user ? $item->updated_by_user->fullname : '---';
        $item->invoiced_by_user = $item->invoiced_by_user ? $item->invoiced_by_user->fullname : '---';
        $item->created_date = Carbon::parse($item->created_at)->format('m/d/Y h:i:s A');
        $item->updated_date = Carbon::parse($item->updated_at)->format('m/d/Y h:i:s A');
        $item->invoiceApprovalJournalUrl = $item->renderInvoiceApprovalJournalUrl();
        return $item;
    }
}
