<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
            return [
                'name' => ['required', 'string', 'max:25'],
                'email' => ['required', 'string', 'email', 'unique:users'],
                'password' => ['required', 'min:8']
            ];
    }
}
