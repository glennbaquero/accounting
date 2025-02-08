<?php

namespace App\Http\Controllers\Discounts;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Discounts\Discount;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\Procurements\Procurement;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Variant;
use App\Models\Services\Service;
use App\Models\Services\ServiceTask;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

class DiscountFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Discount;
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

        return $query->where('company_id', auth()->user()->company_id);
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
                'name' => $item->name,

                'level' => $item->level,
                'applied_to' => $item->applied_to,
                'delivery_type' => $item->delivery_type,
                'discount_category' => $item->discount_category,
                'discount_value' => $item->discount_value,
                'from_amount' => $item->from_amount,
                'to_amount' => $item->to_amount,
                'quantity' => $item->quantity,
                'discount_percentage' => $item->discount_percentage,

                'vendor_payment_method' => $item->vendorPaymentMethod->method_of_payment,
                'customer_payment_method' => $item->customerPaymentMethod->method_of_payment,
                'procurement' => $item->procurement->main_account_name,
                'product' => $item->product->name,
                'variant' => $item->variant->name,
                'service' => $item->service->name,
                'serviceTask' => $item->serviceTask->service,
                'mainAccount' => $item->mainAccount->main_account_name,

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

        if ($id) {
            $item = Discount::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => User::getClients(),
            'vendor_payment_methods' => VendorPaymentMethod::get(),
            'customer_payment_methods' => CustomerPaymentMethod::get(),
            'customer_payment_methods' => CustomerPaymentMethod::get(),
            'procurements' => Procurement::get(),
            'products' => Product::with('variants')->get(),
            'services' => Service::with('serviceTasks')->get(),
            'main_accounts' => MainAccount::get(),

        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
