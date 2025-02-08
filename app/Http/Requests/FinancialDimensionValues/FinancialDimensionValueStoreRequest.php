<?php

namespace App\Http\Requests\FinancialDimensionValues;

use Illuminate\Foundation\Http\FormRequest;

class FinancialDimensionValueStoreRequest extends FormRequest
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
            'description' => 'required',
            'active_from' => 'required|date',
            'active_to' => 'required|date',
        ];
    }
}
