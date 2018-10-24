<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
        if(isset($this->route('company')->id)){
            // dd('V');
            return [
                'name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                'activity' => 'required',
                'slug' => 'required|unique:companies,slug,'.$this->route('company')->id
            ];
        }
        // dd('F');

        return [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'activity' => 'required',
            'slug' => 'required|unique:companies,slug'
        ];
    }
}
