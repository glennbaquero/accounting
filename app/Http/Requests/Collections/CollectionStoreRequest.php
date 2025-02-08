<?php

namespace App\Http\Requests\Collections;

use Illuminate\Foundation\Http\FormRequest;

class CollectionStoreRequest extends FormRequest
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
            'collection_date' => 'required | date',
            'sent_date' => 'required | date',
            'due_date' => 'required | date',
            'amount_to_settle' => 'required | numeric',
            'customer_account' => 'required',
            'invoice_account' => 'required',
            'invoice_date' => 'required | date',
            'customer_address' => 'required',
            'customer_name' => 'required',
            'customer_contact_id' => 'required',
            'customer_bank_account' => 'required',
            'client_bank_account' => 'required',
            'description' => 'required',
            'bills_exchange_id' => 'required',
            'bills_exchange_status' => 'required',
            'voucher' => 'required',
            'collection_status' => 'required',
            'activity_type' => 'required',
            'activity_start_date' => 'required | date',
            'activity_date' => 'required | date',

        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
