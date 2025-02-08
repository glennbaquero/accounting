<?php

namespace App\Http\Requests\InterestCalculations;

use Illuminate\Foundation\Http\FormRequest;

class InterestCalculationStoreRequest extends FormRequest
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
        $rules = [
            'client_id' => 'required',
            'from_date' => 'required | date | before:to_date',
            'to_date' => 'required | date | after:from_date',
            'round_off' => 'required | numeric',
            'invoice' => 'nullable',
            'credit_note' => 'nullable',
            'payment' => 'nullable',
            'interest' => 'nullable',
            'customer_account' => 'required',
            'invoice_account' => 'required',
            'invoice_date' => 'required',
            'customer_address' => 'required',
            'customer_name' => 'required',
            'customer_contact_id' => 'required',
            'customer_bank_account' => 'required',
            'bills_of_exchange_id' => 'required',
            'posting_profile_from' => 'required',
            'customer_posting_profile_id' => 'required_if:posting_profile_from,==,Select',
        ];

        return $rules;
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
