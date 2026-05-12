<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $registerableRoles = array_map(
            fn (UserRole $role) => $role->value,
            UserRole::registerable()
        );

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'max:20'],
            'role'     => ['required', Rule::in($registerableRoles)],
            'password' => [
                'required', 
                'string', 
                Password::defaults()
            ],
        ];
    }
}
