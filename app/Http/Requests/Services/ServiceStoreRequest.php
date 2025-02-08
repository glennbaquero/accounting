<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
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
            'name' => 'required',
            'work_type' => 'required',
            'unit_price' => 'required',
            'service_type' => 'required',
            'client_id' => 'required',
            'vendor_id' => 'required',
            'description' => 'required'
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'client field is required',
        ];
    }    
}
