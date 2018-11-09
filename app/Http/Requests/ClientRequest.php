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
                'identification' => 'required',
                'email' => 'required|email|unique:users,email,'.$this->route('client')->id,
                'password' => 'required|min:6',
                'phone' => 'required',
                'cell_phone' => 'required',
                // 'slug' => 'required|unique:clients,slug,'.$this->route('client')->id
            ];
        }

        return [
            'name' => 'required',
            'identification' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'required',
            'cell_phone' => 'required',
            // 'slug' => 'required|unique:clients,slug'
        ];
        
    }
}
