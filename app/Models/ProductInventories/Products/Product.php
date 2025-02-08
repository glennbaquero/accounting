<?php
namespace App\Models\ProductInventories\Products;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;
use App\Models\PurchaseOrders\PurchaseOrderLine;
use App\Models\Invoices\VendorInvoiceLine;

use App\Models\SalesOrders\SalesOrderLine;
use App\Models\Invoices\CustomerInvoiceLine;
use App\Models\Inventories\InventoryOnHand;

class Product extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'item_number' => $this->item_number,
	        'batch_number' => $this->batch_number,
	        'serial_number' => $this->serial_number,
	        'name' => $this->name,
	    ];
	}


	/**
	 * Relationships
	 */
	
	public function purchase_order_lines() {
		return $this->hasMany(PurchaseOrderLine::class, 'product_number', 'product_number');
	}

	public function variants() {
		return $this->hasMany(Variant::class, 'product_id', 'id');
	}

	public function vendor_invoice_lines() {
		return $this->hasMany(VendorInvoiceLine::class, 'product_number', 'product_number');
	}

	public function sales_order_lines() {
		return $this->hasMany(SalesOrderLine::class, 'product_number', 'product_number');
	}

	public function customer_invoice_lines() {
		return $this->hasMany(CustomerInvoiceLine::class, 'product_number', 'product_number');
	}

	public function client() {
		return $this->belongsTo(Client::class, 'client_id');
	}

	// public function inventoryOnHands() {
	// 	return $this->hasMany(InventoryOnHand::class, 'product_number', 'product_number');
	// }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['product_number', 'batch_number', 'serial_number', 'name',  'client_id'])
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
        return route('products.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('products.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('products.restore', $this->id);
	}

	public function renderCreateVariantUrl() {
        return route('variants.create', ['product' => $this->id]);
	}
	
	public function renderVariantUrl() {
        return route('variants.fetch', ['product' => $this->id]);
    }

	/**
	 * Getters
	 */

	public static function getData() {
		return static::where('company_id', auth()->user()->company_id)->get();
	}
}
