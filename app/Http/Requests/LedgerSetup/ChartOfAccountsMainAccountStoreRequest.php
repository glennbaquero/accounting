<?php

namespace App\Http\Requests\LedgerSetup;

use Illuminate\Foundation\Http\FormRequest;

class ChartOfAccountsMainAccountStoreRequest extends FormRequest
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
        // $is_update = $this->id;
        return [
            // 'coa_id'=> $is_update ? 'required|unique:chart_of_accounts,coa_id,' . $this->id 
            //                         : 'required|unique:chart_of_accounts,coa_id',
            // 'coa_code'=> 'required',
            // 'coa_name'=> 'required',
            // 'main_account_mask'=> 'required',
            // 'description' => 'required'
        ];
    }
}
