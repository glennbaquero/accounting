<?php

namespace App\Models\Budgets;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;
use App\Models\Ledgers\Ledger;
use App\Models\FiscalCalendars\FiscalCalendar;
use App\Models\BudgetLines\BudgetLine;

class Budget extends Model
{
    /**
     * @Relationship
     */

    public function budget_lines() {
        return $this->hasMany(BudgetLine::class, 'budget_id', 'budget_id')->withTrashed();
    }

    public function ledger() {
        return $this->belongsTo(Ledger::class, 'ledger_id')->withTrashed();
    }

    public function fiscal_calendar() {
        return $this->belongsTo(FiscalCalendar::class, 'fiscal_calendar_code', 'fiscal_calendar_code')->withTrashed();
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'client' => $this->client->name ?? null,
            'budget_id' => $this->budget_id,
            'budget_code' => $this->budget_code,
            'budget_name' => $this->budget_name,
            'description' => $this->description,
            'ledger_code' => $this->ledger_code,
            'fiscal_calendar_code' => $this->fiscal_calendar_code,
            'budget_year' => $this->budget_year,
            'budget_status' => $this->budget_status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }

    /**
     * Total planned amount across all (non-archived) budget lines.
     *
     * @return float
     */
    public function getTotalBudgetedAmount() {
        return $this->budget_lines()->sum('budgeted_amount');
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['budget_id', 'budget_code', 'budget_name', 'description', 'client_id', 'ledger_id', 'ledger_code', 'fiscal_calendar_code', 'budget_year', 'budget_status', 'created_by', 'updated_by'])
    {
        $vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    /**
     * Renderers
     */

    public function renderShowUrl() {
        return route('budgets.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('budgets.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('budgets.restore', $this->id);
    }
}
