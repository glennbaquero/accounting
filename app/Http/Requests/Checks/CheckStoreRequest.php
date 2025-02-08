<?php

namespace App\Http\Requests\Checks;

use Illuminate\Foundation\Http\FormRequest;

class CheckStoreRequest extends FormRequest
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
            'client_bank_account_number' => 'required',
            'customer_bank_account_number' => 'required',
            'vendor_bank_account_number' => 'required',
            'check_number' => 'required | string',
            'issue_date' => 'required | date',
            'clearing_date' => 'nullable | date',
            'reconciled_date' => 'nullable | date',

            'check_currency' => 'required | string',
            'check_amount' => 'required | numeric',

            'bank_posting_profile' => 'nullable | string',
            // 'method_of_payment_customer' => 'required | string',
            'payment_reference' => 'nullable | string',
            // 'pending_cancellation' => 'nullable | required',
            'reason_code' => 'nullable | string',
            'reason_comment' => 'nullable | string',
            // 'description' => 'nullable | string',
            'cost_center' => 'required',
            'department' => 'required',
            'expense_purpose' => 'required',

            'voucher_no' => 'nullable | string',
            'vendor_account' => 'nullable | string',
            // 'vendor_bank_account' => 'nullable | string',
            'bank_account_number' => 'nullable | string',

            'vendor_company' => 'nullable | string',
            'vendor_contact' => 'nullable | string',

            'maturity_date' => 'nullable',
            'vendor_invoice_number' => 'nullable',
            'customer_invoice_number' => 'nullable',
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
