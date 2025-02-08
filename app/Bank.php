<?php

namespace App;

use App\Extenders\Models\BaseModel as Model;

class Bank extends Model
{
        /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
			'id' => $this->id,
			'bank_name' => $this->bank_name,
			'bank_address' => $this->bank_address,
			'contact_number' => $this->contact_number,
	    ];
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = ['bank_name', 'bank_address', 'contact_number'])
	{
		
		$vars = $request->only($columns);

	    if (!$item) {
	        $item = static::create($vars);
	    } else {
	        $item->update($vars);
	    }

	    return $item;
	}
}
