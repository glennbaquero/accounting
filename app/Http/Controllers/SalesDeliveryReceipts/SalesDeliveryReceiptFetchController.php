<?php

namespace App\Http\Controllers\SalesDeliveryReceipts;

use Carbon\Carbon;

use App\Models\Users\User;
use App\Models\Customers\Customer;
use App\Models\SalesOrders\SalesOrder;
use App\Models\SalesOrders\SalesOrderReturn;
use App\Models\Invoices\SalesDeliveryReceipt;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPaymentCustomer;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Specification;
use App\Models\ProductInventories\Products\Variant;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\PostingProfile\CustomerPostingProfile;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\Services\Service;
use App\Models\Procurements\Procurement;
use App\Models\Charges\Charge;
use App\Models\Discounts\Discount;
use App\Models\PaymentSchedules\PaymentSchedule;

use App\Extenders\Controllers\FetchController as Controller;

class SalesDeliveryReceiptFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new SalesDeliveryReceipt;
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
            $query = $query->whereNotNull('approved_date')->whereNull('posted_by');
        }

        if($this->request->filled('for_approval')) {
            $query = $query->whereNull('approved_date');
        }

        if($this->request->filled('posted')) {
            $query = $query->whereNotNull('posted_by');
        }

        if($this->request->filled('so_number')) {
            $query = $query->where('sales_order_number', $this->request->so_number);
        }

        if($this->request->filled('customer_account')) {
            $query = $query->where('customer_account', $this->request->customer_account);
        }

        if($this->request->filled('customer_invoice_id')) {
            $query = $query->where('customer_invoice_id', $this->request->customer_invoice_id);
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
                'sales_delivery_receipt_number' => $item->sales_delivery_receipt_number,
                'invoice_status' => $item->invoice_status,
                // 'payment_due_date' => Carbon::parse($item->payment_due_date)->format('M d, Y'),
                // 'invoice_payment_release_date' => Carbon::parse($item->invoice_payment_release_date)->format('M d, Y'),

                'customer_account' => $item->customer_account,
                'invoice_account' => $item->invoice_account,
                'payment_id' => $item->payment_id,
                'customer_name' => $item->customer_name,
                'invoice_date' => Carbon::parse($item->invoice_date)->format('M d, Y'),
                'invoice_status' => $item->invoice_status,
                'invoice_onhold_checkbox' => $item->invoice_onhold_checkbox,
                'posting_date' => $item->posting_date ? Carbon::parse($item->posting_date)->format('M d, Y') : '---'    ,
                'approved_date' => $item->approved_date ? Carbon::parse($item->approved_date)->format('M d, Y') : '---',
                'payment_due_date' => Carbon::parse($item->payment_due_date)->format('M d, Y'),
                'invoice_payment_received_date' => Carbon::parse($item->invoice_payment_release_date)->format('M d, Y'),
                // 'method_of_payment' => PaymentMethod::find($item->method_of_payment) ? PaymentMethod::find($item->method_of_payment)->name : '---',
                'method_of_payment' => $item->payment_method ? $item->payment_method->method_of_payment : '',
                'terms_of_payment' => $item->terms_of_payment,
                'bank_account' => $item->bank_account,
                'total_amount' => number_format($item->total_amount, 2, '.', ','),
                'department' => $item->department ? (FinancialDimensionValue::where('financial_dimension_value_code', $item->department)->first() ? FinancialDimensionValue::where('financial_dimension_value_code', $item->department)->first()->dimension_name : '---') : '---',
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

    public function fetchView($sales_order_number = null, $id = null)
    {
        $item = null;
        // $payment_methods = PaymentMethod::get();
        $payment_methods = CustomerPaymentMethod::get();
        $terms_of_payments = TermsOfPaymentCustomer::get();
        $users = User::get();
        $customers = Customer::get();
        $products = Product::getData();
        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $posting_profiles = CustomerPostingProfile::get();

        $sales_order = [];
        $order_lines = [];
        if ($sales_order_number) {
            $sales_order = SalesOrder::where('sales_order_number', $sales_order_number)->with('sales_order_lines')->first();
            if ($sales_order) {
                $order_lines = $sales_order->sales_order_lines;
            }
        }

        if ($id) {
            $item = SalesDeliveryReceipt::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
            $item['approver'] = $item->approved_by_user ? $item->approved_by_user->fullname : '---';
            $item['poster'] = $item->posted_by_user ? $item->posted_by_user->fullname : '---';
            $item['creator'] = $item->created_by_user ? $item->created_by_user->fullname : '---';
            $item['updater'] = $item->updated_by_user ? $item->updated_by_user->fullname : '---';
            $item['created_date'] = Carbon::parse($item->created_at)->format('m/d/Y h:i:s A');
            $item['updated_date'] = Carbon::parse($item->updated_at)->format('m/d/Y h:i:s A');
            $sales_order = $item->sales_order;
            $order_lines = [];
            // $order_lines  = collect($item->customer_invoice_lines)->map(function ($line) {
            //     $line->product = $line->product;
            //     return $line;            
            // });
            // 
            foreach ($item->customer_invoice_lines as $key => $line) {
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
                    'removeUrl' => $line->removeUrl(),
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

        if ($sales_order) {
            $sales_order['payment_method'] = $sales_order->payment_method;
        }

        $sales_orders = SalesOrder::with('sales_order_lines')
                            ->where('company_id', auth()->user()->company_id)
                            ->with('client')->get();
        $sales_order_returns = SalesOrderReturn::with('sales_order_return_lines')
                            ->where('company_id', auth()->user()->company_id)
                            ->with('client')->get();

        $payment_schedules = PaymentSchedule::getCompanyData();

        return response()->json([
            'item' => $item,
            'sales_order' => (object) $sales_order,
            'sales_orders' => $sales_orders,
            'sales_order_returns' => $sales_order_returns,
            'payment_methods' => $payment_methods,
            'users' => $users,
            'terms_of_payments' => $terms_of_payments,
            'customers' => $customers,
            'products' => $products,
            'customers' => Customer::get(),
            'variants' => Variant::getData(),
            'customer_invoice_lines' => $order_lines,
            'cost_centers' => $cost_centers,
            'expense_purposes' => $expense_purposes,
            'departments' => $departments,
            'posting_profiles' => $posting_profiles,
            'specifications' => Specification::get(),
            'clients' => User::getClients(5, 'App\Models\Invoices\SalesDeliveryReceipt'),
            'services' => Service::where('company_id', auth()->user()->company_id)->with('serviceTasks')->get(),
            'procurements' => Procurement::where('company_id', auth()->user()->company_id)->get(),
            'charges_on_lines' => Charge::where('level', 'Line')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'charges_on_header' => Charge::where('level', 'Main')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'discount_on_lines' => Discount::where('level', 'Line')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'discount_on_header' => Discount::where('level', 'Main')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'payment_schedules' => $payment_schedules,
        ]);
    }

    protected function formatView($item)
    {
        $item->client = $item->client;
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->confirmUrl = $item->renderConfirmUrl();
        $item->salesOrderShowUrl =  $item->sales_order ? $item->sales_order->renderShowUrl() : '';
        $item->postSalesDeliveryReceiptUrl = $item->renderPostSalesDeliveryReceiptUrl();
        return $item;
    }
}
