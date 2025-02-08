<?php

namespace App\Models\JournalSetups;

use App\Extenders\Models\BaseModel as Model;

use App\Models\PurchaseOrders\PurchaseOrder;

use App\Models\SalesOrders\SalesOrder;

class PaymentMethod extends Model
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
	
	public function purchase_orders() {
		return $this->hasMany(PurchaseOrder::class, 'method_of_payment', 'id');
	}

	public function sales_orders() {
		return $this->hasMany(SalesOrder::class, 'method_of_payment', 'id');
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['name'])
	{
	    $vars = $request->only($columns);
		$vars['is_credit_card'] = $request->filled('is_credit_card');
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
        return route('payment-methods.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('payment-methods.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('payment-methods.restore', $this->id);
    }
}
