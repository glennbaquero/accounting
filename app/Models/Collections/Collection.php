<?php

namespace App\Models\Collections;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class Collection extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'company_id' => $this->company_id,
			'client_id' => $this->client_id,
			'collection_id' => $this->collection_id,
			'collection_date' => $this->collection_date,
			'sent_date' => $this->sent_date,
			'due_date' => $this->due_date,
			'amount_to_settle' => $this->amount_to_settle,
			'customer_account' => $this->customer_account,
			'invoice_account' => $this->invoice_account,
			'invoice_date' => $this->invoice_date,
			'customer_address' => $this->customer_address,
			'customer_name' => $this->customer_name,
			'customer_contact_id' => $this->customer_contact_id,
			'customer_bank_account' => $this->customer_bank_account,
			'client_bank_account' => $this->client_bank_account,
			'description' => $this->description,
			'bills_exchange_id' => $this->bills_exchange_id,
			'bills_exchange_status' => $this->bills_exchange_status,
			'voucher' => $this->voucher,
			'collection_status' => $this->collection_status,
			'closed_checkbox' => $this->closed_checkbox,
			'closed_date' => $this->closed_date,
			'closed_by' => $this->closed_by,
			'posted_checkbox' => $this->posted_checkbox,
			'posted_date' => $this->posted_date,
			'posted_by' => $this->posted_by,
			'activity_type' => $this->activity_type,
			'activity_start_date' => $this->activity_start_date,
			'activity_date' => $this->activity__date,
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

	public function closed_by_user() {
		return $this->belongsTo(User::class, 'closed_by', 'id')->withTrashed();
	}

	public function write_off_issued_by_user() {
		return $this->belongsTo(User::class, 'write_off_issued_by', 'id')->withTrashed();
	}

	public function nsf_payment_issued_by_user() {
		return $this->belongsTo(User::class, 'nsf_payment_issued_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'collection_id',
		'collection_date',
		'sent_date',
		'due_date',
		'amount_to_settle',
		'customer_account',
		'invoice_account',
		'invoice_date',
		'customer_address',
		'customer_name',
		'customer_contact_id',
		'customer_bank_account',
		'client_bank_account',
		'description',
		'bills_exchange_id',
		'bills_exchange_status',
		'voucher',
		'collection_status',
		'activity_type',
		'activity_start_date',
		'activity_date',
		'write_off_status',
		'write_off_date',
		'write_off_issued_by',
		'write_off_description',
		'reverse_write_off_date',
		'nsf_payment_status',
		'nsf_payment_date',
		'nsf_payment_issued_by',
		'nsf_payment_description',
		'reverse_nsf_payment_date',
		// 'closed_checkbox',
		// 'closed_date',
		// 'closed_by',
		// 'posted_checkbox',
		// 'posted_date',
		// 'posted_by',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $collection_id = 'collection-id-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        	'collection_id' => $collection_id,
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

	public function renderPostedBy() {
		return $this->posted_by_user ? $this->posted_by_user->renderName() : ''; 
	}

	public function renderCloseddBy() {
		return $this->closed_by_user ? $this->closed_by_user->renderName() : ''; 
	}

	public function renderPostUrl() {
        return route('collections.post', $this->id);
    }

    public function renderCloseUrl() {
        return route('collections.close', $this->id);
    }

	public function renderShowUrl() {
        return route('collections.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('collections.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('collections.restore', $this->id);
    }

    public function renderWriteOffUrl() {
        return route('collections.writeOff', $this->id);
    }
}
