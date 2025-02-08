<?php

namespace App\Models\PaymentFees;

use App\Extenders\Models\BaseModel as Model;

use App\Models\AdminSetups\ClientBankAccount;
use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\AdminSetups\Client;

class PaymentFee extends Model
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
    			'fee_id' => $this->fee_id,
    			'remittance_type' => $this->remittance_type,
    			'client_bank_account_id' => $this->client_bank_account_id,
    			'vendor_payment_method_id' => $this->vendor_payment_method_id,
    			'customer_payment_method_id' => $this->customer_payment_method_id,
    			'payment_specification' => $this->payment_specification,
    			'payment_date' => $this->payment_date,
    			'due_date' => $this->due_date,
    		];
    	}


    	/**
    	 * Relationships
    	 */
    	
    	public function vendorPaymentMethod() 
    	{
    		return $this->belongsTo(VendorPaymentMethod::class)->withTrashed();
    	}

    	public function customerPaymentMethod() 
    	{
    		return $this->belongsTo(CustomerPaymentMethod::class)->withTrashed();
    	}

    	public function clientBankAccount() 
    	{
    		return $this->belongsTo(ClientBankAccount::class)->withTrashed();
    	}

    	public function client() 
    	{
    		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    	}


    	/**
    	 * @Setters
    	 */
    	public static function store($request, $item = null, $columns = [ 'fee_id', 'fee_amount', 'remittance_type', 'client_bank_account_id', 'vendor_payment_method_id', 'customer_payment_method_id', 'payment_specification', 'payment_date', 'due_date', 'client_id', ])
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

    	public function renderShowUrl() {
            return route('payment-fees.show', $this->id);
        }

        public function renderArchiveUrl() {
            return route('payment-fees.archive', $this->id);
        }

        public function renderRestoreUrl() {
            return route('payment-fees.restore', $this->id);
        }
}
