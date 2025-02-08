<?php

namespace App\Http\Requests\PaymentSchedules;

use Illuminate\Foundation\Http\FormRequest;

class PaymentScheduleLineStoreRequest extends FormRequest
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
            'payment_schedule_id' => 'required',
            'due_date' => 'required | date',
            'duration' => 'required | numeric',
            'principal_amount' => 'required | numeric',
            'interest' => 'required | numeric',
            'payment' => 'required | numeric',
            'balance' => 'required | numeric',
            'line_status' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
