<?php

namespace App\Http\Requests\AccountStructures;

use Illuminate\Foundation\Http\FormRequest;

class AccountStructureStoreRequest extends FormRequest
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
            'ledger_account_structure_code_number' => 'required',
            'ledger_account_structure_code' => 'required',
            'ledger_account_structure_name' => 'required',
            'ledger_id' => 'required',
        ];
    }
}
