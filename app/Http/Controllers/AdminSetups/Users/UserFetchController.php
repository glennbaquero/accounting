<?php

namespace App\Http\Controllers\AdminSetups\Users;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\AdminSetups\Company;
use App\Models\Users\User;
use App\Models\AdminSetups\Position;
use App\Models\AdminSetups\Department;

use Carbon\Carbon;

class UserFetchController extends Controller
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

        $query = $query->doesntHave('roles');

        if($this->request->filled('department')) {
            $query = $query->where('department_id', $this->request->department);
        }

        if($this->request->filled('position')) {
            $query = $query->where('position_id', $this->request->position);
        }

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
                'fullname' => $item->renderName(),
                'company' => $item->company ? $item->company->name : '-',
                'department' => $item->department ? $item->department->name : '-',
                'position' => $item->position ? $item->position->name : '-',
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
            'showUrl' => $item->renderShowUrl(),
            'archiveUrl' => $item->renderArchiveUrl(),
            'restoreUrl' => $item->renderRestoreUrl(),
            'withCompanyShowUrl' => $item->withCompanyRenderShowUrl(),
        ];
    }

    public function fetchView($id = null)
    {
        $item = null;
        $companies = Company::all();
        $departments = Department::all();
        $positions = Position::all();

        if ($id) {
            $item = User::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'companies' => $companies,
            'departments' => $departments,
            'positions' => $positions,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}
