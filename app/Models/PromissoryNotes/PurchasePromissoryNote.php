<?php

namespace App\Models\PromissoryNotes;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class PurchasePromissoryNote extends Model
{
    
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'stage' => $this->stage,
			'issued_date' => $this->issued_date,
			'due_from' => $this->due_from,
			'due_to' => $this->due_to,
			'principal_amount' => $this->principal_amount,
			'number_times_settle' => $this->number_times_settle,
			'amount_settle' => $this->amount_settle,
			'terms_payments' => $this->terms_payments,
			'payment_day' => $this->payment_day,
			'interest_rate' => $this->interest_rate,
			'interest_amount' => $this->interest_amount,
			'terms_interest' => $this->terms_interest,
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
	public static function store($request, $item = null, $columns = [
		'client_id',
		'promissory_note',
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
		'letter_credit_purchase_id',
		'letter_of_guarantee_id',
		'payment_status',
	])
	{
		
	    $vars = $request->only($columns);
        $vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	    	$vars['created_by_id'] = auth()->user()->id;
	        $vars['promissory_note'] = 'promissory-note-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item = static::create($vars);
	    } else {
	    	$vars['updated_by_id'] = auth()->user()->id;
	        $item->update($vars);
	    }

	    return $item;
	}

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('purchase-promissory-notes.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('purchase-promissory-notes.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('purchase-promissory-notes.restore', $this->id);
    }

	public function renderApproveUrl() {
        return route('purchase-promissory-notes.approve', $this->id);
    }

    public function renderRedrawUrl() {
        return route('purchase-promissory-notes.redraw', $this->id);
    }

    public function renderRemitUrl() {
        return route('purchase-promissory-notes.remit', $this->id);
    }

    public function renderPostUrl() {
        return route('purchase-promissory-notes.post', $this->id);
    }

    public function renderSettleUrl() {
        return route('purchase-promissory-notes.settle', $this->id);
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
