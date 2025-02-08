<?php

namespace App\Http\Requests\TransactionPostings;

use Illuminate\Foundation\Http\FormRequest;

class TransactionPostingStoreRequest extends FormRequest
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
        'client_id' => 'required',
        'priority' => ['required','integer'],
        'main_account_type' => 'required',
		'match_account' => 'required',
		'match_account_number' => 'required',
		'main_account' => 'required',
		'main_account_number' => 'required',
		'journal',
		'document_attribute' => 'required',
		'document_values' => 'required',
		'method_of_payment_vendor' => 'required',
		'procurement_posting' => 'required',
		'method_of_payment_vendor' => 'required',
		'method_of_payment_customer' => 'required',
		'settlement_type' => 'required',
		'bank_posting' => 'required',
		'type_of_account' => 'required',
		'debit_account_description' => 'required',
		'credit_account_description' => 'required',
		'main_account' => 'required', 
		'posting_header_id' => 'required', 
		'posting_profile' => 'required', 
		'description' => 'required', 
		'offset_account' => 'required', 
		'offset_account_code' => 'required', 
		'offset_account_type' => 'required', 
		'document' => 'required', 
        'link_account_number' => 'required', 
		'link_account' => 'required', 
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'client field is required'
        ];
    }    
}
