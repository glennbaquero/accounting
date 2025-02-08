<?php

namespace App\Models\Inventories;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Users\User;
use App\Models\AdminSetups\Client;
use App\Models\ProductInventories\Products\Variant;

class InventoryOnHand extends Model
{
    
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'inventory_on_hand_number' => $this->inventory_on_hand_number,
	        'item_number' => $this->item_number,
	        'product_name' => $this->product_name,
	        'size' => $this->size,
	        'color' => $this->color,
	        'item_unit' => $this->item_unit,
	        'ordered' => $this->ordered,
	        'ordered_quantity' => $this->ordered_quantity,
	        'physical_inventory' => $this->physical_inventory,
	        'received' => $this->received,
	        'received_quantity' => $this->received_quantity,
	        'posted_quantity' => $this->posted_quantity,
	        'total_available' => $this->total_available,
	        'physical_cost_amount' => $this->physical_cost_amount,
	        'financial_cost_amount' => $this->financial_cost_amount,
	        'closed_inventory_checkbox' => $this->closed_inventory_checkbox,
	        'created_by' => $this->created_by,
	    ];
	}

	/**
	 * Relationships
	 */
	public function client() 
	{
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

	public function variant() 
	{
		return $this->belongsTo(Variant::class, 'item_number', 'id')->withTrashed();
	}


	public function created_by_user() {
	    return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
	    return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['inventory_on_hand_number', 'item_number', 'product_name', 'size', 'color', 'item_unit', 'ordered_quantity', 'physical_inventory', 'received_quantity', 'posted_quantity', 'total_available', 'physical_cost_amount', 'financial_cost_amount', 'created_by', 'updated_by', 'updated_at', 'client_id'])
	{
	  	$vars = $request->only($columns);
	    $vars['ordered'] = $request->filled('ordered');
	    $vars['received'] = $request->filled('received');
	    $vars['closed_inventory_checkbox'] = $request->filled('closed_inventory_checkbox');
	    $vars['company_id'] = auth()->user()->company_id;
	    
	    if (!$item) {
	    	$vars['created_by'] = auth()->user()->id;
	        $item = static::create($vars);
	    } else {
	    	$vars['updated_by'] = auth()->user()->id;
	        $item->update($vars);
	    }

	    return $item;
	}


	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('inventory-on-hands.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('inventory-on-hands.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('inventory-on-hands.restore', $this->id);
    }


}
