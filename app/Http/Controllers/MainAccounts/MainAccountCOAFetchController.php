<?php

namespace App\Http\Controllers\MainAccounts;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\MainAccounts\MainAccount; 
use App\Models\MainAccountCategories\MainAccountCategory;

class MainAccountCOAFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new MainAccount;
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

        if($this->request->filled('coa_id')) {
            $query = $query->where('chart_of_account_id', $this->request->coa_id);
        }                

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
                'main_account_code' => $item->main_account_code,
                'main_account_name' => $item->main_account_name,
                'main_account_type' => $item->main_account_type,          
                'main_account_category' => $item->main_account_category_id ? $item->main_account_category_selected->main_account_category : '-',
                'coa_code' => $item->coa_code,                
                                                
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
            'showUrl' => $item->renderShowCOAUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $main_account_categories = MainAccountCategory::all();


        if ($id) {
            $item = MainAccount::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }
        
        // dd($item);
        return response()->json([
            'item' => $item,
            'main_account_categories' => $main_account_categories,
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

        return $item;
    }
}
