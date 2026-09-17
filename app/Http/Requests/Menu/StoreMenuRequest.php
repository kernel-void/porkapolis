<?php

namespace App\Http\Requests\Menu;

use App\Http\Requests\BaseRequest;

class StoreMenuRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'nama_menu'  => 'required|string',
            'stok'       => 'required|string|max:255|regex:/^[0-9]+$/',
            'harga'      => 'required|string|max:255|regex:/^[0-9]+$/',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_menu.required' => 'Nama Menu tidak boleh kosong.',
            'stok.required'      => 'Stok tidak boleh kosong.',
            'harga.required'     => 'Harga tidak boleh kosong.',
            'harga.regex'        => 'Harga harus berupa angka saja.',
        ];
    }
}
