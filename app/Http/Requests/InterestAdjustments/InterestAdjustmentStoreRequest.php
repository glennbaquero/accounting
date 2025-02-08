<?php

namespace App\Http\Requests\InterestAdjustments;

use Illuminate\Foundation\Http\FormRequest;

class InterestAdjustmentStoreRequest extends FormRequest
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
        $rules = [
            'client_id' => 'required',
            'interest_adjustment_date' => 'required | date',
            'start_date' => 'required | date | before:end_date',
            'end_date' => 'required | date | after:start_date',
            'customer_account' => 'required',
            'customer' => 'required',
            'transaction_date' => 'required | date',
            'transaction_type' => 'required',
            'interest_note_id' => 'required',
            'interest_note_amount' => 'required | numeric',
            'waived_amount' => 'required | numeric',
            'unpaid_balance' => 'required | numeric',
            'fee_amount' => 'required | numeric',
            'interest_adjustment_status' => 'required',
            'voucher' => 'required',
            'write_off_amount' => 'required | numeric',
            'fee_write_off_amount' => 'required | numeric',

            'cost_center' => 'required',
            'department' => 'required',
            'expense_purpose' => 'required',
            'posting_profile' => 'nullable',
            'document' => 'nullable',
            'document_status' => 'nullable',
            'accounting_distribution' => 'nullable',
        ];

        return $rules;
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
