<?php

namespace App\Models\AdminSetups;

use App\Extenders\Models\BaseModel;

use App\Models\AdminSetups\Department;

class Company extends BaseModel
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

	public function departments() {
		return $this->hasMany(Department::class);
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['name',])
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
        return route('companies.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('companies.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('companies.restore', $this->id);
    }

    public function renderDepartmentCount() {
    	return $this->departments->count();
    }

	public function renderPositionCount() {
    	return $this->departments->sum('position_count');
    }


	public function renderUserCount() {
    	return $this->departments->sum('user_count');
    }

}
