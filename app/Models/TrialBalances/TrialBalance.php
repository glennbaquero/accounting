<?php

namespace App\Models\TrialBalances;

use App\Extenders\Models\BaseModel;

use Auth;

class TrialBalance extends BaseModel
{

    public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'name' => $this->name,
	    ];
	}


    /**
     *  Setters
     */
    public static function store($request, $item = null, $columns = [])
	{
		$vars = $request->only($columns);
        
	    if (!$item) {
            $vars['created_by'] = Auth::user()->id;
	        $item = static::create($vars);
	    } else {
            $vars['updated_by'] = Auth::user()->id;
	        $item->update($vars);
	    }

	    return $item;
	}

    /**
     *  Renders
     */

    public function renderShowUrl() {
        return route('trial-balances.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('trial-balances.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('trial-balances.restore', $this->id);
    }


    
}
