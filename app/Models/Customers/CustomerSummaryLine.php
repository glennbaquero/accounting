<?php

namespace App\Models\Customers;

use App\Extenders\Models\BaseModel as Model;

use App\Models\JournalSetups\TermsOfPayment;
use App\Models\JournalSetups\PaymentMethod;

class CustomerSummaryLine extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'transation_number' => $this->transation_number,
            'transation_type' => $this->transation_type,
        ];
    }

    public function customer_summary()
    {
        return $this->belongsTo(CustomerSummary::class)->withTrashed();
    }

    public function terms_of_payment()
    {
        return $this->belongsTo(TermsOfPayment::class)->withTrashed();
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class)->withTrashed();
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = [
        'customer_summary_id',
        'transaction_date',
        'due_date',
        'transation_number',
        'transation_type',
        'method_of_payment_id',
        'terms_of_payment_id',
        'invoice_status',
        'payment_status',
        'pdc_status',
        'transaction_status',
        'amount_inclusive_tax',
        'payments',
        'outstanding',
    ])
    {

        $vars = $request->only($columns);

        if (!$item) {
            $item = static::create($vars);
        } else {
            $item->update($vars);
        }

        return $item;
    }

    public function renderShowUrl() {
        return route('customer-summary-lines.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('customer-summary-lines.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('customer-summary-lines.restore', $this->id);
    }
}
