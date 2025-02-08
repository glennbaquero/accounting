<?php

namespace App\Http\Requests\AdminSetups;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentStoreRequest extends FormRequest
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
            'code' => $for_updating ? 'unique:departments,code,'.$this->id : 'required|unique:departments,code',
            'name' =>  $for_updating ? 'unique:departments,name,'.$this->id : 'required|unique:departments,name',
            'status' => 'required',
            'company_id' => 'required',
            'active_from' => 'nullable|required_with:active_to|before:active_to',
            'active_to' => 'nullable|required_with:active_from|after:active_from',
        ];
    }

    public function messages() 
    {
        return [
            'user_id.required' => 'Please select department head',
            'company_id.required' => 'Please select company'
        ];
    }
}
