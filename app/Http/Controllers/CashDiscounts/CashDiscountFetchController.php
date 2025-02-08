<?php

namespace App\Http\Controllers\CashDiscounts;

use Illuminate\Http\Request;
use App\Models\Vendors\Vendor;
use App\Models\Customers\Customer;
use App\Models\JournalSetups\CashDiscount;
use App\Extenders\Controllers\FetchController as Controller;

class CashDiscountFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CashDiscount;
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

        return $query->withTrashed();
    }

    /**
     * Custom formatting of data
     * 
     * @param \App\Models\JournalSetups\CashDiscount $items
     * @return array $result
     */
    public function formatData($items)
    {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);

            $data = array_merge($data, [
                'id' => $item->id,
                'next_discount_code' => $item->next_discount_code,
                'months' => $item->months,
                'days' => $item->days,
                'net_or_current' => $item->net_or_current,
                'discount_offset_accounts' => $item->discount_offset_accounts,
                'discount_cash' => $item->discount_cash,
                'discount_percent' => $item->discount_percent,
                'customer_id' => $item->customer_account,
                'customer_name' => $item->customer->renderName(),
                'vendor_id' => $item->vendor_account,
                'vendor_name' => $item->vendor->renderName(),
                'created_at' => $item->renderDate()            
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
            $item = CashDiscount::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'vendors' => Vendor::get(),
            'customers' => Customer::get()
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
