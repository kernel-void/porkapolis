<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class StoreUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:5',
            'role_id'  => 'required|in:1,2,3',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama tidak boleh kosong.',
            'username.required' => 'Username tidak boleh kosong.',
            'username.unique'   => 'Username sudah digunakan, silakan gunakan yang lain.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min'      => 'Password minimal 5 karakter.',
            'role_id.required'  => 'Role wajib dipilih.',
            'role_id.in'        => 'Role yang dipilih tidak valid.',
        ];
    }
}
