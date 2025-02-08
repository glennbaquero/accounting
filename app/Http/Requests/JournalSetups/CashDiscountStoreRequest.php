<?php

namespace App\Http\Requests\JournalSetups;

use Illuminate\Foundation\Http\FormRequest;

class CashDiscountStoreRequest extends FormRequest
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
        $is_update = $this->id;
        return [
            'next_discount_code' => $is_update ? 'required|unique:cash_discounts,next_discount_code,' . $this->id : 'required|unique:cash_discounts,next_discount_code',
            'months' => 'required',
            'days' => 'required',
            'description' => 'required',
            'net_or_current' => 'required',
            'discount_offset_accounts' => 'required',
            'discount_cash' => 'required_without:discount_percent',
            'discount_percent' => 'required_without:discount_cash',
            'customer_account' => 'required_without',
            'vendor_account' => 'required'
        ];
    }
}
