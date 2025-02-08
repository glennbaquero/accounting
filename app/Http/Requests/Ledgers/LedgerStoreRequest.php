<?php

namespace App\Http\Requests\Ledgers;

use Illuminate\Foundation\Http\FormRequest;

class LedgerStoreRequest extends FormRequest
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
            'ledger_code' => 'required',
            'ledger_name' => 'required',
            'description' => 'required',
            'active_from' => 'required|nullable|required_with:active_to|before:active_to',
            'active_to' => 'required|nullable|required_with:active_from|after:active_from',
            'client_id' => 'required',
            // 'ledger_fiscal_calendar' => 'required', 
            'ledger_calendar_id' => 'required', 
            'chart_of_account_id' => 'required', 
            // 'ledger_chart_of_account' => 'required', 
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'client field is required',
            // 'ledger_fiscal_calendar.required' => 'ledger calendar field is required', 
            // 'ledger_chart_of_account.required' => 'chart of account field is required',
            'ledger_calendar_id.required' => 'ledger calendar field is required', 
            'chart_of_account_id.required' => 'chart of account field is required',  
        ];
    }    
}
