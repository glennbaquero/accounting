<?php

namespace App\Models\Ledgers;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;

use App\Models\LedgerCalendars\LedgerCalendar;
use App\Models\LedgerSetup\ChartOfAccount;
use App\Models\AdminSetups\Client;
use App\Models\FiscalCalendars\FiscalCalendar;
use App\Models\GeneralLedgers\AccrualPosting;
use App\Models\GeneralLedgers\GeneralLedger;

class Ledger extends Model
{
	/**
	 * @Relationship
	 */
	public function account_structure() {
		return $this->hasMany(AccountStructure::class, 'ledger_id', 'ledger_id')->withTrashed();
	}

 	public function chart_of_account() {
	 	return $this->hasOne(ChartOfAccount::class, 'id', 'chart_of_account_id')->withTrashed();
	}

	public function ledger_calendar() {
		return $this->hasOne(LedgerCalendar::class, 'id', 'ledger_calendar_id')->withTrashed();
	}

	public function fiscal_calendar() {
		return $this->belongsTo(FiscalCalendar::class, 'fiscal_calendar_id')->withTrashed();
	}

	public function clients() {
		return $this->belongsTo(Client::class, 'company_id',  'company_id')->withTrashed();
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

	public function general_ledger() {
		return $this->hasOne(GeneralLedger::class, 'ledger_id', 'id')->withTrashed();
	}

	public function accrual_postings() {
		return $this->hasMany(AccrualPosting::class, 'ledger_id')->withTrashed();
	}

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'ledger_code' => $this->ledger_code,
        ];
    }	

	/**
	 * @Setters
	 */
	
	public static function store($request, $item = null, $columns = ['ledger_id', 'ledger_code', 'ledger_name','chart_of_account_id', 'ledger_calendar_id' ,'description', 'active_from', 'active_to', 'company_id', 'client_id', 'created_by', 'updated_by'])
	{
		$vars = $request->only($columns);
	   
	
		
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	    } else {
	        $item->update($vars);
	    }

		$vars['ledger_status'] = $request->filled('ledger_status') ? true : false;

		if($vars['ledger_status']) {
			$item->setAsActive();
		}

		// clear existing registered ledger calendar
		$existing = LedgerCalendar::where('ledger_id', $item->id)->first();
		if($existing) {
			$existing->ledger_id = null;
			$existing->save();
		}
	
		// set new has one relationship
		$calendar = LedgerCalendar::find($item->ledger_calendar->id);
		$calendar->ledger_id = $item->id;
		$calendar->save();	
		
	    return $item;
	}

	public function setAsActive() {
		$client_ledgers = Ledger::where('client_id', $this->client_id)->update(['ledger_status' => false]);
		$this->update(['ledger_status' => true]);
	}
	
	
	/**
	 * Renderers
	 */
		
	public function renderShowUrl() {
        return route('ledgers.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('ledgers.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('ledgers.restore', $this->id);
    }

	/**
	 * Getters
	 */



}