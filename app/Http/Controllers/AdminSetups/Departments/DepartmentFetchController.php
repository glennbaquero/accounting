<?php

namespace App\Http\Controllers\AdminSetups\Departments;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\AdminSetups\Company;
use App\Models\AdminSetups\Department;
use App\Models\Users\User;

use Carbon\Carbon;

class DepartmentFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Department;
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

        if($this->request->filled('company')) {
            $query = $query->where('company_id', $this->request->company);
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
                'company' => $item->company ? $item->company->name : '---',
                'name' => $item->name,
                'code' => $item->code,
                'head' => $item->head ? $item->head->renderName() : '---',
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
            'withCompanyShowUrl' => $item->withCompanyRenderShowUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $users = User::all();
        $companies = Company::all();

        if ($id) {
            $item = Department::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'users' => $users,
            'companies'=> $companies,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
