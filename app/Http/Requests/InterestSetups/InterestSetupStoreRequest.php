<?php

namespace App\Http\Requests\InterestSetups;

use Illuminate\Foundation\Http\FormRequest;

class InterestSetupStoreRequest extends FormRequest
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
            'interest_code' => 'required | unique:interest_setups,interest_code',
            'interest_name' => 'required',
            'description' => 'required',
            'interest_type' => 'required',
            'grace_period' => 'required | numeric',
            'effective_date' => 'required | date | before:expiration_date',
            'expiration_date' => 'required | date | after:effective_date',
            'calculate_interest_every' => 'required',
            'interest_earning_debit' => 'required',
            'interest_range_by' => 'required',
            'interest_amount' => 'required | numeric',
            'minimum_interest_amount' => 'required | numeric',
            'maximum_interest_amount' => 'required | numeric',
            'charge_customer_when_interest_exceeds' => 'required | numeric',
            'fee_amount' => 'required | numeric',
            'fee_account' => 'required',
            'sales_tax' => 'required',
            'interest_payment_credit_account' => 'required',
        ];

        if($this->id) {
            $rules['interest_code'] = 'required | unique:interest_setups,interest_code,' . $this->id;
        }

        return $rules;
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
