<?php

namespace App\Http\Controllers\MainAccounts;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\MainAccounts\MainAccount; 
use App\Models\MainAccountCategories\MainAccountCategory;
use App\Models\LedgerSetup\ChartOfAccount;
use App\Models\LinkedMainAccounts\LinkedMainAccount;
use App\Models\Users\User;

class MainAccountFetchController extends Controller
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

        if($this->request->filled('detach')) {
            $ids = LinkedMainAccount::find($this->request->detach)->main_accounts->pluck('id');
            $query = $query->whereNotIn('id', $ids);
        }

        if($this->request->filled('attach')) {
            $ids = LinkedMainAccount::find($this->request->attach)->main_accounts->pluck('id');
            $query = $query->whereIn('id', $ids);
        }

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
                'client' => $item->client ? $item->client->name : '---',    
                'main_account_id' => $item->main_account_id,    
                'main_account_code' => $item->main_account_code,                            
                'main_account_name' => $item->main_account_name,
                'main_account_type' => $item->main_account_type,
                'main_account_category_id' => $item->main_account_category_id
                ? $item->main_account_category_selected->main_account_category : '-' ,
                'db_cr_requirement' => $item->db_cr_requirement,                                
                'active_to' => $item->active_to,
                'posting_type' => $item->posting_type,
                                                
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
            'attachUrl' => $item->renderAttachLinkMainAccountUrl(),
            'detachUrl' => $item->renderDetachLinkMainAccountUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $company = auth()->user()->company_id;        
        $main_account_categories = MainAccountCategory::where('company_id', $company)->get();
        $chart_of_accounts = ChartOfAccount::where('company_id', $company)->get();
        $clients = User::getClients();

        if ($id) {
            $item = MainAccount::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }
        
        return response()->json([
            'item' => $item,
            'main_account_categories' => $main_account_categories,
            'chart_of_accounts' => $chart_of_accounts,
            'clients' => $clients,
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
