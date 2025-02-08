<?php

namespace App\Http\Requests\MainAccountCategories;

use App\Models\MainAccountCategories\MainAccountCategory;
use Illuminate\Foundation\Http\FormRequest;

class MainAccountCategoryStoreRequest extends FormRequest
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
             'main_account_category_reference' => 'required',
             'main_account_category' => 'required',
             'main_account_type' => 'required',
             'description' => 'required',
             'client_id' => 'required',
        ];

        if(!$this->id) {
            $mac = MainAccountCategory::where('company_id', auth()->user()->company_id)
            ->where('client_id', $this->client_id)
            ->where('main_account_category_reference', $this->main_account_category_reference)->count();

            if($mac) {
                $mac = $rules['main_account_category_reference'] = 'unique:main_account_categories,main_account_category_reference' . ($this->main_account_category_reference ? ',' . $this->main_account_category_reference : '');
            }

           
        }

        return $rules;
    }

    public function messages() {
        return [
            'main_account_category_reference.unique' => 'Reference ID is already used, please enter another Reference ID that is not used.',
            'main_account_category_reference.required' => 'Reference ID is required',
            'main_account_category.required' => 'Main Account Category is required.',
            'main_account_type.required' => 'Main Account Type is required.',
            'description.required' => 'Description is required.',
            'client_id.required' => 'Client is required.',
        ];
    }    
}
