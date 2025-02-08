<?php

namespace App\Http\Requests\GeneralLedgers;

use Illuminate\Foundation\Http\FormRequest;

class GeneralLedgerStoreRequest extends FormRequest
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
            'name' => 'required',
            'client_id' => 'required',
            'ledger_id' => 'required',
            'ledger_calendar_id' => 'required',
            'period_from' => 'required',
            'period_to' => 'required',
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'The client field is required.',
            'ledger_id.required' => 'The ledger field is required.',
            'ledger_calendar_id.required' => 'The ledger calendar field is required.',
        ];
    }    
}
