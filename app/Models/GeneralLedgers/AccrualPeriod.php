<?php

namespace App\Models\GeneralLedgers;

use App\Extenders\Models\BaseModel;
use App\Models\AdminSetups\Client;

class AccrualPeriod extends BaseModel
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	    ];
	}

	/**
	* Relationships
	*/

    public function client() {
        return $this->belongsTo(Client::class, 'client_id');
    }

	public function accrual_posting() {
		return $this->belongsTo(AccrualPosting::class, 'accrual_id');
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, 
    $columns = ['accrual_id', 'period_id','fiscal_calendar_id', 'fiscal_calendar_id', 
	'fiscal_period_start_date', 'fiscal_period_end_date', 'fiscal_month', 'fiscal_period_type',
	'fiscal_quarter', 'fiscal_period_status', 'comments', 'client_id'])
	{   
        $auth = auth()->user();
	    $vars = $request->only($columns);
		$vars['company_id'] = $auth->company_id;
	
	    if (!$item) {
            $vars['created_by'] = $auth->id;
	        $item = static::create($vars);
	    } else {
            $vars['updated_by'] = $auth->id;
            $vars['updated_on'] = now();
	        $item->update($vars);
	    }

	    return $item;
	}

	/**
	 * Renderers
	 */
	public function renderShowUrl() {
        return route('accrual-postings.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('accrual-postings.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('accrual-postings.restore', $this->id);
    }
}
