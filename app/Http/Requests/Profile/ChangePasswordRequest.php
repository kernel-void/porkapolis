<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'password_lama' => 'required',
            'password_baru' => 'required|min:5|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'password_lama.required'  => 'Password lama wajib diisi.',
            'password_baru.required'  => 'Password baru wajib diisi.',
            'password_baru.min'       => 'Password baru minimal 5 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak sesuai.',
        ];
    }
}
