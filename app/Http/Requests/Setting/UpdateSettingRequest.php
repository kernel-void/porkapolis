<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\BaseRequest;

class UpdateSettingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'nama_aplikasi' => 'required|max:30',
            'ikon_sidebar'  => 'required',
            'tema'          => 'required',
            'footer'        => 'required',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg|max:5048',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_aplikasi.required' => 'Nama Aplikasi tidak boleh kosong.',
            'nama_aplikasi.max'      => 'Nama aplikasi maksimal 30 karakter.',
            'ikon_sidebar.required'  => 'Ikon Sidebar tidak boleh kosong.',
            'tema.required'          => 'Tema tidak boleh kosong.',
            'footer.required'        => 'Footer tidak boleh kosong.',
            'logo.image'             => 'File logo harus berupa gambar.',
            'logo.mimes'             => 'Logo harus berekstensi PNG, JPG, atau JPEG.',
            'logo.max'               => 'Ukuran logo maksimal 5MB.',
        ];
    }
}
