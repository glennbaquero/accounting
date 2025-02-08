<?php

namespace App\Models\InterestNotes;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class InterestNote extends Model
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
			'interest_note' => $this->interest_note,
			'interest_date' => $this->interest_date,
			'interest_updated_date' => $this->interest_updated_date,
			'start_date' => $this->start_date,
			'end_date' => $this->end_date,
			'days' => $this->days,
			'description' => $this->description,
			'interest_note_voucher' => $this->interest_note_voucher,
			'fee_note' => $this->fee_note,
			'fee_write_off_amount' => $this->fee_write_off_amount,
			'fee_adjustment_status' => $this->fee_adjustment_status,
			'total' => $this->total,
			'sales_tax_amount' => $this->sales_tax_amount,
			'interest_note_status' => $this->interest_note_status,
			'adjustment_status' => $this->adjustment_status,
			'canceled' => $this->canceled,
			'block' => $this->block,
			'posted_checkbox' => $this->posted_checkbox,
			'posted_date' => $this->posted_date,
			'posted_by' => $this->posted_by,
			'posting_profile_from' => $this->posting_profile_from,
			'customer_posting_profile_id' => $this->customer_posting_profile_id,
			'customer_account' => $this->customer_account,
			'location_id' => $this->location_id,
			'name_or_description' => $this->name_or_description,
			'street' => $this->street,
			'zip_post_code' => $this->zip_post_code,
			'city' => $this->city,
			'county' => $this->county,
			'state' => $this->state,
			'country_region' => $this->country_region,
			'address' => $this->address,
			'invoice_number' => $this->invoice_number,
			'invoice_date' => $this->invoice_date,
			'invoice_due_date' => $this->invoice_due_date,
			'original_amount' => $this->original_amount,
			'amount_of_interest' => $this->amount_of_interest,
			'interest' => $this->interest,
			'interest_on_transaction_voucher' => $this->interest_on_transaction_voucher,
			'voucher' => $this->voucher,
			'written_off' => $this->written_off,
			'cost_center' => $this->cost_center,
			'department' => $this->department,
			'expense_purpose' => $this->expense_purpose,
			'posting_profile' => $this->posting_profile,
			'document' => $this->document,
			'document_status' => $this->document_status,
			'accounting_distribution' => $this->accounting_distribution,
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

	public function posted_by_user() {
		return $this->belongsTo(User::class, 'posted_by', 'id')->withTrashed();
	}

		/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'interest_note',
		'interest_date',
		'interest_updated_date',
		'start_date',
		'end_date',
		'days',
		'description',
		'interest_note_voucher',
		'fee_note',
		'fee_write_off_amount',
		'fee_adjustment_status',
		'total',
		'sales_tax_amount',
		'interest_note_status',
		'adjustment_status',
		'canceled',
		'block',
		'posting_profile_from',
		'customer_posting_profile_id',
		'customer_account',
		'location_id',
		'name_or_description',
		'street',
		'zip_post_code',
		'city',
		'county',
		'state',
		'country_region',
		'address',
		'invoice_number',
		'invoice_date',
		'invoice_due_date',
		'original_amount',
		'amount_of_interest',
		'interest',
		'interest_on_transaction_voucher',
		'voucher',
		'written_off',
		'cost_center',
		'department',
		'expense_purpose',
		'posting_profile',
		'document',
		'document_status',
		'accounting_distribution',
		// 'posted_checkbox',
		// 'posted_date',
		// 'posted_by',
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

	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderPostedBy() {
		return $this->posted_by_user ? $this->posted_by_user->renderName() : ''; 
	}

	public function renderPostUrl() {
        return route('interest-notes.post', $this->id);
    }

	public function renderShowUrl() {
        return route('interest-notes.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('interest-notes.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('interest-notes.restore', $this->id);
    }
}
