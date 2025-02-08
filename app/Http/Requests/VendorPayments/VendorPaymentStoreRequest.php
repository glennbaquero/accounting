<?php

namespace App\Http\Requests\VendorPayments;

use Illuminate\Foundation\Http\FormRequest;

class VendorPaymentStoreRequest extends FormRequest
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
            'payment_status' => 'required',
            'transaction_type' => 'required',
            'issue_date' => 'required',
            'due_date' => 'required',
            'dimension_value_cost_center_id' => 'required',
            'dimension_value_department_id' => 'required',
            'dimension_value_expense_purpose_id' => 'required',
            'settlement_type' => 'required', 
            'method_of_payment_id' => 'required', 
            'bank_account' => 'required', 
            'payee' => 'required_without:vendor_account_id',
            'client_id' => 'required',
            'vendor_bank_account' => 'required',
            'bank_statement_id' => 'required',
            'bank_statement_issued_date' => 'required | date',
            'bank_posting' => 'required',
            'bank_reason' => 'required',
            'bank_reconciliation_id' => 'required',
            'reconciled_date' => 'required | date',
            'adjustment_date' => 'required | date',

            // 'deposit_id' => 'required_if:method_of_payment,==,Deposit Payment',
            'deposit_status' => 'required_if:method_of_payment,==,Deposit Payment',
            'deposit_slip_number' => 'required_if:method_of_payment,==,Deposit Payment',
            'deposit_amount' => 'required_if:method_of_payment,==,Deposit Payment',
            'deposit_date' => 'required_if:method_of_payment,==,Deposit Payment',
            'deposit_payment_checkbox' => 'nullable',

            // 'check_id' => 'required_if:method_of_payment,==,Check Payment',
            'postdated_check_status_id' => 'required_if:method_of_payment,==,Check Payment',
            'check_number' => 'required_if:method_of_payment,==,Check Payment',
            'check_number_issued' => 'required_if:method_of_payment,==,Check Payment',
            'check_amount' => 'required_if:method_of_payment,==,Check Payment',
            'recipient_name' => 'required_if:method_of_payment,==,Check Payment',
            'maturity_date' => 'required_if:method_of_payment,==,Check Payment',
            'received_date' => 'required_if:method_of_payment,==,Check Payment',
            'clearing_date' => 'required_if:method_of_payment,==,Check Payment',
            'original_check_number' => 'required_if:method_of_payment,==,Check Payment',
            'cashier' => 'required_if:method_of_payment,==,Check Payment',

            'issuing_bank_branch' => 'required',
            'issuing_bank_branch_name' => 'required',

            'total_sales_vat_exclusive' => 'nullable | numeric',
            'less_discount' => 'nullable | numeric',
            'add_charge' => 'nullable | numeric',
            'add_12_vat' => 'nullable | numeric',
            'total_sales_vat_inclusive' => 'nullable | numeric',
            'less_withholding_tax' => 'nullable | numeric',
            'amount_due' => 'nullable | numeric',
            'vatable_sales' => 'nullable | numeric',
            'vatexempt_sales' => 'nullable | numeric',
            'zero_rated_sales' => 'nullable | numeric',
            'vat_amount' => 'nullable | numeric',
            'total_amount_due' => 'nullable | numeric',
            'cash_amount' => 'nullable | numeric',
            'other_amount' => 'nullable | numeric',
            'total_amount_receiveds' => 'nullable | numeric',
            'total_vattable_sales_vat_exclusive' => 'nullable | numeric',
        ];
    }
}
