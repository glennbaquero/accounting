<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'product_number' => $for_updating ? 'unique:products,product_number,'.$this->id : 'required|unique:products,product_number',
            'batch_number' => 'required',
            'serial_number' => 'required',
            'name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'client_id' =>'client field is required',
        ];
    }
}
