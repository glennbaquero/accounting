<?php

namespace App\Http\Requests\FinancialDimensions;

use Illuminate\Foundation\Http\FormRequest;

class FinancialDimensionStoreRequest extends FormRequest
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
            'dimension_name' => 'required',
            'report_column_name' => 'required',
            'dimension_value_mask' => 'required',
        ];
    }
}
