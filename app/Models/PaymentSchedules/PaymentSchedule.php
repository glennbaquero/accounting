<?php

namespace App\Models\PaymentSchedules;

use App\Extenders\Models\BaseModel as Model;
use App\Models\Users\User;
use App\Models\Invoices\CustomerInvoice;

class PaymentSchedule extends Model
{
    /**
	 * Get the indexable data array for the model.
	 *
	 * @return array
	 */
	public function toSearchableArray() {
	    return [
	        'id' => $this->id,
	        'payment_schedule_id' => $this->payment_schedule_id,
			'payment_schedule_name' => $this->payment_schedule_name,
			'description' => $this->description,
			'schedule_start_date' => $this->schedule_start_date,
			'schedule_end_date' => $this->schedule_end_date,
			'allocation' => $this->allocation,
			'payment_per' => $this->payment_per,
			'no_of_payments' => $this->no_of_payments,
			'principal_original_amount' => $this->principal_original_amount,
			'minimum_amount' => $this->minimum_amount,
			'sales_tax_allocation' => $this->sales_tax_allocation,
			'charge_allocation' => $this->charge_allocation,
			'customer_invoice_number' => $this->customer_invoice_number,
			'bills_exchange_id' => $this->bills_exchange_id,
			'payment_schedule_status' => $this->payment_schedule_status,
			'customer_account' => $this->customer_account,
			'customer_address' => $this->customer_address,
			'customer_name' => $this->customer_name,
			'customer_contact_id' => $this->customer_contact_id,
			'client_bank_account' => $this->client_bank_account,
			'approved_by' => $this->approved_by,
			'approved_checkbox' => $this->approved_checkbox,
			'approved_date' => $this->approved_date,
	    ];
	}

	public function created_by_user() {
		return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
	}

	public function updated_by_user() {
		return $this->belongsTo(User::class, 'updated_by', 'id')->withTrashed();
	}

	public function approved_by_user() {
		return $this->belongsTo(User::class, 'approved_by', 'id')->withTrashed();
	}


	public function customer_invoice() {
		return $this->belongsTo(CustomerInvoice::class, 'customer_invoice_number', 'customer_invoice_number')->withTrashed();
	}

	/**
	 * @Setters
	 */
	public static function store($request, $item = null, $columns = [
		'client_id',
		'payment_schedule_name',
		'description',
		'schedule_start_date',
		'schedule_end_date',
		'allocation',
		'payment_per',
		'no_of_payments',
		'principal_original_amount',
		'minimum_amount',
		'sales_tax_allocation',
		'charge_allocation',
		'customer_invoice_number',
		'bills_exchange_id',
		'payment_schedule_status',
		'customer_account',
		'customer_address',
		'customer_name',
		'customer_contact_id',
		'client_bank_account',
		// 'approved_by',
		// 'approved_checkbox',
		// 'approved_date',
	])
	{
		
		$vars = $request->only($columns);
		$vars['company_id'] = auth()->user()->company_id;

	    if (!$item) {
	        $item = static::create($vars);
	        $payment_schedule_id = 'payment-schedule-id-' . date('Ymd') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
	        $item->update([
	        	'created_by' => $request->user()->id,
	        	'updated_by' => $request->user()->id,
	        	'payment_schedule_id' => $payment_schedule_id,
	        ]);
	    } else {
	        $item->update($vars);
	        $item->update([
	        	'updated_by' => $request->user()->id,
	        ]);
	    }

	    return $item;
	}

	/**
	 * Renders
	 */
	public function renderCreatedBy() {
		return $this->created_by_user ? $this->created_by_user->renderName() : ''; 
	}

	public function renderUpdatedBy() {
		return $this->updated_by_user ? $this->updated_by_user->renderName() : ''; 
	}

	public function renderApprovedBy() {
		return $this->approved_by_user ? $this->approved_by_user->renderName() : ''; 
	}

	public function renderApproveUrl() {
        return route('payment-schedules.approve', $this->id);
    }

	public function renderShowUrl() {
        return route('payment-schedules.show', $this->id);
    }

    public function renderArchiveUrl() {
        return route('payment-schedules.archive', $this->id);
    }

    public function renderRestoreUrl() {
        return route('payment-schedules.restore', $this->id);
    }

    public function renderCustomerInvoiceUrl() {
        return route('customer-invoices.show', $this->customer_invoice->id);
    }

}
