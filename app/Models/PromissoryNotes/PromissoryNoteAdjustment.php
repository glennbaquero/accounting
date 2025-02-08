<?php

namespace App\Models\PromissoryNotes;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class PromissoryNoteAdjustment extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'promissory_note' => $this->promissory_note,
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
	public function created_by() {
		return $this->belongsTo(User::class, 'created_by_id', 'id')->withTrashed();
	}

	public function updated_by() {
		return $this->belongsTo(User::class, 'updated_by_id', 'id')->withTrashed();
	}

	public function approved_by() {
		return $this->belongsTo(User::class, 'approved_by_id', 'id')->withTrashed();
	}

	public function posted_by() {
		return $this->belongsTo(User::class, 'posted_by_id', 'id')->withTrashed();
	}
	/**
	 * @Setters
	 */
	public static function store($request, PurchasePromissoryNote $promissory_note)
	{
		$item = static::create([
			'bills_exchange_id' => $promissory_note->id,
			'company_id' => $promissory_note->company_id,
			'bills_exchange_id' => $promissory_note->bills_exchange_id,
			'client_id' => $promissory_note->client_id,
			'promissory_note' => $promissory_note->promissory_note,
			'issue_date' => $promissory_note->issue_date,
			'due_from' => $promissory_note->due_from,
			'due_to' => $promissory_note->due_to,
			'principal_amount' => $promissory_note->principal_amount,
			'number_of_times_to_settle' => $promissory_note->number_of_times_to_settle,
			'ammount_to_settle' => $promissory_note->ammount_to_settle,
			'terms_of_payment' => $promissory_note->terms_of_payment,
			'payment_day' => $promissory_note->payment_day,
			'interest_rate' => $promissory_note->interest_rate,
			'interest_amount' => $promissory_note->interest_amount,
			'terms_of_interest' => $promissory_note->terms_of_interest,
			'vendor_bank_account' => $promissory_note->vendor_bank_account,
			'client_bank_account' => $promissory_note->client_bank_account,
			'voucher' => $promissory_note->voucher,
			'stage' => $promissory_note->stage,
			'status' => $promissory_note->status,
			'approved_by' => $promissory_note->approved_by,
			'approved_checkbox' => $promissory_note->approved_checkbox,
			'approved_date' => $promissory_note->approved_date,
			'posted_by' => $promissory_note->posted_by,
			'posted_checkbox' => $promissory_note->posted_checkbox,
			'posted_date' => $promissory_note->posted_date,
			'created_by' => $request->user()->id,
			'updated_by' => $request->user()->id,
		]);

	    return $item;
	}

	/**
	 * Renders
	 */
	public function renderCreatedBy() {
		return $this->created_by ? $this->created_by->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by ? $this->updated_by->renderName() : ''; 
	}

	public function renderApprovedBy() {
		return $this->approved_by ? $this->approved_by->renderName() : ''; 
	}

	public function renderPostedBy() {
		return $this->posted_by ? $this->posted_by->renderName() : ''; 
	}

}
