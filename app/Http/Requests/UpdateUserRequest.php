<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User|null $auth */
        $auth = $this->user();

        /** @var \App\Models\User|null $target */
        $target = $this->route('user'); // route model binding: {user}

        if (!$auth || !$target) {
            return false;
        }

        // gunakan policy: admin OR pemilik akun sendiri
        return $auth->can('update', $target);
    }

    public function rules(): array
    {
        /** @var \App\Models\User|null $target */
        $target = $this->route('user');

        $targetId = $target?->id;

        return [
            'name'  => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($targetId),
            ],

            'phone'  => ['nullable','string','max:30'],
        'sosmed' => ['nullable','string','max:255'],
        'photo_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // optional: kalau Anda ingin bisa ganti password dari users.edit (self/admin)
            // kalau tidak dipakai, boleh dihapus saja.
            'password' => ['nullable', 'confirmed', Password::defaults()],

            // hard block: jangan pernah terima privilege field dari form
            'status' => ['prohibited'],
            'is_admin' => ['prohibited'],
            'email_verified_at' => ['prohibited'],
            'remember_token' => ['prohibited'],
        ];
    }
}
