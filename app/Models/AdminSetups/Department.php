<?php

namespace App\Models\AdminSetups;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use App\Models\AdminSetups\Company;

class Department extends Model
{

	protected $appends = ['position_count', 'user_count'];

    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'name' => $this->name,
            'head' => $this->head ? $this->head->renderName() : '---',
	        'status' => $this->status,
	        'active_from' => $this->active_from,
	        'active_to' => $this->active_to,
	        'created_at' => $this->created_at,
	    ];
	}

	
    /**
     *  Attributes
     */

    public function getPositionCountAttribute() {
        return $this->positions->count();
    }

	public function getUserCountAttribute() {
    	return $this->positions->sum('user_count');
    }

	/**
	* Relationships
	*/

	public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }
	
    public function head() {
        return $this->belongsTo(User::class, 'user_id');
    }

	public function positions() {
        return $this->hasMany(Position::class);
    }

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['code', 'name', 'status', 'company_id', 'user_id','active_from', 'active_to'])
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
        return route('departments.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('departments.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('departments.restore', $this->id);
    }

	public function withCompanyRenderShowUrl() {
        return route('departments.show', [$this->id, $this->company_id]);
    }

    public function renderName() {
    	return $this->title. ' '.$this->first_name. ' '. $this->last_name.' '. $this->suffix;
    }
}
