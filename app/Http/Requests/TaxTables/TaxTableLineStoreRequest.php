<?php

namespace App\Http\Requests\TaxTables;

use Illuminate\Foundation\Http\FormRequest;

class TaxTableLineStoreRequest extends FormRequest
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
            'tax_id' => 'required',
            'tax_name' => 'required',
            // 'tax_posting_id' => 'required',
            'tax_posting' => 'required',
            'description' => 'required',
            'level' => 'required',
            'tax_percent' => 'required | numeric',
            'peza_checkbox' => 'nullable',
            'vat_exempt_number_checkbox' => 'nullable',
            'major_industry_clasification' => 'required',
            'industry_clasification_group' => 'required',
            'psic_sections' => 'required',
            'psic_divisions' => 'required',
            'psic_groups' => 'required',
            'psic_class' => 'required',
            'psic_subclass' => 'required',
            'procurement_posting' => 'required',
            'product_id' => 'nullable',
            'variant_id' => 'nullable',
            'service_id' => 'nullable',
            'service_task_id' => 'nullable',
            'delivery_type' => 'nullable',
            'applied_to' => 'required',
            'tax_account_code_number' => 'required',
            'tax_account' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
