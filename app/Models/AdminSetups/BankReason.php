<?php

namespace App\Models\AdminSetups;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use Carbon\Carbon;

class BankReason extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'reason_code' => $this->reason_code,
			'default_comment' => $this->default_comment,
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

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'reason_code',
		'default_comment',
		'bank',
		'purpose_code',
		'cancellation_reason',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        ]);
	    } else {
	        $item->update($vars);
	        $item->update([
	        	'updated_by' => $request->user()->id,
	        ]);
	    }

	    return $item;
	}

	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('bank-reasons.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-reasons.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-reasons.restore', $this->id);
    }
}
