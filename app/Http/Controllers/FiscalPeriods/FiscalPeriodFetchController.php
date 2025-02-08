<?php

namespace App\Http\Controllers\FiscalPeriods;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\FiscalPeriods\FiscalPeriod;
use App\Models\Users\User;

use Carbon\Carbon;

class FiscalPeriodFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new FiscalPeriod;
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
        // $query = $query->where('company_id', auth()->user()->company_id);
        if($this->request->filled('fiscal_calendar_id')) {
            $query = $query->where('fiscal_calendar_id', $this->request->fiscal_calendar_id);
        }                

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
                'fiscal_calendar_code' => $item->fiscal_calendar_code,
                'fiscal_period_code' => $item->fiscal_period_code,
                'fiscal_period_type' => $item->fiscal_period_type,
                'fiscal_period_start_date' => Carbon::parse($item->fiscal_period_start_date)->format('m-d-Y'),                
                'fiscal_period_end_date' => Carbon::parse($item->fiscal_period_end_date)->format('m-d-Y'),
                'fiscal_month' => $item->fiscal_month,
                'fiscal_quarter' => $item->fiscal_quarter,
                'fiscal_period_status' => $item->fiscal_period_status,
                'fiscal_month' => $item->fiscal_month,
                'client' => $item->client  ? $item->client->name : '---',
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
            $item = FiscalPeriod::withTrashed()->findOrFail($id);
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
