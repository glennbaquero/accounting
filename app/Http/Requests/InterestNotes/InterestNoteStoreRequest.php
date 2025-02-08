<?php

namespace App\Http\Requests\InterestNotes;

use Illuminate\Foundation\Http\FormRequest;

class InterestNoteStoreRequest extends FormRequest
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

            'interest_note' => 'required',
            'interest_date' => 'required | date',
            'interest_updated_date' => 'required | date',
            'start_date' => 'required | date | before:end_date',
            'end_date' => 'required | date | after:start_date',
            'days' => 'required | numeric',
            'description' => 'required',
            'interest_note_voucher' => 'required',

            'fee_note' => 'required | numeric',
            'fee_write_off_amount' => 'required | numeric',
            'fee_adjustment_status' => 'required',
            'total' => 'required | numeric',
            'sales_tax_amount' => 'required | numeric',

            'interest_note_status' => 'required',
            'adjustment_status' => 'required',
            'canceled' => 'nullable | date',
            'block' => 'nullable',
            'posting_profile_from' => 'required',
            'customer_posting_profile_id' => 'required_if:posting_profile_from,==,Select',

            'customer_account' => 'required',
            'location_id' => 'nullable',
            'name_or_description' => 'nullable',
            'street' => 'required',
            'zip_post_code' => 'required',
            'city' => 'required',
            'county' => 'required',
            'state' => 'required',
            'country_region' => 'required',
            'address' => 'required',

            'invoice_number' => 'required',
            'invoice_date' => 'required | date',
            'invoice_due_date' => 'required | date',
            'original_amount' => 'required',
            'amount_of_interest' => 'required',
            'interest' => 'required',
            'interest_on_transaction_voucher' => 'required',
            'voucher' => 'required',
            'written_off' => 'required',

            'cost_center' => 'required',
            'department' => 'required',
            'expense_purpose' => 'required',
            'posting_profile' => 'nullable',
            'document' => 'nullable',
            'document_status' => 'nullable',
            'accounting_distribution' => 'nullable',
        ];
    }

    public function messages() 
    {
        return [
            'client_id.required' => 'The client is required' 
        ];
    }
}
