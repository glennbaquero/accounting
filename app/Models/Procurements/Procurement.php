<?php

namespace App\Models\Procurements;

use App\Extenders\Models\BaseModel as Model;

class Procurement extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'procurement' => $this->procurement,
	    ];
	}


	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['procurement', 'main_account_code', 'main_account_name', 'client_id'])
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
        return route('procurements.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('procurements.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('procurements.restore', $this->id);
	}


}
