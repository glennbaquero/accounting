<?php

namespace App\Http\Requests\VendorPaymentJournals;

use Illuminate\Foundation\Http\FormRequest;

class VendorPaymentVoucherStoreRequest extends FormRequest
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
            "voucher_type" =>'nullable',
            "voucher_number" => 'required',
            "voucher_date" => 'required',
            "transaction_date" =>'required',
            "invoice_number" => 'required',
            "invoice_date" =>'required',
            "terms_of_payment" => 'required',
            "bank_account" => 'required',
            "bank_transaction_type" => 'required',
            "payment_due_date" => 'required',
            "vendor_account" =>'required',
            "entry_pair_number" => 'required',
            "client_id" =>'required',
            "description" =>'nullable',
        ];

        if($this->voucher_type == "Debit") {
            $validations['main_account'] = 'required';
            $validations['account_type'] = 'required';
        }else if($this->voucher_type == "Credit"){
            $validations['credit_amount'] = 'required';
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
