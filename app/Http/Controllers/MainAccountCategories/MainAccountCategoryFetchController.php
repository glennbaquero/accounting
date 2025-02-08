<?php

namespace App\Http\Controllers\MainAccountCategories;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\MainAccountCategories\MainAccountCategory; 

use App\Models\Users\User;

class MainAccountCategoryFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new MainAccountCategory;
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
                'main_account_category_reference' => $item->main_account_category_reference,
                'client' => $item->client ? $item->client->name : '---',
                'main_account_category' => $item->main_account_category,
                'description' => $item->description,                                 
                'main_account_type' => $item->main_account_type,
                'closed_checkbox' => $item->closed_checkbox ? 1 : null,                
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
        $client = User::getClients();

        if ($id) {
            $item = MainAccountCategory::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }
        // dd($item);
        return response()->json([
            'item' => $item,
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

        return $item;
    }
}
