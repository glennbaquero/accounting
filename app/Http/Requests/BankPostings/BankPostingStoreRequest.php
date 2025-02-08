<?php

namespace App\Http\Requests\BankPostings;

use Illuminate\Foundation\Http\FormRequest;

class BankPostingStoreRequest extends FormRequest
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
            'bank_transaction_posting' => 'required',
            'description' => 'required',
            'document' => 'required',
            'bank_posting_code_number' => 'required',
            'bank_posting' => 'required',
            'cash_register_adjustment_id' => 'required_without:bank_statement_line_adjustment_id',
            'bank_statement_line_adjustment_id' => 'required_without:cash_register_adjustment_id',
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
