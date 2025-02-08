<?php

namespace App\Http\Controllers\PaymentFees;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\PaymentFees\PaymentFee;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\Users\User;

class PaymentFeeFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new PaymentFee;
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
                'remittance_type' => $item->remittance_type,
                'payment_specification' => $item->payment_specification,
                'payment_date' => $item->payment_date,
                'due_date' => $item->due_date,

                'vendor_payment_method' => $item->vendorPaymentMethod->method_of_payment,
                'customer_payment_method' => $item->customerPaymentMethod->method_of_payment,
                'client_bank_account' => $item->clientBankAccount->bank_account,

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
            $item = PaymentFee::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => User::getClients(),
            'vendor_payment_methods' => VendorPaymentMethod::get(),
            'customer_payment_methods' => CustomerPaymentMethod::get(),
            'bank_accounts' => ClientBankAccount::get(),
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
