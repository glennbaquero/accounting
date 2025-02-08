<?php

namespace App\Models\BillsExchanges;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class BillsExchange extends Model
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
	public static function store($request, $item = null, $columns = [
		'client_id',
		'billis_of_exchange',
		'issue_date',
		'due_from',
		'due_to',
		'principal_amount',
		'number_of_times_to_settle',
		'ammount_to_settle',
		'terms_of_payment',
		'payment_day',
		'interest_rate',
		'interest_amount',
		'terms_of_interest',
		'customer_bank_account',
		'client_bank_account',
		'voucher',
		'status',
		'discounted_on',
		'discount_rate',
		'discount_period',
		'discount_amount',
		'bank_document_id',
		'bank_facility_type_id',
		'customer_id',
		'letter_credit_sales_id',
		'letter_of_guarantee_id',
		'payment_status',
		// 'approved_by',
		// 'approved_checkbox',
		// 'approved_date',
		// 'posted_by',
		// 'posted_checkbox',
		// 'posted_date',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $bills_of_exchange = 'bills-of-exchange-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        	'bills_of_exchange' => $bills_of_exchange,
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

	public function renderPostedBy() {
		return $this->posted_by_user ? $this->posted_by_user->renderName() : ''; 
	}

	public function renderApproveUrl() {
        return route('bills-exchanges.approve', $this->id);
    }

    public function renderRedrawUrl() {
        return route('bills-exchanges.redraw', $this->id);
    }

    public function renderRemitUrl() {
        return route('bills-exchanges.remit', $this->id);
    }

    public function renderPostUrl() {
        return route('bills-exchanges.post', $this->id);
    }

    public function renderSettleUrl() {
        return route('bills-exchanges.settle', $this->id);
    }

	public function renderShowUrl() {
        return route('bills-exchanges.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bills-exchanges.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bills-exchanges.restore', $this->id);
    }
}
