<?php

namespace App\Models\PurchaseOrders;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use App\Models\AdminSetups\Client;
use App\Models\Vendors\VendorBankAccount;

class LetterCreditPurchase extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'bank_document_number' => $this->bank_document_number,
            'purchase_status' => $this->purchase_status,
        ];
    }


    public function created_by_user() {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updated_by_user() {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    public function confirmed_by_user() {
        return $this->belongsTo(User::class, 'confirmed_by', 'id')->withTrashed();
    }

    public function close_by_user() {
        return $this->belongsTo(User::class, 'close_by', 'id')->withTrashed();
    }

    public function issue_by_user() {
        return $this->belongsTo(User::class, 'issue_by', 'id')->withTrashed();
    }

    public function amendment_by_user() {
        return $this->belongsTo(User::class, 'amendment_by', 'id')->withTrashed();
    }

    public function purchase_order() {
        return $this->belongsTo(PurchaseOrder::class)->withTrashed();
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function vendor_bank() {
        return $this->belongsTo(VendorBankAccount::class)->withTrashed();
    }


    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['bank_document_number', 'issue_date', 'issue_by', 'application_date', 'receipt_date', 'amendment_number', 'amendment_on', 'amendment_by', 'purchase_order_id', 'voucher_number', 'purchase_status', 'confirmation_instruction', 'vendor_account', 'vendor_bank_account_id', 'advising_bank', 'issuing_bank', 'available_with', 'bank_document_id', 'bank_document_type_id', 'bank_facility_type_id', 'bank_facility_agreement_number', 'documentary_credit_type', 'documentary_credit_nature', 'beneficiary', 'lc_ic_amount', 'lc_tolerance_amount', 'tolerance_percentage', 'beneficiary', 'documentary_credit_nature', 'currency', 'expiration_date', 'place_of_expiration', 'partial_shipment', 'partial_shipment', 'port_loading', 'latest_shipment_date', 'destination_port', 'description_goods', 'incoterms', 'document_required', 'special_instructions', 'bank_charges', 'draft', 'deferred_days', 'period_of_presentation', 'description', 'insurance_number', 'insurance_status', 'insurance_vendor_number', 'shipment_number', 'shipment_date', 'shipment_date_to', 'port_loading', 'port_discharge', 'purchase_delivery_receipt_date', 'actual_maturity_date', 'margin_amount', 'allocated', 'settled', 'shipping_document_status', 'shipment_status', 'voucher_number'])
    {
        $vars = $request->only($columns);          

        $vars['purchase_status'] = 'Prepared';
        
        $vars['company_id'] = auth()->user()->company_id;

        if (!$item) {
            $vars['created_by'] = auth()->user()->id;
            $vars['updated_at'] = null;
            $item = static::create($vars);
        } else {
            $vars['updated_by'] = auth()->user()->id;
            $item->update($vars);
        }

        return $item;
    }

    /**
     * Renderers
     */
    
    public function renderShowUrl() {
        return route('letter-credit-purchases.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('letter-credit-purchases.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('letter-credit-purchases.restore', $this->id);
    }

    public function renderCloseUrl() {
        return route('letter-credit-purchases.close', $this->id);
    }

    public function renderConfirmUrl() {
        return route('letter-credit-purchases.confirm', $this->id);
    }

    public function renderAmendmentUrl() {
        return route('letter-credit-purchases.confirm', $this->id);
    }

    public function renderCreatedBy() {
        return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
    }

    public function renderUpdatedBy() {
        return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
    }

}
