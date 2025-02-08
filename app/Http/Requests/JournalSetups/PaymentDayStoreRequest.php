<?php

namespace App\Http\Requests\JournalSetups;

use Illuminate\Foundation\Http\FormRequest;

class PaymentDayStoreRequest extends FormRequest
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
        $week_month = $this->week_month;

        $out = [
            'payment_day' => 'required',
            'week_month' => 'required',
            'description' => 'required'
        ];


        if ($week_month == 'Week') {
            $out['day_of_week'] = 'required';
        } else {
            $out['day_of_month'] = 'required'; 
        }

        return $out;
    }
}
