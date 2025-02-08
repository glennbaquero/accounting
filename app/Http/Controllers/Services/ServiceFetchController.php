<?php

namespace App\Http\Controllers\Services;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Services\Service;
use App\Models\Users\User;
use App\Models\Vendors\Vendor;

class ServiceFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Service();
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
                'service_number' => $item->service_number,
                'name' => $item->name,
                'work_type'=> $item->work_type,
                'service_type'=> $item->service_type,
                'unit_price'=> $item->unit_price,
                'client' => $item->client->name,
                'created_at' => $item->renderDate(),
                'deleted_at' => $item->deleted_at,
            ]);

            array_push($result, $data);
        }

        return $result;
    }

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
        $vendors = Vendor::where('company_id', auth()->user()->company_id)->get();

        if ($id) {
            $item = Service::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'vendors' => $vendors,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}