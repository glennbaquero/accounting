<?php

namespace App\Http\Controllers\CustomerPaymentFees;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Users\User;
use App\Models\Customers\CustomerPaymentFee;
use App\Models\MainAccounts\MainAccount;

class CustomerPaymentFeeFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CustomerPaymentFee;
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

        return $query;
    }

    /**
     * Custom formatting of data
     * 
     * @param CustomerPayment[]
     * @return array $result
     */
    public function formatData($items) {
        $result = [];

        foreach($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'fee_id' => $item->fee_id,
                'name' => $item->name,
                'charge_to' => $item->charge_to,
                'deleted_at' => $item->deleted_at
            ]);

            array_push($result, $data);
        }

        return $result;
    }

    /**
     * Build array data
     * @param CustomerPaymentFee
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

    public function fetchView($id = null) {
        $item = null;
        
        if ($id) {
            $item = CustomerPaymentFee::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => User::getClients(),
            'main_accounts' => MainAccount::get(),
        ]);
    }

    protected function formatView($item) {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
