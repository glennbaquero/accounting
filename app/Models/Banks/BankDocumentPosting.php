<?php

namespace App\Models\Banks;

use App\Extenders\Models\BaseModel as Model;

use App\Models\AdminSetups\Client;

class BankDocumentPosting extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'bank_document_postings' => $this->bank_document_postings,
        ];
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function facility_group() {
        return $this->belongsTo(BankFacilityGroup::class, 'bank_facility_group_id')->withTrashed();
    }

    public function facility_type() {
        return $this->belongsTo(BankFacilityType::class, 'bank_facility_type_id')->withTrashed();
    }


    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['bank_document_postings', 'bank_facility_type_id', 'bank_facility_group_id', 'description', 'settle_account_id', 'charges_account_id', 'margin_account_id', 'client_id'])
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
        return route('bank-document-postings.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-document-postings.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-document-postings.restore', $this->id);
    }
}
