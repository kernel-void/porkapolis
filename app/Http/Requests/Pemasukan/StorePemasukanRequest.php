<?php

namespace App\Http\Requests\Pemasukan;

use App\Http\Requests\BaseRequest;

class StorePemasukanRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.qty'     => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required'         => 'Tanggal tidak boleh kosong.',
            'items.required'           => 'Minimal harus ada 1 menu.',
            'items.*.menu_id.required' => 'Menu tidak boleh kosong.',
            'items.*.qty.required'     => 'Jumlah tidak boleh kosong.',
        ];
    }
}
