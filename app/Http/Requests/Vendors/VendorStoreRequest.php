<?php

namespace App\Http\Requests\Vendors;

use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
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
            'company_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'client_id' => 'required',
        ];

        if($this->middle_name) {
            $rules['middle_name'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'client_id.required' => 'The client field is required'
        ];
    }
}
