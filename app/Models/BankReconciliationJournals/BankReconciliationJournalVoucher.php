<?php

namespace App\Models\BankReconciliationJournals;

use App\Extenders\Models\BaseModel as Model;

use App\Models\JournalLines\VendorPaymentJournalVoucher;
use App\Models\FinancialDimensions\FinancialDimensionValue;
use App\Models\Users\User;
use Carbon\Carbon;

class BankReconciliationJournalVoucher extends Model
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
			'customer_payment_id' => $this->customer_payment_id,
			'customer_payment_issued_date' => $this->customer_payment_issued_date,
			'customer_name' => $this->customer_name,
			'vendor_payment_id' => $this->vendor_payment_id,
			'vendor_payment_issued_date' => $this->vendor_payment_issued_date,
			'vendor_name' => $this->vendor_name,
			'check_id' => $this->check_id,
			'check_amount' => $this->check_amount,
			'deposit_id' => $this->deposit_id,
			'payment_reference' => $this->payment_reference,
			'created_at' => $this->created_at,
	    ];
	}

	/**
	 * @Relationships
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
		'customer_payment_id',
		'customer_payment_issued_date',
		'customer_name',
		'customer_payment_method',
		'vendor_payment_id',
		'vendor_payment_issued_date',
		'vendor_name',
		'vendor_payment_method',
		'client_bank_account_number',
		'check_id',
		'check_number',
		'check_amount',
		'deposit_id',
		'payment_reference',
		'bank_account_transaction_id',
		'bank_reconciliation_id',
		'reconcile_date',
		'matched_checkbox',
		'statement_adjustment_id',
		'cash_register_adjustment_id',
		'bank_statement_id',
		'bank_posting',
		'bank_reason',
		'voucher_number'
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

	public function renderUpdateUrl() {
        return route('bank-reconciliation-journal-vouchers.update', $this->id);
    }

	public function renderShowUrl() {
        return route('bank-reconciliation-journal-vouchers.fetch-item', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-reconciliation-journal-vouchers.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-reconciliation-journal-vouchers.restore', $this->id);
    }

}
