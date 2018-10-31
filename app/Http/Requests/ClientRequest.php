<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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

        if(isset($this->route('client')->id)){
            return [
                'name' => 'required',
                'lastname' => 'required',
                'phone' => 'required',
                'email' => 'required|email|unique:clients,email,'.$this->route('client')->id,
                'cell_phone' => 'required',
                // 'slug' => 'required|unique:clients,slug,'.$this->route('client')->id
            ];
        }

        return [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:clients,email',
            'cell_phone' => 'required',
            // 'slug' => 'required|unique:clients,slug'
        ];
        
    }
}
