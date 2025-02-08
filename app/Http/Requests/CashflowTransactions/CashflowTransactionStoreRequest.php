<?php

namespace App\Http\Requests\CashflowTransactions;

use Illuminate\Foundation\Http\FormRequest;

class CashflowTransactionStoreRequest extends FormRequest
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
            'cashflow_transaction_name' => 'nullable',
            'type' => 'required | in:Customer,Vendor', // Customer or Vendor

            'vendor_payment_journal_voucher' => 'required_without:customer_payment_journal_voucher',
            'vendor_payment_journal_number' => 'required_with:vendor_payment_journal_voucher',

            'customer_payment_journal_voucher' => 'required_without:vendor_payment_journal_voucher',
            'customer_payment_journal_number' => 'required_with:customer_payment_journal_voucher',

            'journal_name' => 'nullable',
            'voucher_date' => 'nullable | date',
            'description' => 'nullable',
            'debit_amount' => 'required | numeric',
            'credit_amount' => 'required | numeric',
            
            'posted_checkbox' => 'nullable',
            'posted_on' => 'nullable',
            'posted_by' => 'nullable',

            'vendor_account' => 'required_without:customer_account',
            'vendor_name' => 'nullable',
            'vendor_invoice_number' => 'required_without:customer_invoice_number',
            
            'customer_account' => 'required_without:vendor_invoice_number',
            'customer_name' => 'nullable',
            'customer_invoice_number' => 'required_without:vendor_invoice_number',

            'invoice_date' => 'required | date',

            'payment_due_date' => 'nullable | date',
            'settlement_type' => 'required',
            // 'method_of_payment' => 'required',
            
            'vendor_payment_id' => 'required_without:customer_payment_id',
            'customer_payment_id' => 'required_without:vendor_payment_id',

            'payment_status' => 'required',
            
            'deposit_slip_number' => 'nullable',
            'payment_specification' => 'nullable',
            'payment_reference' => 'nullable',
            'bank_transaction_type' => 'nullable',

            'bank_account' => 'required', // Client Bank Account

            'postdated_check_status' => 'required',
            'check_number' => 'nullable',
            'check_number_issued' => 'nullable | date',

            'maturity_date' => 'nullable | date',
            'received_date' => 'nullable | date',

            'cashier' => 'required',
            'salesperson' => 'required',
            'issuing_bank_branch' => 'required',
            'issuing_bank_name' => 'required',
            'stop_payment' => 'required',
            'replacement_check' => 'required',
            'original_check' => 'required',
            'check_amount' => 'required',
            'recipient_name' => 'required',

            'reconciled_checkbox' => 'nullable',
            'reconciled_date' => 'nullable',
            'reconciled_by' => 'nullable',
            
            'adjustment_checkbox' => 'nullable',
            'adjustment_date' => 'nullable',
            'adjustment_by' => 'nullable',

            'matched' => 'nullable',
            'main_account' => 'required',
            'account_type' => 'required',

            'offset_company_accounts' => 'nullable',
            'offset_account_type' => 'required',
            'offset_account' => 'required',
            'offset_transaction_text' => 'nullable',
            'sales_tax_direction' => 'nullable',
            'sales_tax_group' => 'nullable',
            'item_sales_tax_group' => 'nullable',
            'withholding_tax_group' => 'nullable',
            'fee_account' => 'nullable',
            'fee_id' => 'nullable',
            'fee_amount' => 'nullable | numeric',
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
