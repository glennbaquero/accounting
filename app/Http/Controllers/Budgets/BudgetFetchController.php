<?php

namespace App\Http\Controllers\Budgets;

use App\Extenders\Controllers\FetchController as Controller;

use App\Models\Budgets\Budget;
use App\Models\Ledgers\Ledger;
use App\Models\FiscalCalendars\FiscalCalendar;
use App\Models\FiscalPeriods\FiscalPeriod;
use App\Models\MainAccounts\MainAccount;
use App\Models\FinancialDimensions\FinancialDimension;
use App\Models\Users\User;

use Carbon\Carbon;

class BudgetFetchController extends Controller
{
    /**
     * Set object class of fetched data
     *
     * @return void
     */
    public function setObjectClass()
    {
        $this->class = new Budget;
    }

    /**
     * Custom filtering of query
     *
     * @param Illuminate\Support\Facades\DB $query
     * @return Illuminate\Support\Facades\DB $query
     */
    public function filterQuery($query)
    {
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

        foreach ($items as $item) {
            $data = $this->formatItem($item);
            $data = array_merge($data, [
                'id' => $item->id,
                'client' => $item->client ? $item->client->name : '---',
                'budget_id' => $item->budget_id,
                'budget_code' => $item->budget_code,
                'budget_name' => $item->budget_name,
                'ledger_code' => $item->ledger_code,
                'fiscal_calendar_code' => $item->fiscal_calendar_code,
                'budget_year' => $item->budget_year ? Carbon::parse($item->budget_year)->format('Y') : '---',
                'budget_status' => $item->budget_status,
                'total_budgeted_amount' => number_format($item->getTotalBudgetedAmount(), 2),
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
     * @param  App\Models\Budgets\Budget
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
        $fiscalperiods = FiscalPeriod::all();
        $mainaccounts = MainAccount::all();
        $departments = FinancialDimension::where('use_value_from', 'Departments')->first()?->financial_dimension_values;
        $cost_centers = FinancialDimension::where('use_value_from', 'Cost centers')->first()?->financial_dimension_values;
        $client = User::getClients();

        if ($id) {
            $item = Budget::withTrashed()->findOrFail($id);
            $item = $this->formatView($item);
        }

        return response()->json([
            'item' => $item,
            'ledgers' => $ledgers,
            'fiscalcalendars' => $fiscalcalendars,
            'fiscalperiods' => $fiscalperiods,
            'mainaccounts' => $mainaccounts,
            'departments' => $departments,
            'cost_centers' => $cost_centers,
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

        $item->budget_lines = $item->budget_lines()->get();

        return $item;
    }
}
