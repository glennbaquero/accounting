<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdatePermissionRequest extends FormRequest
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
            'permissions' => 'nullable',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    public function messages()
    {
        return [
            'permissions.*.exists' => 'Some of the permission selected no longer exists',
        ];
    }
}
