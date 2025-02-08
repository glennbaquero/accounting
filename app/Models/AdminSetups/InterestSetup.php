<?php

namespace App\Models\AdminSetups;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class InterestSetup extends Model
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
			'interest_code' => $this->interest_code,
			'interest_name' => $this->interest_name,
			'description' => $this->description,
			'interest_type' => $this->interest_type,
			'grace_period' => $this->grace_period,
			'effective_date' => $this->effective_date,
			'expiration_date' => $this->expiration_date,
			'calculate_interest_every' => $this->calculate_interest_every,
			'interest_earning_debit' => $this->interest_earning_debit,
			'interest_range_by' => $this->interest_range_by,
			'interest_amount' => $this->interest_amount,
			'minimum_interest_amount' => $this->minimum_interest_amount,
			'maximum_interest_amount' => $this->maximum_interest_amount,
			'charge_customer_when_interest_exceeds' => $this->charge_customer_when_interest_exceeds,
			'fee_amount' => $this->fee_amount,
			'fee_account' => $this->fee_account,
			'sales_tax' => $this->sales_tax,
			'interest_payment_credit_account' => $this->interest_payment_credit_account,
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
		'interest_code',
		'interest_name',
		'description',
		'interest_type',
		'grace_period',
		'effective_date',
		'expiration_date',
		'calculate_interest_every',
		'interest_earning_debit',
		'interest_range_by',
		'interest_amount',
		'minimum_interest_amount',
		'maximum_interest_amount',
		'charge_customer_when_interest_exceeds',
		'fee_amount',
		'fee_account',
		'sales_tax',
		'interest_payment_credit_account',
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
        return route('interest-setups.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('interest-setups.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('interest-setups.restore', $this->id);
    }
}
