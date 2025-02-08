<?php

namespace App\Http\Controllers\Specifications;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\ProductInventories\Products\Specification;
use App\Models\ProductInventories\Products\Variant;
use App\Models\Users\User;

class SpecificationFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Specification;
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
                'client' => $item->client->name,
                'construction' => $item->construction,
                'fibre' => $item->fibre,
                'dye_method' => $item->dye_method,
                'gauge' => $item->gauge,
                'size' => $item->size,
                'yarn' => $item->yarn,
                'average_density' => $item->average_density,
                'tufted_weight' => $item->tufted_weight,
                'production_weight' => $item->production_weight,
                'total_thickness' => $item->total_thickness,
                'secondary_backing' => $item->secondary_backing,
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

        if ($id) {
            $item = Specification::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'variants' => Variant::get(),
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}