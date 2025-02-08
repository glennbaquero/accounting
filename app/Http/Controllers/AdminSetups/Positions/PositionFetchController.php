<?php

namespace App\Http\Controllers\AdminSetups\Positions;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\AdminSetups\Company;
use App\Models\AdminSetups\Position;
use App\Models\AdminSetups\Department;

use Carbon\Carbon;

class PositionFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Position;
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

        if($this->request->filled('department')) {
            $query = $query->where('department_id', $this->request->department);
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
                'company' => $item->renderCompanyName(),
                'department' => $item->department->name,
                'name' => $item->name,
                'code' => $item->code,
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
        $departments = Department::all();
        $companies = Company::all();

        if ($id) {
            $item = Position::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'departments' => $departments,
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
