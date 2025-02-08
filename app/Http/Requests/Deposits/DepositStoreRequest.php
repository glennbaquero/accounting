<?php

namespace App\Http\Requests\Deposits;

use Illuminate\Foundation\Http\FormRequest;

class DepositStoreRequest extends FormRequest
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
            'client_bank_account_number' => 'required | string',
            // 'customer_company' => 'required | string',
            // 'customer_contact' => 'required | string',
            // 'deposit_slip_id' => 'required | string',
            'deposit_slip_number' => 'required | string',
            'deposit_amount' => 'required | numeric',
            'issue_date' => 'required | date',
            'bank_posting_profile' => 'nullable | string',
            'method_of_payment_customer' => 'required | string',
            'payment_reference' => 'nullable | required',
            'pending_cancellation' => 'nullable',
            'reason_code' => 'nullable | string',
            'reason_comment' => 'nullable | string',
            'description' => 'nullable | string',
            'cost_center' => 'required',
            'department' => 'required',
            'expense_purpose' => 'required',

            'voucher_no' => 'required | string',
            'reason_code' => 'nullable | string',
            'reason_comment' => 'nullable | string',
            'description' => 'nullable | string',
            // 'vendor_account' => 'nullable | string',
            // 'vendor_bank_account' => 'nullable | string',
            // 'bank_account_number' => 'nullable | string',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'client_id.required' => 'The client field is required'
        ];
    }
}
