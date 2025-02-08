<?php

namespace App\Http\Controllers\Customers;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Customers\Customer;
use App\Models\JournalSetups\PaymentMethod;
use App\Models\JournalSetups\TermsOfPayment;

class CustomerFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Customer;
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
                'id' => $item->customer_account,
                'name' => $item->renderName(),
                'display_name' => $item->display_name,
                'company' => $item->company,
                'customer_account' => $item->customer_account,
                'mobile_number' => $item->mobile_number,
                'address' => $item->renderShippingAddress(),
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
        $payment_methods = PaymentMethod::get();
        $terms_of_payments = TermsOfPayment::get();

        if ($id) {
            $item = Customer::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        $customer_parent_lists = $item ? Customer::whereNotIn('id', [$item->id])->get() : Customer::get();
        $customer_bill_parent_lists = $item ? Customer::whereNotIn('id', [$item->id])->get() : Customer::get();

        return response()->json([
            'item' => $item,
            'customer_parent_lists' => $customer_parent_lists,
            'customer_bill_parent_lists' => $customer_bill_parent_lists,
            'payment_methods' => $payment_methods,
            'terms_of_payments' => $terms_of_payments,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->mobile_number_component = $item->mobile_calling_code ? explode($item->mobile_calling_code,$item->mobile_number)[1] : $item->mobile_number;
        $item->phone_number_component = $item->phone_calling_code ? explode($item->phone_calling_code,$item->phone)[1] : $item->phone;

        return $item;
    }
}
