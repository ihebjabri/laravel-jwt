<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use App\Http\Requests\ApiFormRequestTrait;
class ChangePasswordRequest extends FormRequest
{
    use ApiFormRequestTrait;
    public function rules(): array
    {
        return [
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
