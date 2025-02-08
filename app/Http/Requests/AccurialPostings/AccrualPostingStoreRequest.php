<?php

namespace App\Http\Requests\AccurialPostings;

use Illuminate\Foundation\Http\FormRequest;

class AccrualPostingStoreRequest extends FormRequest
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
           'ledger_id' => 'required',
           'accrual_id' => 'required',
           'main_account_id' => 'required',
           'accrual_status' => 'required',
           'ledger_posting_debit_account_number' => 'required',
           'ledger_posting_credit_account_number' => 'required',
           'calendar_type' => 'required',
           'period_frequency' => 'required',
           'length' => 'required|numeric|max:3650',
           'posting_date' => 'required',
           'description' => 'required',
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'Client field is required',
            'ledger_id' => 'Ledger field is required',
            'main_account_id' => 'Main Account field is required',
        ];
    }    
}
