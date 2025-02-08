<?php

namespace App\Http\Controllers\AccrualPeriods;

use App\Extenders\Controllers\FetchController as Controller;
use App\Models\GeneralLedgers\AccrualPeriod;
use Carbon\Carbon;

class AccrualPeriodFetchController extends Controller
{
    /**
     * Set object class of fetched data
     * 
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new AccrualPeriod;
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
        if($this->request->filled('accrual_id')) {
            $query = $query->where('accrual_id', $this->request->accrual_id);
        }

        return $query->where('company_id', auth()->user()->company_id);
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
                'accrual_id' => $item->accrual_posting->accrual_id ?? '---',
                'accrual_posting' => $item->accrual_posting->accrual_posting ?? '---',
                'period_code' => $item->period_id ?? '---',
                'fiscal_period_start_date' => Carbon::parse($item->fiscal_period_start_date)->format('m/d/Y') ?? '---',
                'fiscal_period_end_date' => Carbon::parse($item->fiscal_period_end_date)->format('m/d/Y') ?? '---',
                'fiscal_month' => $item->fiscal_month ?? '---',
                'fiscal_quarter' => $item->fiscal_quarter ?? '---',
                'fiscal_period_status' => $item->fiscal_period_status ?? '---',
                'fiscal_period_type' => $item->fiscal_period_type ?? '---',
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
        $clients = User::getClients();
        $main_accounts = MainAccount::where('company_id', auth()->user()->company_id)->get();
        $ledgers = Ledger::where('company_id', auth()->user()->company_id)->with('ledger_calendar.fiscal_calendar')->get();

        if ($id) {
            $item = AccrualPosting::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'clients' => $clients,
            'ledgers' => $ledgers,
            'main_accounts' => $main_accounts,
        ]);
    }

    protected function formatView($item)
    {
        $item->archiveUrl = $item->renderArchiveUrl();
        $item->restoreUrl = $item->renderRestoreUrl();

        return $item;
    }
}