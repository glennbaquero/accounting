<?php

namespace App\Http\Requests\ClosingTransactions;

use Illuminate\Foundation\Http\FormRequest;

class ClosingTransactionStoreRequest extends FormRequest
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
            'general_ledger_id' => 'required',
            'ledger_id' => 'required',
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'Client field is required',
            'general_ledger_id.required' => 'General Ledger field is required',
            'ledger_id.required' => 'General Ledger field is required',
        ];
    }    
}
