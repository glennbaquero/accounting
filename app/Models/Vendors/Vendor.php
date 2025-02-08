<?php

namespace App\Models\Vendors;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\PurchaseOrders\PurchaseOrderLine;
use App\Models\AdminSetups\Company;

class Vendor extends Model
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
			'name' => $this->renderName(),
			'client' => $this->client ? $this->client->name : '-',
	        'vendor_account' => $this->vendor_account,
	        'first_name' => $this->first_name,
	        'last_name' => $this->last_name,
	        'email' => $this->email,
	        'company_name' => $this->company_name,
	        'mobile_number' => $this->mobile_number,
	        'display_name' => $this->display_name,
	        'website' => $this->website,
	    ];
	}

	/**
	 * Relationships
	 */
	
	public function purchase_orders() {
		return $this->hasMany(PurchaseOrder::class, 'vendor_account', 'vendor_account');
	}

	public function purchase_order_lines() {
		return $this->hasMany(PurchaseOrderLine::class, 'vendor_account', 'vendor_account');
	}

	public function client() {
		return $this->belongsTo(Client::class, 'client_id');
	}

	public function vendor_bank_accounts() {
		return $this->hasMany(VendorBankAccount::class, 'vendor_account', 'vendor_account');
	}

    public function company() {
        return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['vendor_account', 'company_name', 'title', 'first_name', 'last_name', 'middle_name', 'suffix', 'email', 'client_id', 'display_name', 'phone', 'fax', 'mobile_number', 'other', 'website', 'notes', 'phone_calling_code', 'mobile_calling_code', 'method_of_payment', 'terms_of_payment', 'payment_specification', 'tax_exempt_number', 'payment_day_id', 'payment_id', 'bank_account_number', 'use_cash_discount', 'payment_schedule', 'payment_type', 'bank_account', 'payment_type', 'address', 'type_of_trade', 'major_industry_classification', 'industry_classification_group', 'psic_sections', 'psic_divisions', 'psic_groups', 'psic_class', 'psic_subclass', 'vat_exempt_number'])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;
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
        return route('vendors.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('vendors.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('vendors.restore', $this->id);
    }

    public function renderName() {
    	return $this->first_name. ' '. $this->last_name;
	}
	
	public function renderDisplayName() {
    	return $this->title. ' '.$this->first_name. ' '. $this->last_name.' '. $this->suffix;
    }

    /**
     * Appends
     */
    
    public function getFullnameAttribute() {
    	return $this->title. ' '.$this->first_name. ' '. $this->last_name.' '. $this->suffix;
    }

}
