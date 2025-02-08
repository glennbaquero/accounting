<?php

namespace App\Http\Controllers\CustomerPaymentFeeSetups;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Customers\CustomerPaymentFeeSetup;
use App\Models\Users\User;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;

class CustomerPaymentFeeSetupFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CustomerPaymentFeeSetup;
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
                'fee_id' => $item->fee_id,
                'payment_specification' => $item->payment_specification,
                'percentage_amount' => $item->percentage_amount,
                'fee_amount' => $item->fee_amount,
                'minimum' => $item->minimum,
                'maximum' => $item->maximum,
                'from_date' => $item->from_date,
                'to_date' => $item->to_date,
                'minimum_fee' => $item->minimum_fee,
                'tax_account' => $item->tax_account,
                'days' => $item->days,

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
            $item = CustomerPaymentFeeSetup::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => User::getClients(),
            'payment_methods' => CustomerPaymentMethod::get(),
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
