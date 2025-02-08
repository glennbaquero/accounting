<?php
namespace App\Models\ProductInventories\Products;

use App\Extenders\Models\BaseModel as Model;
use App\Models\PurchaseOrders\PurchaseOrderLine;
use App\Models\PurchaseOrders\PurchaseOrderReturnLine;
use App\Models\SalesOrders\SalesOrderLine;
use App\Models\Procurements\Procurement;

class Variant extends Model
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
	
	public function parent() {
		return $this->belongsTo(Product::class, 'product_id');
	}

	public function purchase_order_lines() {
		return $this->hasMany(PurchaseOrderLine::class, 'variant_id');
	}

	public function purchase_order_return_lines() {
		return $this->hasMany(PurchaseOrderReturnLine::class, 'variant_id');
	}

	public function sales_order_lines() {
		return $this->hasMany(SalesOrderLine::class, 'variant_id');
	}

	public function procurement() {
		return $this->belongsTo(Procurement::class, 'procurement_id');
	}

	/**exit
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['product_id', 'sku', 'serial_number', 'batch_number','name', 'size', 'color', 'quantity', 'unit_price', 'variant_number', 'unit_of_measurement', 'threshold_danger', 'threshold_warning', 'procurement_id'])
	{
        $vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;
		$vars['is_available'] = $request->is_available ? true : false;

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
        return route('variants.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('variants.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('variants.restore', $this->id);
    }

	public function renderInventoryOnHandShowUrl() {
        return route('inventory-on-hands.show', $this->id);
    }

	public function renderStatus() {
		$quantity = $this->quantity;

		if($quantity == 0) {
			return ['text' => 'empty' , 'color' => 'badge-info'];
		}else{
			if($quantity <= $this->threshold_warning && $quantity > $this->threshold_danger) {
				return ['text' => 'warning' , 'color' => 'badge-warning'];
			}
	
			if($quantity < $this->threshold_danger) {
				return ['text' => 'danger' , 'color' => 'badge-danger'];
			}
		}
		
        return ['text' => 'safe' , 'color' => 'badge-success'];
    }

	public function renderOnHandValue() {
		return $this->quantity * $this->unit_price;
    }

	public function renderPoQuanity() {
		return $this->purchase_order_lines->sum('quantity');
    }

	public function renderSoQuanity() {
		return $this->sales_order_lines->sum('quantity');
    }

	public function renderPoValue() {
		return $this->renderPoQuanity() * $this->unit_price;
    }

	public function renderSoValue() {
		return  $this->renderSoQuanity() * $this->unit_price;
    }

	/**
	 * Getters
	 */

	public static function getData() {
		return static::with('procurement')->where('company_id', auth()->user()->company_id)->get();
	}
}
