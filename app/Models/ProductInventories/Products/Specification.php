<?php

namespace App\Models\ProductInventories\Products;

use App\Extenders\Models\BaseModel as Model;

use App\Models\AdminSetups\Client;

class Specification extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	        'product_specification' => $this->product_specification,
	        'specification_name' => $this->specification_name,
	    ];
	}

	/**
	 * Relationship
	 */
	
	public function client() {
		return $this->belongsTo(Client::class, 'client_id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['product_specification', 'client_id', 'specification_name', 'description', 'construction', 'fibre', 'dye_method', 'gauge', 'size', 'average_density', 'tufted_weight', 'production_weight', 'total_thickness', 'secondary_backing', 'recommended_installation', 'yarn', 'variant_id'])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	    	$vars['created_by'] = auth()->user()->fullname;
	        $item = static::create($vars);
	    } else {
	    	$vars['updated_by'] = auth()->user()->fullname;
	        $item->update($vars);
	    }

	    return $item;
	}

	/**
	 * Renderers
	 */
	
	public function renderShowUrl() {
        return route('specifications.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('specifications.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('specifications.restore', $this->id);
	}
}
