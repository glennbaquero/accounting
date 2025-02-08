<?php

namespace App\Models\PaymentSchedules;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class PaymentScheduleLine extends Model
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
	 * @Relationships
	 */

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'payment_schedule_id',
		'due_date',
		'duration',
		'principal_amount',
		'interest',
		'payment',
		'balance',
		'line_status',
	])
	{
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $schedule_line_id = 'schedule-line-id-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        	'schedule_line_id' => $schedule_line_id,
	        ]);
	    } else {
	        $item->update($vars);
	        $item->update([
	        	'updated_by' => $request->user()->id,
	        ]);
	    }

	    return $item;
	}

	/**
	 * Renders
	 */
	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderUpdateUrl() {
        return route('payment-schedules.update', $this->id);
    }

	public function renderShowUrl() {
        return route('payment-schedules.fetch-item', $this->id);
    }

    public function renderArchiveUrl() {
        return route('payment-schedules.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('payment-schedules.restore', $this->id);
    }
}
