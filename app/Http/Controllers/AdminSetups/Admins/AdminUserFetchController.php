<?php

namespace App\Http\Controllers\AdminSetups\Admins;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\AdminSetups\Company;
use App\Models\Users\User;
use App\Models\AdminSetups\Position;
use App\Models\AdminSetups\Department;

use Carbon\Carbon;

class AdminUserFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new User;
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

         $query = $query->whereHas("roles", function($use){ $use->where("name",'<>',"Super Admin"); });

        if($this->request->filled('system-admin')) {
            $query = $query->whereHas("roles", function($use){ $use->where("name", ["Admin"]); });
        }

        if($this->request->filled('company-admin')) {
            $query = $query->whereHas("roles", function($use){ $use->where("name", ["Company Admin"]); });
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
                'fullname' => $item->renderName(),
                'company' => $item->company ? $item->company->name : '-',
                'role' => $item->getRoleNames() ? $item->getRoleNames()->first() : '-',
                'active_from' => Carbon::parse($item->active_from)->format('M d, Y'),
                'active_to' => Carbon::parse($item->active_to)->format('M d, Y'),
                'status' => $item->status,
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
            'showUrl' => $item->adminRenderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $companies = Company::all();

        if ($id) {
            $item = User::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'companies' => $companies,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
