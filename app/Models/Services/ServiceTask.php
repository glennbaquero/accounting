<?php

namespace App\Models\Services;

use App\Extenders\Models\BaseModel as Model;

class ServiceTask extends Model
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
	
	public function belongToService() {
		return $this->belongsTo(Service::class, 'service_id')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['service_task', 'service_id', 'service', 'rpm_method', 'service_responsible', 'period', 'base_hour', 'unit_price', 'description'])
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
        return route('service-tasks.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('service-tasks.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('service-tasks.restore', $this->id);
	}
}
