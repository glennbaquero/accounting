<?php

namespace App\Http\Requests\TaxTables;

use Illuminate\Foundation\Http\FormRequest;

class TaxTableStoreRequest extends FormRequest
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
            'tax_posting' => 'required',
            'tax_posting_name' => 'required',
            'description' => 'required',
            'tax_percent' => 'required | numeric',
            'peza_checkbox' => 'nullable',
            'vat_exempt_number_checkbox' => 'nullable',
            'tax_account_code_number' => 'required',
            'tax_account' => 'required',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
