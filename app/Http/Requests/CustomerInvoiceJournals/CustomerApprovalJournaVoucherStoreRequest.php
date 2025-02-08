<?php

namespace App\Http\Requests\CustomerInvoiceJournals;

use Illuminate\Foundation\Http\FormRequest;

class CustomerApprovalJournaVoucherStoreRequest extends FormRequest
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
        $validations = [
            "voucher_type" =>'required',
            "invoice_voucher_number" => 'required',
            "voucher_date" => 'required',
            "transaction_date" =>'required',
            "invoice_number" => 'required',
            "invoice_date" =>'required',
            "terms_of_payment" => 'required',
            "bank_account" => 'required',
            "bank_transaction_type" => 'required',
            "due_date" => 'required',
            "customer_account" =>'required',
            "entry_pair_number" => 'required|integer|max:9999',
            "client_id" =>'required',
            "description" =>'required',
        ];

        if($this->voucher_type == "Debit") {
            $validations['debit_amount'] = 'required|max:99999999999999999999';
            $validations['main_account'] = 'required';
            $validations['account_type'] = 'required';
        }else if($this->voucher_type == "Credit"){
            $validations['credit_amount'] = 'required|max:99999999999999999999';
            $validations['offset_account'] = 'required';
            $validations['offset_account_type'] = 'required';
            $validations['offset_transaction_text'] = 'required';
            $validations['offset_company_accounts'] = 'required';
        }

        return $validations;
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
