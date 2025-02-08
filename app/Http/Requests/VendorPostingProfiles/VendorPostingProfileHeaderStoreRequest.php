<?php

namespace App\Http\Requests\VendorPostingProfiles;

use Illuminate\Foundation\Http\FormRequest;

class VendorPostingProfileHeaderStoreRequest extends FormRequest
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
            'client_id' => 'required',
            'posting_profile' => 'required',
            'description' => 'required',
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'client field is required'
        ];
    }    
}
