<?php

namespace App\Http\Requests\BankAccountTransactions;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountTransactionStoreRequest extends FormRequest
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
            'vendor_company' => 'nullable | string',
            'vendor_contact' => 'nullable | string',

            'method_of_payment_customer' => 'required',
            'method_of_payment_vendor' => 'required',
            // 'bank_statement' => '',
            'bank_statement_date' => 'required | date',
            'transaction_date' => 'required | date',
            'issued_by' => 'required',
            'bank_posting_profile' => 'required',
            'deposit_slip_number' => 'required',
            'check_number' => 'required',
            'cleared_checkbox' => 'nullable',
            'reconciled_checkbox' => 'nullable',
            'manual_checkbox' => 'nullable',
            'pending_cancellation_checkbox' => 'nullable',
            'reason_code' => 'required',
            'reason_comment' => 'required',
            'voucher_number' => 'required',
            'accounting_date' => 'required | date',
            'cost_center' => 'required',
            'department' => 'required',
            'expense_purpose' => 'required',
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
