<?php

namespace App\Models\JournalSetups;

use App\Extenders\Models\BaseModel as Model;

use App\Models\PurchaseOrders\PurchaseOrder;
use App\Models\PurchaseOrders\PurchaseOrderLine;
use App\Models\Invoices\VendorInvoice;
use App\Models\Invoices\VendorInvoiceLine;

use App\Models\SalesOrders\SalesOrder;
use App\Models\Invoices\CustomerInvoice;
use App\Models\Invoices\CustomerInvoiceLine;

class CostCenter extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'code' => $this->code,
	        'name' => $this->name,
	        'active_from' => $this->active_from,
	        'active_to' => $this->active_to,
	        'status' => $this->status,
	    ];
	}

	/**
	 * Relationships
	 */
	
	public function purchase_orders() {
		return $this->hasMany(PurchaseOrder::class, 'cost_center', 'code');
	}

	public function purchase_order_lines() {
		return $this->hasMany(PurchaseOrderLine::class, 'cost_center', 'code');
	}
	
	public function vendor_invoices() {
		return $this->hasMany(VendorInvoice::class, 'cost_center', 'code');
	}

	public function vendor_invoice_lines() {
		return $this->hasMany(VendorInvoiceLine::class, 'cost_center', 'code');
	}

	public function sales_orders() {
		return $this->hasMany(SalesOrder::class, 'cost_center', 'code');
	}

	public function sales_order_lines() {
		return $this->hasMany(SalesOrderLine::class, 'cost_center', 'code');
	}

	public function customer_invoices() {
		return $this->hasMany(CustomerInvoice::class, 'cost_center', 'code');
	}

	public function customer_invoice_lines() {
		return $this->hasMany(CustomerInvoiceLine::class, 'cost_center', 'code');
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['code', 'name', 'active_from', 'active_to', 'status'])
	{
	    $vars = $request->only($columns);

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
        return route('cost-centers.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('cost-centers.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('cost-centers.restore', $this->id);
    }
}
