<?php

namespace App\Http\Controllers\TaxTables;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\TaxTables\TaxTable;
use App\Models\MainAccounts\MainAccount;
use Carbon\Carbon;

class TaxTableFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new TaxTable;
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
                'tax_posting_name' => $item->tax_posting_name,
                'description' => $item->description,
                'tax_percent' => $item->tax_percent,
                'peza_checkbox' => $item->peza_checkbox,
                'vat_exempt_number_checkbox' => $item->vat_exempt_number_checkbox,
                'tax_account_code_number' => $item->tax_account_code_number,
                'tax_account' => $item->tax_account,
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


        $main_accounts = MainAccount::getCompanyData();

        if ($id) {
            $item = TaxTable::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'main_accounts' => $main_accounts,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->renderUpdatedBy();
        $item->created_by = $item->renderCreatedBy();
        $item->created_date = $item->renderDate('created_at', 'm/d/Y h:i A');
        $item->updated_date = $item->renderDate('updated_at', 'm/d/Y h:i A');

        return $item;
    }
}
