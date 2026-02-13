<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // policy di route/controller
    }

    public function rules(): array
    {
        return [
            'photo_path' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
