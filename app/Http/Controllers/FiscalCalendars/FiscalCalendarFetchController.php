<?php

namespace App\Http\Controllers\FiscalCalendars;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FiscalCalendars\FiscalCalendar;

use App\Models\Users\User;

use Carbon\Carbon;

class FiscalCalendarFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new FiscalCalendar;
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

        return $query->withTrashed();
    }

    /**
     * 
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
                'client' => $item->client  ? $item->client->name : '---',
                'fiscal_calendar_code' => $item->fiscal_calendar_code,
                'fiscal_calendar_name' => $item->fiscal_calendar_name,
                'fiscal_year_start_date' => $item->fiscal_year_start_date ? Carbon::parse($item->fiscal_year_start_date)->format('m-d-Y') : '---',
                'fiscal_year_end_date' => $item->fiscal_year_end_date ? Carbon::parse($item->fiscal_year_end_date)->format('m-d-Y') : '---',                
                'length_of_period' => $item->length_of_period,
                'unit' => $item->unit,
                'fiscal_year_status' => $item->fiscal_year_status,
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

        if ($id) {
            $item = FiscalCalendar::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
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
