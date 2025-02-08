<?php

namespace App\Models\InterestCalculations;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class InterestCalculation extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'client_id' => $this->client_id,
			'from_date' => $this->from_date,
			'to_date' => $this->to_date,
			'round_off' => $this->round_off,
			'invoice' => $this->invoice,
			'credit_note' => $this->credit_note,
			'payment' => $this->payment,
			'interest' => $this->interest,
			'customer_account' => $this->customer_account,
			'invoice_account' => $this->invoice_account,
			'invoice_date' => $this->invoice_date,
			'customer_address' => $this->customer_address,
			'customer_name' => $this->customer_name,
			'customer_contact_id' => $this->customer_contact_id,
			'customer_bank_account' => $this->customer_bank_account,
			'bills_of_exchange_id' => $this->bills_of_exchange_id,
			'posting_profile_from' => $this->posting_profile_from,
			'customer_posting_profile_id' => $this->customer_posting_profile_id,
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
		'client_id',
		'from_date',
		'to_date',
		'round_off',
		'invoice',
		'credit_note',
		'payment',
		'interest',
		'customer_account',
		'invoice_account',
		'invoice_date',
		'customer_address',
		'customer_name',
		'customer_contact_id',
		'customer_bank_account',
		'bills_of_exchange_id',
		'posting_profile_from',
		'customer_posting_profile_id',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

		if($vars['posting_profile_from'] != 'Select') {
			$vars['customer_posting_profile_id'] = null;
		}

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

	/**
	 * Renders
	 */
	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderShowUrl() {
        return route('interest-calculations.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('interest-calculations.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('interest-calculations.restore', $this->id);
    }

}
