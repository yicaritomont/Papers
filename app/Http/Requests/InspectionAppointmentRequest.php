<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InspectionAppointmentRequest extends FormRequest
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
            'appointment_location_id'   => 'required',
            'contract_id'               => 'required',
            'client_id'                 => 'required',
            'estimated_start_date'      => 'required|date|date_format:Y-m-d',
            'estimated_end_date'        => 'required|date|date_format:Y-m-d',
        ];
    }
}
