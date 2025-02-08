<?php

namespace App\Models\JournalLines;

use App\Extenders\Models\BaseModel as Model;

class Voucher extends Model
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


    public function voucher() {
        return $this->morphTo();
    }
}
