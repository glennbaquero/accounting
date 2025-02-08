<?php

namespace App\Models\CustomerPaymentMethods;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\AdminSetups\Client;
use App\Models\MainAccounts\MainAccount;

use Carbon\Carbon;

class CustomerPaymentMethod extends Model
{
	protected $casts = [
		'bank_posting_profile' => 'integer',
	];
	
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'client_id' => $this->client_id,
			'method_of_payment' => $this->method_of_payment,
			'method_of_payment_id' => $this->method_of_payment_id,
			'description' => $this->description,
			'payment_status' => $this->payment_status,
			'postdated_check_status' => $this->postdated_check_status,
			'account_type' => $this->account_type,
			'payment_account' => $this->payment_account,
			'main_account_id' => $this->main_account_id,
			'postdated_check_clearing_posting' => $this->postdated_check_clearing_posting,
			'bank_posting_profile' => $this->bank_posting_profile,
			'journal_name' => $this->journal_name,
			'cost_center' => $this->cost_center,
			'department' => $this->department,
			'expense_purpose' => $this->expense_purpose,
	    ];
	}

	/**
	 * Relationships
	 */
	public function main_account() {
		return $this->belongsTo(MainAccount::class, 'payment_account', 'main_account_id')->withTrashed();
	}

	public function client() {
		return $this->belongsTo(Client::class)->withTrashed();
	}

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
		'method_of_payment',
		'method_of_payment_id',
		'description',
		'payment_status',
		'postdated_check_status',
		'account_type',
		'main_account_id',
		'postdated_check_clearing_posting',
		'bank_posting_profile',
		'journal_name',
		'cost_center',
		'department',
		'expense_purpose',
		'document',
		'method_of_payment_id',
		'payment_account',
		'postdated_check_account',
		'not_sufficient_fund_account',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	    	// $method_of_payment_id = 'customer-payment-method-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	// 'method_of_payment_id' => $method_of_payment_id,
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
        return route('customer-payment-methods.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('customer-payment-methods.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('customer-payment-methods.restore', $this->id);
    }
}
