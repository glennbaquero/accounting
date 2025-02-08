<?php

namespace App\Models\AdminSetups;

use App\Extenders\Models\BaseModel as Model;
use App\Models\AdminSetups\Department;
use App\Models\Users\User;

class Position extends Model
{

	protected $appends = ['user_count'];
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */

	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'name' => $this->name,
	        'type' => $this->type,
	        'status' => $this->status,
	        'active_from' => $this->active_from,
	        'active_to' => $this->active_to,
	        'created_at' => $this->created_at,
	    ];
	}

	/**
	* Relationships
	*/
	
	public function department() {
		return $this->belongsTo(Department::class, 'department_id');
	}

	public function users() {
		return $this->hasMany(User::class);
	}

	/**
     *  Attributes
     */

    public function getUserCountAttribute() {
        return $this->users->count();
    }



	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['code', 'name', 'type', 'status', 'company_id', 'department_id', 'active_from', 'active_to'])
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
        return route('positions.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('positions.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('positions.restore', $this->id);
    }

	public function withCompanyRenderShowUrl() {
        return route('positions.show', [$this->id, $this->company_id]);
    }

	public function renderCompanyName() {
		return $this->department->company ?  $this->department->company->name : '---';
	}

	

    /**
     * Appends
     */
}
