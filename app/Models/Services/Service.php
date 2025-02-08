<?php
namespace App\Models\Services;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Client;

class Service extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
	    ];
	}


	/**
	 * Relationships
	 */
	
	public function client() {
		return $this->belongsTo(Client::class, 'client_id');
	}
	
	public function serviceTasks() {
		return $this->hasMany(ServiceTask::class);
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['name' ,'service_number' , 'service_type', 'work_type', 'description','vendor_id', 'client_id', 'unit_price'])
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
        return route('services.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('services.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('services.restore', $this->id);
	}

	public function renderCreateVariantUrl() {
        return route('services.create', ['product' => $this->id]);
	}
	
	public function renderVariantUrl() {
        return route('services.fetch', ['product' => $this->id]);
    }

	/**
	 * Getters
	 */

	public static function getData() {
		return static::where('company_id', auth()->user()->company_id)->get();
	}
}
