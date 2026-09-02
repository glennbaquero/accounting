<?php

namespace App\Http\Requests\RecurringJournalTemplates;

use Illuminate\Foundation\Http\FormRequest;

class RecurringJournalTemplateStoreRequest extends FormRequest
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
            'template_name' => 'required|string|max:255',
            'frequency' => 'required|in:Daily,Weekly,Monthly,Quarterly,Annually',
            'start_date' => 'required|date',
        ];
    }

    public function messages() {
        return [

        ];
    }
}
