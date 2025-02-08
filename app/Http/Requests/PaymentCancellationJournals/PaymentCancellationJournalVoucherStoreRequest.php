<?php

namespace App\Http\Requests\PaymentCancellationJournals;

use Illuminate\Foundation\Http\FormRequest;

class PaymentCancellationJournalVoucherStoreRequest extends FormRequest
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
            'customer_payment_id' => 'required',
            'customer_payment_issued_date' => 'required',
            'customer_name' => 'required',
            'customer_payment_method' => 'required',
            'vendor_payment_id' => 'required',
            'vendor_payment_issued_date' => 'required',
            'vendor_name' => 'required',
            'vendor_payment_method' => 'required',
            'client_bank_account_number' => 'required',
            'check_id' => 'required',
            'check_number' => 'required',
            'check_amount' => 'required',
            'deposit_id' => 'required',
            'payment_reference' => 'required',
            'bank_account_transaction_id' => 'required',
            'bank_reconciliation_id' => 'required',
            'reconcile_date' => 'required',
            'matched_checkbox' => 'nullable',
            'statement_adjustment_id' => 'required',
            'cash_register_adjustment_id' => 'required',
            'bank_statement_id' => 'required',
            'bank_posting' => 'required',
            'bank_reason' => 'required',
            'reversal_id' => 'required',
            'reversed_date' => 'required',
            'cancelled_date' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
