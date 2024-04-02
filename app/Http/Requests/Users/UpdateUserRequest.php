<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Requests\ApiFormRequestTrait;

class UpdateUserRequest extends FormRequest
{
    use ApiFormRequestTrait;
    public function rules(): array
    {
        return [
            'name'  => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }
}
