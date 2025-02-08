<?php

namespace App\Http\Controllers\SalesOrderReturns;

use Carbon\Carbon;

use App\Models\SalesOrders\SalesOrderReturn;
use App\Models\Users\User;
use App\Models\Customers\Customer;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Specification;
use App\Models\ProductInventories\Products\Variant;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\PostingProfile\CustomerPostingProfile;
use App\Models\Services\Service;
use App\Models\Procurements\Procurement;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\Charges\Charge;
use App\Models\Discounts\Discount;

use App\Extenders\Controllers\FetchController as Controller;

class SalesOrderReturnFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new SalesOrderReturn;
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

        if($this->request->filled('confirmed')) {
            $query = $query->whereNotNull('confirmed_by')->whereNull('customer_invoice_number');
        }

        if($this->request->filled('for_confirmation')) {
            $query = $query->whereNull('confirmed_by');
        }

        if($this->request->filled('invoiced')) {
            $query = $query->whereNotNull('customer_invoice_number');
        }

        if($this->request->filled('customer_account')) {
            $query = $query->where('customer_account', $this->request->customer_account);
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
                'sales_order_return_number' => $item->sales_order_return_number,
                'customer_account' => $item->customer_account,
                'sales_order_date' => Carbon::parse($item->sales_order_date)->format('M d, Y'),
                'delivery_date' => $item->delivery_date ? Carbon::parse($item->delivery_date)->format('M d, Y') : '---',
                'due_date' => $item->due_date ? Carbon::parse($item->due_date)->format('M d, Y') : '---',
                'approval_status_date' => Carbon::parse($item->approval_status_date)->format('M d, Y'),
                'accounting_date' => $item->accounting_date ? Carbon::parse($item->accounting_date)->format('M d, Y') : '---',
                'customer_name' => $item->customer_name,
                'total_amount' => $item->sales_order_return_lines->sum('amount'),

                'invoice_account' => $item->invoice_account,
                'customer_invoice_number' => $item->customer_invoice_number ?? '---',
                'invoice_count' => $item->customer_invoices->count(),
                'confirmed_date' => $item->approval_status_date ? Carbon::parse($item->approval_status_date)->format('M d, Y') : '---',
                'approval_status' => $item->approval_status,
                'sales_order_status' => $item->sales_order_status,
                'sales_type' => $item->sales_type,
                'method_of_payment' => $item->payment_method ? $item->payment_method->name : '---',
                'terms_of_payment' => $item->terms_of_payment_detail ? $item->terms_of_payment_detail->terms_of_payment : '---',
                'department' => $item->department_value ? $item->department_value->dimension_name : '---',
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

        $payment_methods = PaymentMethod::where('company_id', auth()->user()->company_id)->get();
        $terms_of_payments = TermsOfPayment::where('company_id', auth()->user()->company_id)->get();
        $users = User::get();
        $customers = Customer::get();
        $products = Product::getData();
        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $order_lines = [];
        $posting_profiles = CustomerPostingProfile::get();

        if ($id) {
            $item = SalesOrderReturn::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
            $item['confirmed_user'] = $item->confirmed_by_user ? $item->confirmed_by_user->fullname : '---';
            $order_lines  = collect($item->sales_order_return_lines)->map(function ($line) {
                $line->product = $line->product;
                return $line;            
            });
        }

        return response()->json([
            'item' => $item,
            'payment_methods' => $payment_methods,
            'users' => $users,
            'terms_of_payments' => $terms_of_payments,
            'customers' => $customers,
            'products' => $products,
            'variants' => Variant::getData(),
            'cost_centers' => $cost_centers,
            'expense_purposes' => $expense_purposes,
            'departments' => $departments,
            'sales_order_lines' => $order_lines,
            'clients' => User::getClients(4, 'App\Models\SalesOrders\SalesOrderReturn'),
            'posting_profiles' => $posting_profiles,
            'specifications' => Specification::where('company_id', auth()->user()->company_id)->get(),
            'services' => Service::where('company_id', auth()->user()->company_id)->with('serviceTasks')->get(),
            'procurements' => Procurement::where('company_id', auth()->user()->company_id)->get(),
            'charges_on_lines' => Charge::where('status', 'Enabled')->where('level', 'Line')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'charges_on_header' => Charge::where('status', 'Enabled')->where('level', 'Main')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'discount_on_lines' => Discount::where('status', 'Enabled')->where('level', 'Line')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
            'discount_on_header' => Discount::where('status', 'Enabled')->where('level', 'Main')->where('company_id', auth()->user()->company_id)->with('product', 'variant', 'service', 'serviceTask', 'procurement')->get(),
        ]);
    }

    protected function formatView($item)
    {
        $item->is_already_confirmed = $item->confirmed_by ? true : false;
        $item->client = $item->client;
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->confirmationUrl = $item->renderConfirmationUrl();
        $item->customerInvoiceUrl = $item->renderCustomerInvoiceUrl();
        $item->hasExistingInvoice = $item->vendor_invoice ? true : false;
        
        $item->created_by = $item->created_by_user;
        $item->updated_by = $item->updated_by_user;
        $item->formatted_created_at = $item->renderDate('created_at');
        $item->formatted_updated_at = $item->renderDate('updated_at');

        return $item;
    }
}
