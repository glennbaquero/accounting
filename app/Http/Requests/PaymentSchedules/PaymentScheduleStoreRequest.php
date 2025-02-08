<?php

namespace App\Http\Requests\PaymentSchedules;

use Illuminate\Foundation\Http\FormRequest;

class PaymentScheduleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id' => 'required',
            'payment_schedule_name' => 'required',
            'description' => 'required',
            'schedule_start_date' => 'required | date | before:schedule_end_date',
            'schedule_end_date' => 'required | date | after:schedule_start_date',
            'allocation' => 'required',
            'payment_per' => 'required',
            'no_of_payments' => 'required | numeric',
            'principal_original_amount' => 'required | numeric',
            'minimum_amount' => 'required | numeric',
            'sales_tax_allocation' => 'required',
            'charge_allocation' => 'required',
            'customer_invoice_number' => 'required',
            'bills_exchange_id' => 'required',
            'payment_schedule_status' => 'required',
            'customer_account' => 'required',
            'customer_address' => 'required',
            'customer_name' => 'required',
            'customer_contact_id' => 'required',
            'client_bank_account' => 'required',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
