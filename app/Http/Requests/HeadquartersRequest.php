<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeadquartersRequest extends FormRequest
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
        if( auth()->user()->hasRole('Cliente') ){
            return [
                'name'      => 'required',
                'address'   => 'required',
                'latitude'   => 'required',
                'longitude'   => 'required',
            ];
        }

        return [
            'client_id' => 'required',
            'name'      => 'required',
            'address'   => 'required',
            'latitude'  => 'required',
            'longitude'  => 'required',
        ];
    }
}
