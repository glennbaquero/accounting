<?php

namespace App\Http\Requests\OpeningTransactions;

use Illuminate\Foundation\Http\FormRequest;

class OpeningTransactionStoreRequest extends FormRequest
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
            'main_account_id' => 'required',
            'general_ledger_id' => 'required',
            'period_from' => 'required',
            'period_to' => 'required',
            'ledger_id' => 'required',
            'ledger_calendar_id' => 'required',
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'Client field is required',
            'main_account.required' => 'Main Account field is required',
            'general_ledger_id.required' => 'General Ledger field is required',
            'ledger_id.required' => 'General Ledger field is required',
            'ledger_calendar_id.required' => 'General Ledger field is required',
        ];
    }    
}
