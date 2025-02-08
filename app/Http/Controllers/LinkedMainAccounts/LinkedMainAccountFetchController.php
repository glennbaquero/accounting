<?php

namespace App\Http\Controllers\LinkedMainAccounts;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\LinkedMainAccounts\LinkedMainAccount; 

use App\Models\LedgerSetup\ChartOfAccount;
use App\Models\MainAccounts\MainAccount;

use App\Models\Users\User;  
use Carbon\Carbon;

class LinkedMainAccountFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new LinkedMainAccount;
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
                'client' => $item->client ? $item->client->name : '---',
                'linked_main_account_code' => $item->linked_main_account_code,
                'chart_of_accounts_code' => $item->chart_of_accounts_code,
                'chart_of_accounts_name' => $item->chart_of_accounts_name,
                'main_account_code' => $item->main_account_code,
                'main_account' => $item->main_account,
                'main_account_type' => $item->main_account_type,
                'main_account_category' => $item->main_account_category,
                'linked' => $item->linked,
                'main_accounts' => $item->main_accounts->count(),
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
            // 'showLMAUrl' => $item->renderLinkedMAShowUrl(),            
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $chart_of_accounts = ChartOfAccount::all();
        $clients = User::getClients();
        $main_accounts = MainAccount::get();

        if ($id) {
            $item = LinkedMainAccount::withTrashed()->findOrFail($id);
            $item->creator = $item->created_by_user->fullname;
            $item->updator = $item->updated_by_user ? $item->updated_by_user->fullname : '---';
            $item->formatted_updated_at = Carbon::parse($item->updated_at)->format('M d, Y h:i A');
            $item->formatted_created_at = Carbon::parse($item->created_at)->format('M d, Y h:i A');
            $item = $this->formatView($item);
        }
        // dd($item);
        return response()->json([
            'item' => $item,
            'chart_of_accounts' => $chart_of_accounts,
            'clients' => $clients,
            'main_accounts' => $main_accounts,

        ]);
    
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
