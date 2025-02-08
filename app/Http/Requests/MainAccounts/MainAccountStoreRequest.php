<?php

namespace App\Http\Requests\MainAccounts;

use Illuminate\Foundation\Http\FormRequest;

class MainAccountStoreRequest extends FormRequest
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
            'chart_of_account_id' => 'required',
            'main_account_code_number' => 'required',
            'main_account_id' => 'required',
            'main_account_code' => 'required',
            'main_account_name' => 'required',
            'main_account_type' => 'required',
            'reporting_type' => 'required',
            'main_account_category_id' => 'required',
            'db_cr_proposal' => 'required',
            'db_cr_requirement' => 'required',
            'balance_control' => 'required',
            'offset_account' => 'required',
            'opening_account' => 'required',
            'default_consolidation_account' => 'required',
            'posting_type' => 'required',
            'validate_posting' => 'required',
            'item_sales_tax_group' => 'required',
            'sales_tax_direction' => 'required',      
            'sales_tax_code' => 'required',
            'validate_sales_tax' => 'required',
            'debit_credit_decrease_rule' => 'required',      
            'debit_credit_increase_rule' => 'required',                      
            'not_sufficient_account' => 'nullable | numeric',                      
        ];
    }

    public function messages() {
        return [
            'main_account_id.required' => 'Main Account is a required field',
            'chart_of_account_id.required' => 'Chart of Account is a required field',
        ];
    }    
}
