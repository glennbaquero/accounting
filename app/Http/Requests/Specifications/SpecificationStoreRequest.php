<?php

namespace App\Http\Requests\Specifications;

use Illuminate\Foundation\Http\FormRequest;

class SpecificationStoreRequest extends FormRequest
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
            'product_specification' => 'required',
            'client_id' => 'required',
            'specification_name' => 'required',
            'description' => 'required',
            'size' => 'required',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client field is required.'
        ];
    }
}
