<?php

namespace App\Http\Requests\TransactionPostings;

use Illuminate\Foundation\Http\FormRequest;

class TransactionPostingHeaderStoreRequest extends FormRequest
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
            'effective_date' => 'required',
            'expiration_date' => 'required',
            'closing_debit_account' => 'required',
            'closing_credit_account' => 'required',
            'module' => 'required',
            'document' => 'required',
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'client field is required'
        ];
    }    
}
