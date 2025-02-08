<?php

namespace App\Models\Letters;
use App\Extenders\Models\BaseModel as Model;

use App\Models\AdminSetups\Client;
use App\Models\Banks\BankFacilityType;
use App\Models\SalesOrders\SalesOrder;
use App\Models\PurchaseOrders\PurchaseOrder;

class LetterOfGuarantee extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'letter_of_guarantee_number' => $this->letter_of_guarantee_number,
            'requested_by' => $this->requested_by,
            'transaction_type' => $this->transaction_type,
            'status' => $this->status,
        ];
    }

    public function client() {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function facility_type() {
        return $this->belongsTo(BankFacilityType::class, 'bank_facility_type_id')->withTrashed();
    }

    public function client_bank_account() {
        return $this->belongsTo(ClientBankAccount::class, 'client_bank_account_id')->withTrashed();
    }

    public function sales_order() {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id')->withTrashed();
    }

    public function purchase_order() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id')->withTrashed();
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = ['letter_of_guarantee_number', 'document_id', 'document_type_id', 'requested_by', 'transaction_type', 'received_date', 'issue_date', 'expiration_date', 'amount', 'currency', 'client_id', 'client_bank_account_id', 'sales_order_id', 'status', 'approved_checkbox', 'approved_date', 'approved_by_id', 'liquidated', 'liquidated_on', 'extended', 'extended_on', 'purchase_order_id', 'bank_facility_status', 'bank_facility_agreement_id', 'bank_facility_type_id', 'margin', 'expense', 'cancellation_reason', 'cancellation_date', 'project_reason', 'project_number',])
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
        return route('letter-of-gurantees.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('letter-of-gurantees.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('letter-of-gurantees.restore', $this->id);
    }

    public function renderLiquidateUrl() {
        return route('letter-of-gurantees.liquidated', $this->id);
    }

    public function renderExtendUrl() {
        return route('letter-of-gurantees.extend', $this->id);
    }

    public function renderApprovedUrl() {
        return route('letter-of-gurantees.approve', $this->id);
    }
}
