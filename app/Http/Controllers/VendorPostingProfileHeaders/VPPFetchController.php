<?php

namespace App\Http\Controllers\VendorPostingProfileHeaders;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\Users\User;
use App\Models\MainAccounts\MainAccount;
use App\Models\PostingProfile\VendorPostingProfileHeader;
use App\Models\Vendors\Vendor;
use Carbon\Carbon;

class VPPFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new VendorPostingProfileHeader;
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
                'posting_profile' => $item->posting_profile,
                'description' => $item->description,
                'document' => $item->renderDocument(),
                'client' => $item->client->name,
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
        $documents = VendorPostingProfileHeader::getDocuments();

        if ($id) {
            $item = VendorPostingProfileHeader::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'documents' => $documents,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();
        $item->updated_by = $item->updated_by_user ? $item->updated_by_user->renderName() : '---';
        $item->created_by = $item->created_by_user ? $item->created_by_user->renderName() : '---';
        $item->updated_on = $item->updated_on ? $item->updated_on : '---';
        $item->created_on = $item->created_on ? $item->created_on : '---';
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
