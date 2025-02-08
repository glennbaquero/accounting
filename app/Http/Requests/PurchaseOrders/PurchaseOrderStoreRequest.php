<?php

namespace App\Http\Requests\PurchaseOrders;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderStoreRequest extends FormRequest
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
            'purchase_order_number' => $for_updating ? 'unique:purchase_orders,purchase_order_number,'.$this->id : 'required|unique:purchase_orders,purchase_order_number',
            'vendor_account' => 'required',
            'purchase_order_date' => 'required',
            'delivery_date' => 'required',
            'due_date' => 'required',
            'vendor_name' => 'required',
            'vendor_contact_id' => 'required',
            'ordered_by' => 'required',
            'cost_center' => 'required',
            'department' => 'required',
            'expense_purpose' => 'required',
            'method_of_payment' => 'required',
            'terms_of_payment' => 'required',
            'purchase_type' => 'required',
            'purchase_order_status' => 'required',
            'purchase_type' => 'required',
            'client_id' => 'required',
            'posting_profile_id' => 'required'
        ];
    }

    public function messages() {
        return [
            'client_id.required' => 'client field is required field',
            'vendor_contact_id.required' => 'vendor contact field is requied field',
            'posting_profile_id.required' => 'posting profile field is requied field',
        ];
    }
}
