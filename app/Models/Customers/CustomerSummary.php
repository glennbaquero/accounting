<?php

namespace App\Models\Customers;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;

class CustomerSummary extends Model
{
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'customer_summary_id' => $this->customer_summary_id,
        ];
    }
    
    public function customer() 
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function user_approved() 
    {
        return $this->belongsTo(User::class, 'approved_by', 'id')->withTrashed();
    }

    public function user_prepared() 
    {
        return $this->belongsTo(User::class, 'prepared_by', 'id')->withTrashed();
    }

    public function lines() 
    {
        return $this->hasMany(CustomerSummaryLine::class);
    }

    /**
     * @Setters
     */
    public static function store($request, $item = null, $columns = [
        'customer_summary_id',
        'customer_id',
        'summary_as_of',
        'issue_date_from',
        'issue_date_to',
        'prepared_by',
        'number_sales_order',
        'number_customer_invoice',
        'number_overdue_invoice',
        'opening_balance',
        'invoiced_amount',
        'amount_paid',
        'balance_due',
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
        return route('customer-summaries.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('customer-summaries.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('customer-summaries.restore', $this->id);
    }

    public function renderApprovedUrl() {
        return route('customer-summaries.approved', $this->id);
    }
}
