<?php

namespace App\Models\AccountStructures;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;

class AccountStructure extends Model
{
	/**
	 * @Relationship
	 */

    public function parent() {
		return $this->belongsTo(Ledger::class, 'ledger_id', 'ledger_id')->withTrashed();
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
	public static function store($request, $item = null, $columns = ['financial_dimension_value_code', 'ledger_account_structure_id', 'ledger_account_structure_code','ledger_account_structure_code_number', 'ledger_account_structure_name','ledger_id', 'ledger_code', 'ledger_name', 'ledger_chart_of_accounts', 'chart_of_accounts_id','ledger_fiscal_calendar', 'company_name', 'description', 'main_account_from', 'main_account_to', 'active_from', 'active_to', 'created_by', 'updated_by', 'client_id'])
	{
		$vars = $request->only($columns);
	    $vars['ledger_status'] = $request->filled('ledger_status');
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
        return route('account-structures.show', $this->id);
    }

	public function renderShowLedgerUrl() {
        return route('account-structures.show-ledger', $this->id);
    }

	public function renderShowCoaUrl() {
        return route('account-structures.show-coa', $this->id);
    }

    public function renderArchiveUrl() {
        return route('account-structures.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('account-structures.restore', $this->id);
    }
}