<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // admin-only: gunakan policy via controller/middleware,
        // tapi aman juga jika kita harden:
        return $this->user() && (bool) ($this->user()->is_admin ?? false);
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required', Password::defaults()],
            // optional:
            // 'is_admin' => ['sometimes','boolean'],
        ];
    }
}
