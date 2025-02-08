<?php

namespace App\Http\Requests\BankAccountStatements;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountStatementStoreRequest extends FormRequest
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
            'bank_statement' => 'required',
            // 'bank_statement_id' => ,
            'client_bank_account_number' => 'required',
            'bank_account_transaction_number' => 'required',
            'bank_statement_issue_date' => 'required | date',
            'bank_statement_from_date' => 'required | date | before:bank_statement_to_date',
            'bank_statement_to_date' => 'required | date | after:bank_statement_from_date',
            'prepared_by' => 'required',
            'cost_center' => 'required',
            'department' => 'required',
            'currency' => 'required',
            'opening_balance' => 'numeric',
            'ending_balance' => 'numeric',
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
