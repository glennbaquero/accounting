<?php

namespace App\Http\Requests\Variants;

use Illuminate\Foundation\Http\FormRequest;

class VariantStoreRequest extends FormRequest
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
        $for_updating = $this->id;

        return [
            'name' => 'required',
            'variant_number' => $for_updating ? 'unique:variants,variant_number,'.$this->id : 'required|unique:variants,variant_number',
            'size' => 'required',
            'color' => 'required',
            'unit_of_measurement' => 'required',
            'unit_price' => 'required|numeric|min:0|not_in:0|min:1|max:999999.99',
        ];
    }
}
