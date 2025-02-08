<?php

namespace App\Http\Requests\WithholdingTaxes;

use Illuminate\Foundation\Http\FormRequest;

class WithholdingTaxStoreRequest extends FormRequest
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
            'withholding_tax_posting' => 'required',
            'withholding_tax_posting_name' => 'required',
            'description' => 'required',
            'effective_date' => 'required | date | before:expiration_date',
            'expiration_date' => 'required | date | after:effective_date',
            'withholding_tax_percent' => 'nullable | numeric',
            'withholding_tax_exemptions_checkbox' => 'nullable',
            'withholding_tax_debit_account' => 'nullable',
            'withholding_tax_debit_account_code_number' => 'nullable',
            'withholding_tax_credit_account' => 'nullable',
            'withholding_tax_credit_account_code_number' => 'nullable',
            'withholding_tax_debit_offset_account' => 'nullable',
            'withholding_tax_debit_offset_account_code_number' => 'nullable',
            'withholding_tax_credit_offset_account' => 'nullable',
            'withholding_tax_credit_offset_account_code_number' => 'nullable',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
