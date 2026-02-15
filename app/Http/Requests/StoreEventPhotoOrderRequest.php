<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventPhotoOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // boleh guest
    }

    public function rules(): array
    {
        return [
            'guardian_name'    => ['required','string','max:120'],
            'email'            => ['required','email','max:190'],
            'phone'            => ['required','string','max:30'],
            'rider_full_name'  => ['required','string','max:120'],
            'rider_nickname'   => ['required','string','max:60'],
            'category'         => ['required','string','max:80'],
            'plate_no'         => ['nullable','string','max:30'],
            'batch'            => ['nullable','string','max:30'],
            'instagram'        => ['nullable','string','max:80'],
        ];
    }

    public function messages(): array
    {
        return [
            'guardian_name.required'   => 'Nama Wali Rider wajib diisi.',
            'email.required'           => 'Email wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'phone.required'           => 'No Handphone wajib diisi.',
            'rider_full_name.required' => 'Nama Lengkap Rider wajib diisi.',
            'rider_nickname.required'  => 'Nama Panggilan Rider wajib diisi.',
            'category.required'        => 'Kategori / Class wajib diisi.',
        ];
    }
}
