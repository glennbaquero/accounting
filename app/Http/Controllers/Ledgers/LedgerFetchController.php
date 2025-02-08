<?php

namespace App\Http\Controllers\Ledgers;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Ledgers\Ledger; 

use App\Models\LedgerSetup\ChartOfAccount;

use App\Models\LedgerCalendars\LedgerCalendar;

use App\Models\AdminSetups\Client;
use App\Models\FiscalCalendars\FiscalCalendar;
use App\Models\Users\User;
use Carbon\Carbon;

class LedgerFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Ledger;
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

        return $query->where('company_id' , auth()->user()->company_id);
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
                'ledger_id' => $item->ledger_id,
                'ledger_code' => $item->ledger_code,
                'client' => $item->client->name,
                'ledger_name' => $item->ledger_name,
                'chart_of_account' => $item->chart_of_account->coa_name,
                'ledger_calendar' => $item->ledger_calendar->ledger_calendar_name,
                'description' => $item->description,
                'company_name' => $item->company_name,
                'active_from' => Carbon::parse($item->active_from)->format('m-d-Y'),
                'active_to' => Carbon::parse($item->active_to)->format('m-d-Y'),                
                'ledger_status' => $item->ledger_status  == 1 ? 'Active' : 'Inactive',                                
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
        $company = auth()->user()->company_id;
        $chart_of_accounts = ChartOfAccount::where('company_id', $company)->get();
        $ledger_calendars = LedgerCalendar::where('company_id', $company)->doesntHave('ledger')->get();
        $fiscal_calendars = FiscalCalendar::where('company_id', $company)->get();
        $clients = User::getClients();

        if ($id) {  
            $item = Ledger::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'chart_of_accounts' => $chart_of_accounts,
            'ledger_calendars' => $ledger_calendars,
            'fiscal_calendars' => $fiscal_calendars,
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