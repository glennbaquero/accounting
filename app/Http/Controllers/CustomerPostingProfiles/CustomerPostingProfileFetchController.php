<?php

namespace App\Http\Controllers\CustomerPostingProfiles;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\AdminSetups\Client;
use App\Models\PostingProfile\CustomerPostingProfile;
use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\Customers\Customer;

class CustomerPostingProfileFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new CustomerPostingProfile;
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

        if($this->request->filled('client')) {
            $query = $query->where('client_id', $this->request->client);
        }

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
                'posting_profile' => $item->posting_profile,
                'description' => $item->description,
                'account_code' => $item->account_code,
                'account' => $item->account,
                'group_number' => $item->group_number,
                'summary_account' => $item->summaryAccount ? $item->summaryAccount->main_account_name : '---',
                'settle_account' => $item->settle_account,
                'sales_tax_prepayments' => $item->sales_tax_prepayments,
                'arrival' => $item->arrival,
                'offset_account' => $item->offset_account,
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
        $main_accounts = MainAccount::get();
        $customers = Customer::get();

        if ($id) {
            $item = CustomerPostingProfile::withTrashed()->findOrFail($id);
            $item['group_number'] = json_decode($item->group_number);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'main_accounts' => $main_accounts,
            'customers' => $customers,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
