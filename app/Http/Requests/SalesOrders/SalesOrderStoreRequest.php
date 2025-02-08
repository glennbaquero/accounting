<?php

namespace App\Http\Requests\SalesOrders;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderStoreRequest extends FormRequest
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
            'sales_order_number' => $for_updating ? 'unique:sales_orders,sales_order_number,'.$this->id : 'required|unique:sales_orders,sales_order_number',
            'customer_account' => 'required',
            'invoice_account' => 'required',
            'sales_order_date' => 'required',
            'delivery_date' => 'required',
            'due_date' => 'required',
            // 'approval_status_date' => 'required',
            // 'confirmed_date' => 'required',
            // 'accounting_date' => 'required',
            'customer_name' => 'required',
            'customer_address' => 'required',
            'customer_contact_id' => 'required',
            'cost_center_id' => 'required',
            'department_id' => 'required',
            'expense_purpose_id' => 'required',
            'method_of_payment' => 'required',
            'terms_of_payment' => 'required',
            'sales_type' => 'required',
            'sales_order_status' => 'required',
            'document_status' => 'required',
            // 'approval_status' => 'required',
            'customer_name' => 'required',
            'customer_address' => 'required',
            'customer_contact_id' => 'required',
            'client_id' => 'required',
        ];
    }
}
