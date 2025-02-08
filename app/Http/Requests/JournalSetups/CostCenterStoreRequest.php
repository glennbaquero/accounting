<?php

namespace App\Http\Requests\JournalSetups;

use Illuminate\Foundation\Http\FormRequest;

class CostCenterStoreRequest extends FormRequest
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
            'code' => $for_updating ? 'unique:cost_centers,code,'.$this->id : 'required|unique:cost_centers,code',
            'name' => 'required',
            'active_from' => 'nullable|required_with:active_to|before:active_to',
            'active_to' => 'nullable|required_with:active_from|after:active_from',
        ];
    }
}
