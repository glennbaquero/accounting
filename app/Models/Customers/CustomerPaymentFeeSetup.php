<?php

namespace App\Models\Customers;

use App\Extenders\Models\BaseModel as Model;

class CustomerPaymentFeeSetup extends Model
{

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'fee_id' => $this->fee_id,
	        'payment_specification' => $this->payment_specification,
	        'percentage_amount' => $this->percentage_amount,
	        'fee_amount' => $this->fee_amount,
	        'minimum' => $this->minimum,
	        'maximum' => $this->maximum,
	        'from_date' => $this->from_date,
	        'to_date' => $this->to_date,
	        'minimum_fee' => $this->minimum_fee,
	        'tax_account' => $this->tax_account,
	        'days' => $this->days,
	    ];
	}

    /**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['fee_id', 'customer_payment_method_id', 'client_id', 'payment_specification', 'percentage_amount', 'fee_amount', 'minimum', 'maximum', 'from_date', 'to_date', 'minimum_fee', 'tax_account', 'days', 'waive_limit_fees'])
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
        return route('customer-payment-fee-setups.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('customer-payment-fee-setups.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('customer-payment-fee-setups.restore', $this->id);
    }
}
