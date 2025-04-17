<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NumberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'expert_id' => ['numeric', 'gte:1'],
            'number' => ['required', 'numeric', 'gte:1', 'lte:5']
        ];
    }
}
