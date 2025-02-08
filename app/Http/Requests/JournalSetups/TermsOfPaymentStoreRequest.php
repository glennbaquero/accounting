<?php

namespace App\Http\Requests\JournalSetups;

use Illuminate\Foundation\Http\FormRequest;

class TermsOfPaymentStoreRequest extends FormRequest
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
        $for_updating = $this->id;

        return [
            'terms_of_payment' => $for_updating ? 'unique:terms_of_payments,terms_of_payment,'.$this->id : 'required|unique:terms_of_payments,terms_of_payment',
            'months' => 'required',
            'days' => 'required',
            'payment_method_id' => 'required',
            'cutoff_day' => 'required',
        ];
    }

    public function messages() 
    {
        return [
            'payment_method_id.required' => 'The payment method field is required.'
        ];
    }
}
