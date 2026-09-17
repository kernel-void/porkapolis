<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UpdateUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->route('id') . ',id_users',
            'password' => 'nullable|min:5',
            'role_id'  => 'required|in:1,2,3',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama tidak boleh kosong.',
            'name.string'       => 'Nama harus berupa teks.',
            'name.max'          => 'Nama maksimal 255 karakter.',
            'username.required' => 'Username tidak boleh kosong.',
            'username.string'   => 'Username harus berupa teks.',
            'username.max'      => 'Username maksimal 255 karakter.',
            'username.unique'   => 'Username sudah digunakan, silakan gunakan yang lain.',
            'password.min'      => 'Password minimal 5 karakter.',
            'role_id.required'  => 'Role wajib dipilih.',
            'role_id.in'        => 'Role yang dipilih tidak valid.',
        ];
    }
}
