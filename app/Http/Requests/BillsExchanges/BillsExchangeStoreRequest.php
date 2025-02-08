<?php

namespace App\Http\Requests\BillsExchanges;

use Illuminate\Foundation\Http\FormRequest;

class BillsExchangeStoreRequest extends FormRequest
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
            'issue_date' => 'required | date',
            'due_from' => 'required | date | before:due_to',
            'due_to' => 'required | date | after:due_from',
            'principal_amount' => 'required | numeric',
            'number_of_times_to_settle' => 'required | numeric',
            'ammount_to_settle' => 'required | numeric',
            'terms_of_payment' => 'required',
            'payment_day' => 'required',
            'interest_rate' => 'required | numeric',
            'interest_amount' => 'required | numeric',
            'terms_of_interest' => 'required',
            'customer_bank_account' => 'required',
            'client_bank_account' => 'required',
            'voucher' => 'required',
            'status' => 'required',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
