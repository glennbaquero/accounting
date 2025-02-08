<?php

namespace App\Http\Requests\FiscalCalendars;

use Illuminate\Foundation\Http\FormRequest;

class FiscalCalendarStoreRequest extends FormRequest
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
            'fiscal_calendar_code' => 'required',
            'fiscal_calendar_name' => 'required',
            'client_id' => 'required',
            'unit' => 'required',
            'fiscal_year_status' => 'required',
            'fiscal_year_start_date' => 'required',
            'fiscal_year_end_date' => 'required',
            'length_of_period' => 'required',
        ];
    }
}
