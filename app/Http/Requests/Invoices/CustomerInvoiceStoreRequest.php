<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class CustomerInvoiceStoreRequest extends FormRequest
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
            // 'posting_date' => 'required|date',
            'invoice_payment_release_date' => 'nullable|date',
            'payment_due_date' => 'required|date',
            'client_id' => 'required',
            'transaction_type' => 'required',
            'cost_center_id' => 'required',
            'department_id' => 'required',
            'expense_purpose_id' => 'required',
            'invoice_status' => 'required',
            'settlement_type' => 'required',
            'method_of_payment' => 'required',
            'terms_of_payment' => 'required',
            'customer_account' => 'required',
            'customer_name' => 'required',
            'customer_contact_id' => 'required',
            'customer_address' => 'required',
            'posting_profile' => 'required',
            'invoice_date' => 'nullable|date',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client field is required',
            'cost_center_id.required' => 'The cost center field is required',
            'department_id.required' => 'The department field is required',
            'expense_purpose_id.required' => 'The expense purpose field is required',
        ];
    }
}
