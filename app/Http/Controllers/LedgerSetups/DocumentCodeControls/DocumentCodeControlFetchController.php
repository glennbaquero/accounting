<?php

namespace App\Http\Controllers\LedgerSetups\DocumentCodeControls;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\LedgerSetups\DocumentCodeControls\DocumentCodeControl;
use App\Models\Users\User;

class DocumentCodeControlFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new DocumentCodeControl();
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
        
        if($this->request->filled('status')) {
            $query = $query->where('active', $this->request->status);
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
                'client' => $item->client->name,
                'module' => $item->renderName(),
                'code' => $item->getCode(),
                'active' => $item->active,
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
        $modules = DocumentCodeControl::getModules();
        $clients = User::getClients();

        if ($id) {
            $item = DocumentCodeControl::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'modules' => $modules,
            'clients' => $clients
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}