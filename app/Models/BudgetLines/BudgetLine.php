<?php

namespace App\Models\BudgetLines;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use App\Models\Budgets\Budget;
use App\Models\MainAccounts\MainAccount;
use App\Models\FiscalPeriods\FiscalPeriod;
use App\Models\FinancialDimensions\FinancialDimensionValue;

class BudgetLine extends Model
{
    /**
     * @Relationship
     */

    public function parent() {
        return $this->belongsTo(Budget::class, 'budget_id', 'budget_id')->withTrashed();
    }

    public function main_account_selected() {
        return $this->belongsTo(MainAccount::class, 'main_account')->withTrashed();
    }

    public function fiscal_period_selected() {
        return $this->belongsTo(FiscalPeriod::class, 'fiscal_period_id', 'fiscal_period_id')->withTrashed();
    }

    public function department_fd() {
        return $this->belongsTo(FinancialDimensionValue::class, 'department', 'financial_dimension_value_code')->withTrashed();
    }

    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['budget_id', 'main_account', 'main_account_code', 'main_account_name', 'fiscal_period_id', 'fiscal_period_code', 'department', 'cost_center', 'description', 'budgeted_amount', 'client_id', 'created_by', 'updated_by'])
    {
        $vars = is_array($request) ? \Illuminate\Support\Arr::only($request, $columns) : $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }
}
