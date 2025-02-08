<?php

namespace App\Http\Requests\BankAccountStatementLines;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountStatementLineStoreRequest extends FormRequest
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
            'line_number' => 'required | numeric',
            'transaction_date' => 'required | date',
            'payment_reference' => 'required',
            'bank_transaction_code' => 'required',
            'bank_reason' => 'required',
            'withdrawal_debit_amount' => 'required | numeric',
            'deposit_credit_amount' => 'required | numeric',
            'cost_center' => 'required',
            'department' => 'required',
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
