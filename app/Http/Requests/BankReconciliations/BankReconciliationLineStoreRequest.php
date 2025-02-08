\<?php

namespace App\Http\Requests\BankReconciliations;

use Illuminate\Foundation\Http\FormRequest;

class BankReconciliationLineStoreRequest extends FormRequest
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
            'bank_reconciliation_id' => 'required',

            'description' => 'nullable',
            'operation_type' => 'nullable | in:0,1',
            'source' => 'nullable | in:Bank Statement,Cash Register,Bank Posting,User Entry',
            'statement_adjustment_id' => 'nullable',
            'cash_register_adjustment_id' => 'nullable',
            'bank_posting_id' => 'nullable',

            'adjustment_name' => 'required',
            'adjustment_amount' => 'required',
   
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
