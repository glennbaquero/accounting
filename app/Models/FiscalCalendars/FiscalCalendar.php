<?php

namespace App\Models\FiscalCalendars;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\AdminSetups\Company;
use App\Models\Users\User;
use App\Models\FiscalPeriods\FiscalPeriod;
use App\Models\Ledgers\Ledger;

class FiscalCalendar extends Model
{
	/**
	 * @Relationship
	 */

    public function fiscal_periods() {
		return $this->hasMany(FiscalPeriod::class, 'fiscal_calendar_id', 'fiscal_calendar_id')->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
	}

	public function client() {
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}	

	public function company() {
		return $this->belongsTo(Company::class, 'company_id')->withTrashed();
	}	

 //    public function parent() {
	// 	return $this->belongsTo(Ledger::class, 'ledger_id', 'ledger_id')->withTrashed();
	// }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['fiscal_calendar_id', 'fiscal_calendar_code', 'fiscal_calendar_code_number', 'fiscal_calendar_name', 'client_id','fiscal_company_name', 'length_of_period', 'fiscal_year_start_date', 'fiscal_year_end_date', 'unit', 'fiscal_year_status', 'description', 'created_by', 'updated_by'])
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
        return route('fiscal-calendars.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('fiscal-calendars.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('fiscal-calendars.restore', $this->id);
    }
}