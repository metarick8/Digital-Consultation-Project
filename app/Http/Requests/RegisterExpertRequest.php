<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterExpertRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'name' => ['required', 'string', 'max:25'],
            'email' => ['required', 'string', 'email', 'unique:experts'],
            'password' => ['required', 'min:8'],
            'phone_number' => ['required', 'numeric'],
            'address' => ['required'],
            'price' => ['required', 'numeric', 'gte:0'],
        ];
    }
}
