<?php

namespace App\Models\FiscalPeriods;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;

use App\Models\FiscalCalendars\FiscalCalendar;

class FiscalPeriod extends Model
{
	/**
	 * @Relationship
	 */

    public function parent() {
		return $this->belongsTo(FiscalCalendar::class, 'fiscal_calendar_id', 'fiscal_calendar_id')->withTrashed();
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
	public static function store($request, $item = null, $columns = ['fiscal_calendar_id', 'fiscal_period_id', 'fiscal_calendar_code', 'fiscal_year_start_date', 'fiscal_year_end_date', 'fiscal_company_name', 'fiscal_period_code', 'fiscal_period_name', 'fiscal_period_type', 'fiscal_period_start_date', 'fiscal_period_end_date', 'fiscal_month', 'fiscal_quarter', 'fiscal_period_status', 'comments','created_by', 'updated_by', 'client_id'])
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
        return route('fiscal-periods.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('fiscal-periods.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('fiscal-periods.restore', $this->id);
    }
}