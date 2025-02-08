<?php

namespace App\Models\PurchaseOrders;

use App\Extenders\Models\BaseModel as Model;

use App\Models\Vendors\VendorBankAccount;
use App\Models\AdminSetups\ClientBankAccount;
use App\Models\AdminSetups\Client;

class BankDocument extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'available_with' => $this->available_with,
            'bank_facility_agreement_number' => $this->bank_facility_agreement_number,
            'bank_facility_type' => $this->bank_facility_type,
            'bank_document_type' => $this->bank_document_type,
        ];
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function vendor_bank_account() { // Advising bank
        return $this->belongsTo(VendorBankAccount::class)->withTrashed();
    }

    public function client_bank_account() {
        return $this->belongsTo(VendorBankAccount::class)->withTrashed();
    }


    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['vendor_bank_account', 'available_with', 'client_bank_account_id', 'bank_facility_agreement_number', 'bank_facility_type', 'bank_document_type', 'facility_balance', 'documentary_credit_type', 'documentary_credit_nature', 'beneficiary', 'lc_ic_amount', 'lc_tolerance_amount', 'tolerance_percentage', 'tolerance_type', 'currency', 'expiration_date', 'place_of_expiration', 'partial_shipment', 'transshipment', 'port_loading', 'latest_shipment_date', 'destination_port', 'description_goods', 'incoterms', 'document_required', 'bank_charges', 'draft', 'preferred_days', 'period_of_presentation', 'confirmation_instruction', 'insurance_number', 'insurance_status', 'insurance_vendor_number', 'client_id',])
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
        return route('bank-documents.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('bank-documents.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('bank-documents.restore', $this->id);
    }
}
