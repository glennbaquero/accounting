<?php

namespace App\Models\JournalSetups;

use App\Models\Vendors\Vendor;

use App\Models\Customers\Customer;
use App\Extenders\Models\BaseModel as Model;

class CashDiscount extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'net_or_current' => $this->net_or_current,
			'discount_cash' => $this->discount_cash,
			'discount_percent' => $this->discount_percent,
			'month' => $this->month,
			'days' => $this->days,
	        'next_discount_code' => $this->next_discount_code,
	    ];
    }
    
	/**
	 * Relationships
	 */

	public function customer() {
		return $this->belongsTo(Customer::class, 'customer_account', 'id')->withTrashed();
	}

	public function vendor() {
		return $this->belongsTo(Vendor::class, 'vendor_account', 'id')->withTrashed();
    }
    
    /**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['next_discount_code','months','days', 'description','net_or_current','discount_offset_accounts','discount_cash','discount_percent','customer_account','vendor_account'])
	{
		$vars = $request->only($columns);

		if (! $vars['discount_percent']) {
			$vars['discount_percent'] = 0;
		} else if (! $vars['discount_cash']) {
			$vars['discount_percent'] = 0;
		}

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
        return route('cash-discounts.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('cash-discounts.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('cash-discounts.restore', $this->id);
    }
}
