<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class VendorInvoiceStoreRequest extends FormRequest
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
            'payment_due_date' => 'required|date',
            'invoice_status' => 'required',
            'delivery_date' => 'required',
            'settlement_type' => 'required',
            'method_of_payment' => 'required',
            'terms_of_payment' => 'required',
            'client_id' => 'required',
            'transaction_type' => 'required',
            'cost_center_id' => 'required',
            'department_id' => 'required',
            'expense_purpose_id' => 'required',
            'invoice_status' => 'required',
            'settlement_type' => 'required',
            'method_of_payment' => 'required',
            'terms_of_payment' => 'required',
            'vendor_account' => 'required',
            'vendor_name' => 'required',
            'vendor_contact_id' => 'required',
            'vendor_address' => 'required',
            'invoiced_by' => 'required',
            'posting_profile_id' => 'required',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client field is required',
            'cost_center_id.required' => 'The cost center field is required',
            'department_id.required' => 'The department field is required',
            'expense_purpose_id.required' => 'The expense purpose field is required',
            'posting_profile_id.required' => 'The posting profile field is required',
        ];
    }
}
