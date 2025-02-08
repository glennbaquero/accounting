<?php

namespace App\Models\Customers;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use App\Models\BillsExchanges\BillsExchange;

class CustomerBankRemittance extends Model
{
        /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'billis_of_exchange' => $this->billis_of_exchange,
			'issue_date' => $this->issue_date,
			'due_from' => $this->due_from,
			'due_to' => $this->due_to,
			'principal_amount' => $this->principal_amount,
			'number_of_times_to_settle' => $this->number_of_times_to_settle,
			'ammount_to_settle' => $this->ammount_to_settle,
			'terms_of_payment' => $this->terms_of_payment,
			'payment_day' => $this->payment_day,
			'interest_rate' => $this->interest_rate,
			'interest_amount' => $this->interest_amount,
			'terms_of_interest' => $this->terms_of_interest,
			'customer_bank_account' => $this->customer_bank_account,
			'client_bank_account' => $this->client_bank_account,
			'voucher' => $this->voucher,
			'status' => $this->status,
			'approved_by' => $this->approved_by,
			'approved_checkbox' => $this->approved_checkbox,
			'approved_date' => $this->approved_date,
			'posted_by' => $this->posted_by,
			'posted_checkbox' => $this->posted_checkbox,
			'posted_date' => $this->posted_date,
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

	public function posted_by_user() {
		return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, BillsExchange $bills_of_exchange)
	{
		$item = static::create([
			'bills_exchange_id' => $bills_of_exchange->id,
			'company_id' => $bills_of_exchange->company_id,
			'bills_exchange_id' => $bills_of_exchange->bills_exchange_id,
			'client_id' => $bills_of_exchange->client_id,
			'bills_of_exchange' => $bills_of_exchange->bills_of_exchange,
			'issue_date' => $bills_of_exchange->issue_date,
			'due_from' => $bills_of_exchange->due_from,
			'due_to' => $bills_of_exchange->due_to,
			'principal_amount' => $bills_of_exchange->principal_amount,
			'number_of_times_to_settle' => $bills_of_exchange->number_of_times_to_settle,
			'ammount_to_settle' => $bills_of_exchange->ammount_to_settle,
			'terms_of_payment' => $bills_of_exchange->terms_of_payment,
			'payment_day' => $bills_of_exchange->payment_day,
			'interest_rate' => $bills_of_exchange->interest_rate,
			'interest_amount' => $bills_of_exchange->interest_amount,
			'terms_of_interest' => $bills_of_exchange->terms_of_interest,
			'customer_bank_account' => $bills_of_exchange->customer_bank_account,
			'client_bank_account' => $bills_of_exchange->client_bank_account,
			'voucher' => $bills_of_exchange->voucher,
			'bills_of_exchange_stage' => $bills_of_exchange->bills_of_exchange_stage,
			'status' => $bills_of_exchange->status,
			'approved_by' => $bills_of_exchange->approved_by,
			'approved_checkbox' => $bills_of_exchange->approved_checkbox,
			'approved_date' => $bills_of_exchange->approved_date,
			'posted_by' => $bills_of_exchange->posted_by,
			'posted_checkbox' => $bills_of_exchange->posted_checkbox,
			'posted_date' => $bills_of_exchange->posted_date,
			'bills_of_exchange_stage' => $bills_of_exchange->bills_of_exchange_stage,
			'created_by' => $request->user()->id,
			'updated_by' => $request->user()->id,
		]);

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

	public function renderPostedBy() {
		return $this->posted_by_user ? $this->posted_by_user->renderName() : ''; 
	}
}
