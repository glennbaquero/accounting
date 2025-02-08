<?php

namespace App\Http\Requests\InvoiceApprovalJournals;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceApprovalJournalStoreRequest extends FormRequest
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
            'account_type' => 'required',
            'document' => 'required',
            'bank_account' => 'required',
            'cost_center' => 'required',
            'department' => 'required',
            'expense_purpose' => 'required',
            'used_by_user' => 'required',
            'lines_limit' => 'integer',
            'client_id' => 'required',
            'journal_type' => 'required',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
