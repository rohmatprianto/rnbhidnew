<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && (bool) ($this->user()->is_admin ?? false);
    }

    public function rules(): array
    {
        return [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','confirmed', Password::defaults()],
            'phone'    => ['nullable','string','max:30'],
        'sosmed'   => ['nullable','string','max:255'],
        'photo_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // hardening: cegah injection field sensitif
             'status' => ['prohibited'],
            'is_admin'          => ['prohibited'],
            'email_verified_at' => ['prohibited'],
        ];
    }
}
