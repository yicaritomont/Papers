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
        /* //Se valida si es un usuario con rol compañia agregue al request la compañuia en sesión
        if( auth()->user()->hasRole('Compania') ){
            $request['inspector_id'] = auth()->user()->companies->pluck('id');
        } */
        if( auth()->user()->hasRole('Inspector') ){
            $request['inspector_id'] = auth()->user()->inspectors->id;
        }

        return [
            'start_date'    => 'required|date|date_format:Y-m-d',
            'end_date'      => 'required|date|date_format:Y-m-d',
            'inspector_id'  => 'required',
            'country'       => 'required',
            'city_id'       => 'required',
        ];
    }
}
