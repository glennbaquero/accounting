<?php

namespace App\Http\Controllers\LedgerCalendars;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\LedgerCalendars\LedgerCalendar; 

use App\Models\Ledgers\Ledger;

use App\Models\FiscalCalendars\FiscalCalendar;

use App\Models\Users\User;

use Carbon\Carbon;

class LedgerCalendarFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new LedgerCalendar;
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
                'ledger_code' => $item->parent ? $item->parent->ledger_code : '---',
                'ledger_calendar_code' => $item->ledger_calendar_code,
                'ledger_calendar_name' => $item->ledger_calendar_name,
                'ledger_calendar_year' => $item->ledger_calendar_year ? Carbon::parse($item->ledger_calendar_year)->format('Y') : '---',
                'fiscal_calendar_code' => $item->fiscal_calendar_code,
                'fiscal_year_start_date' => $item->fiscal_year_start_date ? Carbon::parse($item->fiscal_year_start_date)->format('m-d-Y') : '---',
                'fiscal_year_end_date' => $item->fiscal_year_end_date ? Carbon::parse($item->fiscal_year_end_date)->format('m-d-Y') : '---',
                'ledger_calendar_status' => $item->ledger_calendar_status ? 1 : null,
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
        $ledgers = Ledger::all();
        $fiscalcalendars = FiscalCalendar::all();
        $client = User::getClients();


        if ($id) {
            $item = LedgerCalendar::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }
        // dd($item);
        return response()->json([
            'item' => $item,
            'ledgers' => $ledgers,         
            'fiscalcalendars' => $fiscalcalendars,   
            'clients' => $client,

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
