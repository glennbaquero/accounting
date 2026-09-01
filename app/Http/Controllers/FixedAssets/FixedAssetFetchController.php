<?php

namespace App\Http\Controllers\FixedAssets;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FixedAssets\FixedAsset;
use App\Models\MainAccounts\MainAccount;
use App\Models\Users\User;

use Carbon\Carbon;

class FixedAssetFetchController extends Controller
{
    /**
     * Set object class of fetched data
     *
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new FixedAsset;
    }

    /**
     * Custom filtering of query
     *
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
        $query = $query->where('company_id', auth()->user()->company_id);

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

        foreach ($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'client' => $item->client ? $item->client->name : '---',
                'asset_id' => $item->asset_id,
                'asset_code' => $item->asset_code,
                'asset_name' => $item->asset_name,
                'main_account_name' => $item->main_account_name,
                'acquisition_date' => $item->acquisition_date ? Carbon::parse($item->acquisition_date)->format('m-d-Y') : '---',
                'acquisition_cost' => number_format($item->acquisition_cost, 2),
                'accumulated_depreciation' => number_format($item->getAccumulatedDepreciation(), 2),
                'net_book_value' => number_format($item->getNetBookValue(), 2),
                'asset_status' => $item->asset_status,
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
     * @param  App\Models\FixedAssets\FixedAsset
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
        $mainaccounts = MainAccount::all();
        $client = User::getClients();

        if ($id) {
            $item = FixedAsset::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'mainaccounts' => $mainaccounts,
            'clients' => $client,
        ]);
    }

    protected function formatView($item)
    {
        $item->created_by = $item->created_by_user;
        $item->updated_by = $item->updated_by_user;
        $item->formatted_created_at = $item->renderDate('created_at');
        $item->formatted_updated_at = $item->renderDate('updated_at');

        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        $item->accumulated_depreciation = $item->getAccumulatedDepreciation();
        $item->net_book_value = $item->getNetBookValue();
        $item->depreciation_lines = $item->depreciation_lines()->orderBy('period_number')->get();

        return $item;
    }
}
