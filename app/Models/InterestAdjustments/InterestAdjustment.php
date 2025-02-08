<?php

namespace App\Models\InterestAdjustments;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class InterestAdjustment extends Model
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
	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	public function approved_by_user() {
		return $this->belongsTo(User::class, 'approved_by', 'id')->withTrashed();
	}

	public function waived_by_user() {
		return $this->belongsTo(User::class, 'waived_by', 'id')->withTrashed();
	}

	public function reinstated_by_user() {
		return $this->belongsTo(User::class, 'reinstated_by', 'id')->withTrashed();
	}

	public function reserved_by_user() {
		return $this->belongsTo(User::class, 'reserved_by', 'id')->withTrashed();
	}

	public function posted_by_user() {
		return $this->belongsTo(User::class, 'reserved_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'interest_adjustment_id',
		'interest_adjustment_date',
		'start_date',
		'end_date',
		'customer_account',
		'customer',
		'transaction_date',
		'transaction_type',
		'interest_note_id',
		'interest_note_amount',
		'waived_amount',
		'unpaid_balance',
		'fee_amount',
		'interest_adjustment_status',
		'voucher',
		'write_off_amount',
		'fee_write_off_amount',

		'cost_center',
		'department',
		'expense_purpose',
		'posting_profile',
		'document',
		'document_status',
		'accounting_distribution',
	])
	{
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $interest_adjustment_id = 'interest-adjustment-id' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        	'interest_adjustment_id' => $interest_adjustment_id,
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

	public function renderApprovedBy() {
		return $this->approved_by_user ? $this->approved_by_user->renderName() : ''; 
	}

	public function renderWaivedBy() {
		return $this->waived_by_user ? $this->waived_by_user->renderName() : ''; 
	}

	public function renderReinstatedBy() {
		return $this->reinstated_by_user ? $this->reinstated_by_user->renderName() : ''; 
	}

	public function renderReservedBy() {
		return $this->reserved_by_user ? $this->reserved_by_user->renderName() : ''; 
	}

	public function renderPostedBy() {
		return $this->posted_by_user ? $this->posted_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('interest-adjustments.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('interest-adjustments.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('interest-adjustments.restore', $this->id);
    }

    public function renderActionUrl($action) {
        return route('interest-adjustments.action', ['id' => $this->id, 'action' => $action]);
    }

}
