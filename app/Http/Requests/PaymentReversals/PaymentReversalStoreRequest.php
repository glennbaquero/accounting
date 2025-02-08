<?php

namespace App\Http\Requests\PaymentReversals;

use Illuminate\Foundation\Http\FormRequest;

class PaymentReversalStoreRequest extends FormRequest
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
            'reversed_date' => ['required'],
            'reason' => ['required'],
            'status' => ['required'],
            // 'approved_checkbox' => ['required'],
            // 'approved_by' => ['required'],
            // 'approved_date' => ['required'],
            // 'posted_checkbox' => ['required'],
            // 'posted_by' => ['required'],
            // 'posted_date' => ['required'],
            'voucher' => ['required'],
            'client_bank_account_number' => ['required'],
            'customer_bank_account_number' => ['required'],
            'vendor_bank_account_number' => ['required'],
            'check_id' => ['required'],
            'check_issued_date' => ['required'],
            'postdated_check_status' => ['required'],
            'check_number' => ['required'],
            'amount' => ['required', 'numeric'],
            'deposit_id' => ['required'],
            'deposit_issued_date' => ['required'],
            'deposit_status' => ['required'],
            'vendor_payment_id' => ['required'],
            'vendor_payment_issued_date' => ['required'],
            'vendor' => ['required'],
            'customer_payment_id' => ['required'],
            'customer_payment_issued_date' => ['required'],
            'customer' => ['required'],
            'bank_statement_id' => ['required'],
            'bank_statement_issued_date' => ['required'],
            'bank_statement_status' => ['required'],
            'cash_register_id' => ['required'],
            'cash_register_issued_date' => ['required'],
            'cash_register_status' => ['required'],
            'bank_reconciliation_id' => ['required'],
            'bank_reconciliation_issued_date' => ['required'],
            'bank_reconciliation_status' => ['required'],
            'payment_reference' => ['required'],
            'vendor_payment_method' => ['required'],
            'customer_payment_method' => ['required'],
            'bank_posting' => ['required'],
            'bank_reason' => ['required'],
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
