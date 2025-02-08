<?php

namespace App\Http\Requests\Variants;

use Illuminate\Foundation\Http\FormRequest;

class VariantUpdateRequest extends FormRequest
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
            'threshold_warning' => 'required|numeric|min:0|max:999999.99|gt:threshold_danger',
            'threshold_danger' => 'required|numeric|min:0|max:999999.99|lt:threshold_warning',
        ];
    }
}
