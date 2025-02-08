<?php

namespace App\Models\JournalSetups;

use App\Extenders\Models\BaseModel as Model;

use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\SalesOrders\SalesOrder;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;

class TermsOfPayment extends Model
{
	protected $casts = [
       'ledger_posting_profile' => 'integer',
       'payment_day' => 'integer',
       'payment_schedule' => 'integer',
   	];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'terms_of_payments' => $this->terms_of_payments,
	        'months' => $this->months,
	        'days' => $this->days,
	        'payment_method_id' => $this->payment_method ? $this->payment_method : null,
	        'payment_day' => $this->payment_day ? $this->payment_day : null,
	        'payment_schedule' => $this->payment_schedule ? $this->payment_schedule : null,
	        'cutoff_day' => $this->cutoff_day,
	        'ledger_posting_profile' => $this->ledger_posting_profile ? $this->ledger_posting_profile : null,
	    ];
	}

	/**
	 * Relationships
	 */
	
	public function purchase_orders() {
		return $this->hasMany(PurchaseOrder::class, 'terms_of_payment', 'terms_of_payment');
	}

	public function payment_method() {
		return $this->belongsTo(VendorPaymentMethod::class, 'payment_method_id', 'id')->withTrashed();
	}

	public function payment_day_elo() {
		return $this->belongsTo(PaymentDay::class, 'payment_day', 'id')->withTrashed();
	}

	public function sales_orders() {
		return $this->hasMany(SalesOrder::class, 'terms_of_payment', 'terms_of_payment');
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['terms_of_payment', 'payment_method_id', 'months', 'days', 'payment_day', 'payment_schedule', 'cutoff_day', 'ledger_posting_profile', 'description'])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	    } else {
	        $item->update($vars);
	    }

	    return $item;
	}

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('terms.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('terms.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('terms.restore', $this->id);
    }
}
