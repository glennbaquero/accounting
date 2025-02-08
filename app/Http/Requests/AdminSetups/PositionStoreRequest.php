<?php

namespace App\Http\Requests\AdminSetups;

use Illuminate\Foundation\Http\FormRequest;

class PositionStoreRequest extends FormRequest
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
            'code' => $for_updating ? 'unique:positions,code,'.$this->id : 'required|unique:positions,code',
            'name' => $for_updating ? 'unique:positions,name,'.$this->id : 'required|unique:positions,name',
            'company_id' => 'required',
            'type' => 'required',
            'status' => 'required',
            'department_id' => 'required',
            'active_from' => 'nullable|required_with:active_to|before:active_to',
            'active_to' => 'nullable|required_with:active_from|after:active_from',
        ];
    }

    public function messages() 
    {
        return [
            'department_id.required' => 'The department field is required.',
            'company_id.required' => 'The company field is required.',
        ];
    }
}
