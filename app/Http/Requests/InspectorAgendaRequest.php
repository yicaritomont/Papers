<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InspectorAgendaRequest extends FormRequest
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
            'start_date' => 'required',
            'end_date' => 'required',
            'inspector_id' => 'required',
            'country' => 'required',
            'city_id' => 'required',
        ];
    }
}
