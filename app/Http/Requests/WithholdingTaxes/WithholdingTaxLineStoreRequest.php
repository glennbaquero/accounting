<?php

namespace App\Http\Requests\WithholdingTaxes;

use Illuminate\Foundation\Http\FormRequest;

class WithholdingTaxLineStoreRequest extends FormRequest
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
            'withholding_tax_name' => 'required',
            'withholding_tax_posting_id' => 'required',
            'withholding_tax_posting' => 'required',
            'description' => 'required',
            'minimum_amount' => 'required | numeric',
            'maximum_amount' => 'required | numeric',
            'tax_percent' => 'required | numeric',
            'withholding_tax_exemptions_checkbox' => 'nullable',
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
