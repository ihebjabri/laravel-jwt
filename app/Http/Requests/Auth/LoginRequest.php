<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use ApiFormRequestTrait; 

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
