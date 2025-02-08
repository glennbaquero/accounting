<?php

namespace App\Models\Charges;

use App\Extenders\Models\BaseModel as Model;

use App\Models\VendorPaymentMethods\VendorPaymentMethod;
use App\Models\CustomerPaymentMethods\CustomerPaymentMethod;
use App\Models\Procurements\Procurement;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Variant;
use App\Models\Services\Service;
use App\Models\Services\ServiceTask;
use App\Models\MainAccounts\MainAccount;
use App\Models\AdminSetups\Client;

class Charge extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
			'id' => $this->id,
			'name' => $this->name,
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

	public function procurement() 
	{
		return $this->belongsTo(Procurement::class)->withTrashed();
	}

	public function product() 
	{
		return $this->belongsTo(Product::class)->withTrashed();
	}

	public function variant() 
	{
		return $this->belongsTo(Variant::class)->withTrashed();
	}

	public function service() 
	{
		return $this->belongsTo(Service::class)->withTrashed();
	}

	public function serviceTask() 
	{
		return $this->belongsTo(ServiceTask::class)->withTrashed();
	}
	
	public function mainAccount() 
	{
		return $this->belongsTo(MainAccount::class)->withTrashed();
	}
	
	public function client() 
	{
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}


	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'name',
		'description',
		'client_id',
		'level',
		'applied_to',
		'vendor_payment_method_id',
		'customer_payment_method_id',
		'procurement_id',
		'product_id',
		'variant_id',
		'service_id',
		'service_task_id',
		'delivery_type',
		'charge_category',
		'charge_value',
		'from_amount',
		'to_amount',
		'quantity',
		'charge_percentage',
		'main_account_id',
		'valid_from',
		'valid_to',
	])
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
        return route('charges.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('charges.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('charges.restore', $this->id);
    }

}
