<?php

namespace App\Models\Banks;

use App\Extenders\Models\BaseModel as Model;

use App\Models\AdminSetups\Client;

class BankFacilityGroup extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'bank_facility_group_code' => $this->bank_facility_group_code,
            'bank_facility_group_name' => $this->bank_facility_group_name,
           
        ];
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }


    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['bank_facility_group_code','bank_facility_group_name','description',])
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
        return route('bank-facility-groups.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-facility-groups.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-facility-groups.restore', $this->id);
    }
}
