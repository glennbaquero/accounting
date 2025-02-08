<?php

namespace App\Http\Requests\BankReconciliations;

use Illuminate\Foundation\Http\FormRequest;

class BankReconciliationStoreRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required',
            'ending_balance' => 'required | numeric',
            'reconciled_transactions' => 'required | numeric',
            'unreconciled_transactions' => 'required | numeric',
            'client_bank_account' => 'required',
            
            'bank_account_number' => 'required',
            'bank_account_type' => 'required',
            'bank_statement_id' => 'required',

            'statement_as_of_date' => 'required | date',
            'statement_ending_balance' => 'required | numeric',
            'statement_total_amount' => 'required | numeric',
            'statement_open_amount' => 'required | numeric',
            'balance_per_bank_statement' => 'required | numeric',

            'cash_register_id' => 'required',
            'cash_register_as_of_date' => 'required | date',
            'cash_register_ending_balance' => 'required | numeric',
            'cash_register_total_amount' => 'required | numeric',
            'cash_register_open_amount' => 'required | numeric',

            'balance_per_cash_register' => 'required | numeric',
            'cash_register_description' => 'required | numeric',
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
