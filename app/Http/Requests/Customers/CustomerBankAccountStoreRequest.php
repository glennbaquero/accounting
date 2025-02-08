<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class CustomerBankAccountStoreRequest extends FormRequest
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
            // 'bank_groups' =>'string | max:255 | required',
            // 'bank_account' =>'string | max:255 | required',
            'customer_account' =>'string | max:255 | required',
            'client_id' => 'max:255 | required',
            'bank_account_status' =>'string | max:255 | nullable',
            'bank_name' =>'string | max:255 | required',
            'active_date' =>'string | required | date | before:expiration_date',
            'expiration_date' =>'string | required | date | after:active_date',

            'bank_account_number' =>'string | max:255 | required',
            'account_holder' =>'string | max:255 | required',
            'bank_account_type' =>'string | max:255 | required',
            'routing_number' =>'string | max:255 | nullable',
            'bank_name' =>'string | max:255 | required',
            'bank_branch' =>'string | max:255 | required',

            'swift_code' =>'string | max:255 | nullable',
            'iban' =>'string | max:255 | nullable',
            'post_fee_checkbox' =>'string | max:255 | nullable',
            'fee_account' =>'string | max:255 | nullable',
            'clearing' =>'string | max:255 | nullable',

            'cost_center' => 'max:255 | required',
            'department' => 'max:255 | required',
            'expense_purpose' => 'max:255 | required',
            'text_code' => 'string | max:255 | nullable',
            'message_to_bank' => 'string | max:255 | nullable',
            'address' => 'string | max:255 | nullable',

            'name_of_person' => 'string | max:255 | nullable',
            'telephone' => 'string | max:255 | nullable',
            'extension' => 'string | max:255 | nullable',
            'pager' => 'string | max:255 | nullable',
            'mobile_phone' => 'string | max:255 | nullable',

            'fax' => 'string | max:255 | nullable',
            'email' => 'string | max:255 | nullable | email',
            'sms' => 'string | max:255 | nullable',
            'internet_address' => 'string | max:255 | nullable',
            'telex_number' => 'string | max:255 | nullable',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'client_id.required' => 'The client field is required'
        ];
    }
}
