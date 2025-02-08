<?php

namespace App\Models\Customers;

use App\Extenders\Models\BaseModel as Model;

use App\Models\SalesOrders\SalesOrder;
use App\Models\SalesOrders\SalesOrderLine;

class Customer extends Model
{
    protected $appends = ['fullname'];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'customer_account' => $this->customer_account,
	        'first_name' => $this->first_name,
	        'last_name' => $this->last_name,
	        'email' => $this->email,
	        'company' => $this->company,
	        'mobile_number' => $this->mobile_number,
	        'display_name' => $this->display_name,
	        'website' => $this->website,
	    ];
	}

	/**
	 * Relationships
	 */
	
	public function sales_orders() {
		return $this->hasMany(SalesOrder::class, 'customer_account', 'customer_account');
	}

	public function purchase_order_lines() {
		return $this->hasMany(PurchaseOrderLine::class, 'customer_account', 'customer_account');
	}

	public function parent_customer_accounts() {
		return $this->hasMany(Customer::class, 'parent_customer_account', 'customer_account');
	}

	public function parent_customer_account() {
		return $this->belongsTo(Customer::class, 'parent_customer_account', 'customer_account')->withTrashed();
	}

	public function bill_parent_customer_accounts() {
		return $this->hasMany(Customer::class, 'bill_parent_customer_account', 'customer_account');
	}

	public function bill_parent_customer_account() {
		return $this->belongsTo(Customer::class, 'bill_parent_customer_account', 'customer_account')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['parent_customer_account', 'bill_parent_customer_account', 'customer_account', 'title', 'first_name', 'last_name', 'middle_name', 'suffix', 'email', 'company', 'display_name', 'phone', 'fax', 'mobile_number', 'phone', 'other', 'website', 'notes', 'phone_calling_code', 'mobile_calling_code', 'billing_province', 'billing_city', 'billing_street', 'billing_country', 'billing_postal_code', 'shipping_province', 'shipping_city', 'shipping_street', 'shipping_country', 'shipping_postal_code', 'language', 'tax_register_number', 'method_of_payment', 'terms_of_payment', 'payment_specification', 'tax_exempt_number', 'payment_days', 'payment_id', 'bank_account_number', 'use_cash_discount', 'payment_schedule', 'payment_type', 'bank_account', 'payment_type', 'type_of_trade', 'major_industry_classification', 'industry_classification_group', 'psic_sections', 'psic_divisions', 'psic_groups', 'psic_class', 'psic_subclass', 'vat_exempt_number'])
	{
		
	    $vars = $request->only($columns);
	    $vars['is_sub_customer'] = $request->filled('is_sub_customer');
	    $vars['peza_checkbox'] = $request->filled('peza_checkbox');

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
        return route('customers.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('customers.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('customers.restore', $this->id);
    }

    public function renderName() {
    	return $this->title. ' '.$this->first_name. ' '. $this->last_name.' '. $this->suffix;
    }

    public function renderShippingAddress() {
    	return $this->shipping_street. ' '. $this->shipping_city.' '.$this->shipping_province.' '.$this->shipping_postal_code;
    }

    /**
     * Appends
     */
    
    public function getFullnameAttribute() {
    	return $this->title. ' '.$this->first_name. ' '. $this->last_name.' '. $this->suffix;
    }
}
