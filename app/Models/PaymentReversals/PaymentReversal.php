<?php

namespace App\Models\PaymentReversals;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use Carbon\Carbon;

class PaymentReversal extends Model
{
	protected $casts = [
		'bank_posting' => 'integer',
	];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	    ];
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
		'payment_reversal_id',
		'reversed_date',
		'reason',
		'status',

		// 'approved_checkbox',
		// 'approved_by',
		// 'approved_date',

		// 'posted_checkbox',
		// 'posted_by',
		// 'posted_date',

		'voucher',
		'client_bank_account_number',
		'customer_bank_account_number',
		'vendor_bank_account_number',

		'check_id',
		'check_issued_date',
		'postdated_check_status',
		'check_number',
		'amount',
		'deposit_id',
		'deposit_issued_date',
		'deposit_status',
		
		'vendor_payment_id',
		'vendor_payment_issued_date',
		'vendor',

		'customer_payment_id',
		'customer_payment_issued_date',
		'customer',

		'bank_statement_id',
		'bank_statement_issued_date',
		'bank_statement_status',

		'cash_register_id',
		'cash_register_issued_date',
		'cash_register_status',

		'bank_reconciliation_id',
		'bank_reconciliation_issued_date',
		'bank_reconciliation_status',

		'bank_reconciliation_status',
		'payment_reference',

		'vendor_payment_method',
		'customer_payment_method',
		'bank_posting',
		'bank_reason',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $payment_reversal_id = 'payment-reversal-id' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        	'payment_reversal_id' => $payment_reversal_id,
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
        return route('payment-reversals.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('payment-reversals.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('payment-reversals.restore', $this->id);
    }
}
