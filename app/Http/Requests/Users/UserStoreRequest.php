<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'email' => $for_updating ? 'email|unique:users,email,'.$this->id : 'email|required|unique:users,email',
            'status' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required',
            'department_id' => 'required',
            'position_id' => 'required',
            'active_from' => 'nullable|required_with:active_to|before:active_to',
            'active_to' => 'nullable|required_with:active_from|after:active_from',
        ];
    }

    public function messages() 
    {
        return [
            'company_id.required' => 'The company field is required.',
            'department_id.required' => 'The department field is required.',
            'position_id.required' => 'The position field is required.',
        ];
    }
}
