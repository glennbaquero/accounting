<?php

namespace App\Http\Controllers\Procurements;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\Procurements\Procurement;

class ProcurementFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Procurement;
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
        
        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

        return $query->where('company_id', auth()->user()->company_id)->withTrashed();
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
                'client' => $item->client ? $item->client->name : '---',
                'procurement' => $item->procurement,
                'main_account_code' => $item->main_account_code,
                'main_account_name' => $item->main_account_name,
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
        $clients = User::getClients();

        if ($id) {
            $item = Procurement::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'main_accounts' => MainAccount::get(),
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
