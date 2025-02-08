<?php

namespace App\Http\Controllers\SalesOrderLines;

use Carbon\Carbon;

use App\Models\Users\User;
use App\Models\Customers\Customer;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Variant;
use App\Models\SalesOrders\SalesOrder;
use App\Models\SalesOrders\SalesOrderLine;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;
use App\Models\PostingProfile\CustomerPostingProfile;

use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\FinancialDimensions\FinancialDimensionValue;

use App\Extenders\Controllers\FetchController as Controller;

class SalesOrderLineFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new SalesOrderLine;
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
        $query = $query->whereHas('sales_order', function($q) { $q->whereNotNull('confirmed_date'); });

        $query = $query->where('variant_id', $this->request->variant);

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
                'sales_order_number' => $item->sales_order_number,
                'sales_order_line_number' => $item->sales_order_line_number,
                'customer' => $item->sales_order->customer_name,
                'confirmed_delivery_date' => $item->sales_order->confirmed_date,
                'order_date' => $item->sales_order->sales_order_date,

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
            'archiveUrl' => $item->renderArchiveUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        $payment_methods = PaymentMethod::get();
        $terms_of_payments = TermsOfPayment::get();
        $users = User::get();
        $customers = Customer::get();
        $products = Product::getData();
        $cost_centers = FinancialDimension::renderFinancialDimensionValues('Cost centers');
        $departments = FinancialDimension::renderFinancialDimensionValues('Departments');
        $expense_purposes = FinancialDimension::renderFinancialDimensionValues('Expense purposes');
        $order_lines = [];
        $posting_profiles = CustomerPostingProfile::get();

        if ($id) {
            $item = SalesOrder::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
            $item['confirmed_user'] = $item->confirmed_by_user ? $item->confirmed_by_user->fullname : '---';
            $order_lines  = collect($item->sales_order_lines)->map(function ($line) {
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
            'clients' => User::getClients(),
            'posting_profiles' => $posting_profiles,
        ]);
    }

    protected function formatView($item)
    {
        $item->is_already_confirmed = $item->confirmed_by ? true : false;
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
