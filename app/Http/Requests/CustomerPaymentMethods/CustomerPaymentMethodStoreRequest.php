<?php

namespace App\Http\Requests\CustomerPaymentMethods;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPaymentMethodStoreRequest extends FormRequest
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
            'method_of_payment_id' => 'required | unique:vendor_payment_methods,method_of_payment_id',
            'method_of_payment' =>'max:255 | required',
            'description' =>'max:255 | required',
            'payment_status' =>'max:255 | required',
            'account_type' =>'max:255 | required',
            // 'main_account_id' =>'max:255 | required',
            'bank_posting_profile' => 'required',
            'journal_name' =>'max:255 | required',
            'document' =>'required',
            'payment_account' =>'max:255 | required',
            
            'postdated_check_clearing_posting' =>'max:255 | required_if:document,==,Check',
            'postdated_check_status' =>'max:255 | required_if:document,==,Check',
            'postdated_check_account' =>'max:255 | required_if:document,==,Check',
            'not_sufficient_fund_account' =>'max:255 | required_if:document,==,Check',
        ];

        if($this->id) {
            $rules['method_of_payment_id'] = 'required | unique:vendor_payment_methods,method_of_payment_id,' . $this->id;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'client_id.required' => 'The client field is required'
        ];
    }
}
