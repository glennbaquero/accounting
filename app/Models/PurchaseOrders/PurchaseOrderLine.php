<?php

namespace App\Models\PurchaseOrders;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Vendors\Vendor;
use App\Models\Users\User;
use App\Models\JournalSetups\CostCenter;
use App\Models\ProductInventories\Products\Product;
use App\Models\ProductInventories\Products\Variant;

class PurchaseOrderLine extends Model
{

	protected $casts = [
        'product' => 'object',
		'variant' => 'object',
    ];

	protected $appends = [ 'removeUrl', 'existing_data', 'approveUrl', 'rejectUrl'];

	/**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'purchase_order_line_number' => $this->purchase_order_line_number,
	        'line_number' => $this->line_number,
	        'line_status' => $this->line_status,
	    ];
	}


	/**
	 * Relationships
	 */
	
	public function purchase_order() {
		return $this->belongsTo(PurchaseOrder::class, 'purchase_order_number', 'purchase_order_number')->withTrashed();
	}

    public function vendor() {
		return $this->belongsTo(Vendor::class, 'vendor_account', 'vendor_account')->withTrashed();
	}

	public function cost_center() {
		return $this->belongsTo(CostCenter::class, 'cost_center', 'code')->withTrashed();
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

    public function product_relation() {
		return $this->belongsTo(Product::class, 'product_id', 'id')->withTrashed();
	}

    public function variant_relation() {
		return $this->belongsTo(Variant::class, 'variant_id', 'id')->withTrashed();
	}
	/**
	 * Renderers
	 */
	
	public function removeUrl() {
		return route('purchase-order-lines.archive', $this->id);
	}

	public function renderApproveUrl() {
		return route('purchase-order-lines.approve', $this->id);
	}

	public function renderRejectUrl() {
		return route('purchase-order-lines.reject', $this->id);
	}

	/**
	 * Appends
	 */
	
	public function getRemoveUrlAttribute() {
		return $this->removeUrl();
	}
	
	public function getApproveUrlAttribute() {
		return $this->renderApproveUrl();
	}

	public function getRejectUrlAttribute() {
		return $this->renderRejectUrl();
	}

	public function getExistingDataAttribute() {
		return true;
	}
}
