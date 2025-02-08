<?php

namespace App\Http\Controllers\AdminSetups\Clients;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;

class ClientFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Client();
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

        if($this->request->filled('detach')) {
            $ids = User::find($this->request->detach)->clients->pluck('id');
            $query = $query->whereNotIn('id', $ids);
        }

        if($this->request->filled('attach')) {
            $ids = User::find($this->request->attach)->clients->pluck('id');
            $query = $query->whereIn('id', $ids);
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
                'name' => $item->name,
                'user_count' => $item->name,
                'created_at' => $item->renderDate(),
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
            'userDetachUrl' => $item->renderDetachUser(),
            'userAttachUrl' => $item->renderAttachUserUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;

        if ($id) {
            $item = Client::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}