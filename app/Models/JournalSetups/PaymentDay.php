<?php

namespace App\Models\JournalSetups;

use App\Extenders\Models\BaseModel as Model;

class PaymentDay extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'payment_day' => $this->payment_day,
	        'week_month' => $this->week_month,
	        'day_of_week' => $this->day_of_week,
	        'day_of_month' => $this->day_of_month
	    ];
	}

	/**
	 * Relationships
	 */

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['payment_day', 'week_month', 'day_of_week', 'day_of_month', 'description'])
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
        return route('payment-days.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('payment-days.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('payment-days.restore', $this->id);
    }
}
