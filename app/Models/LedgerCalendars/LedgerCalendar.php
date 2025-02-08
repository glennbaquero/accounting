<?php

namespace App\Models\LedgerCalendars;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\Users\User;
use App\Models\FiscalCalendars\FiscalCalendar;
use App\Models\Ledgers\Ledger;

class LedgerCalendar extends Model
{
	/**
	 * @Relationship
	 */

    public function parent() {
		return $this->belongsTo(Ledger::class, 'ledger_id')->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }
    
    public function ledger() {
        return $this->hasOne(LedgerCalendar::class)->withTrashed();
    }

    public function client() {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function fiscal_calendar() {
        return $this->belongsTo(FiscalCalendar::class, 'fiscal_calendar_code', 'fiscal_calendar_code')->withTrashed();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'client' => $this->client->name,
            'ledger_id' => $this->ledger_id,            
            'ledger_code' => $this->ledger_code,
            'ledger_name' => $this->ledger_name,            
            'company_id' => $this->company_id,            
            'company_code' => $this->company_code,            
            'ledger_calendar_id' => $this->ledger_calendar_id,            
            'ledger_calendar_code' => $this->ledger_calendar_code,            
            'ledger_calendar_name' => $this->ledger_calendar_name,            
            'description' => $this->description,            
            'ledger_calendar_year' => $this->ledger_calendar_year,            
            'fiscal_calendar_code' => $this->fiscal_calendar_code,            
            'fiscal_year_start_date' => $this->fiscal_year_start_date,            
            'fiscal_year_end_date' => $this->fiscal_year_end_date,            
            'ledger_calendar_status' => $this->ledger_calendar_status,            
            'created_by' => $this->created_by,            
            'updated_by' => $this->updated_by,                        
        ];
    }	

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['ledger_id', 'ledger_code', 'ledger_name', 'client_id', 'company_code', 'ledger_calendar_id', 'ledger_calendar_code', 'ledger_calendar_code_number', 'ledger_calendar_name', 'description', 'ledger_calendar_year', 'fiscal_calendar_code', 'fiscal_year_start_date', 'fiscal_year_end_date', 'created_by', 'updated_by'])
	{
		$vars = $request->only($columns);
	    $vars['ledger_calendar_status'] = $request->filled('ledger_calendar_status');
        $vars['company_id'] = auth()->user()->company_id;
        
	    if (!$item) {
	        $item = static::create($vars);
	    } else {
	        $item->update($vars);	    }

	    return $item;
	}
	
	
	/**
	 * Renderers
	 */
		
	public function renderShowUrl() {
        return route('ledger-calendars.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('ledger-calendars.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('ledger-calendars.restore', $this->id);
    }
}