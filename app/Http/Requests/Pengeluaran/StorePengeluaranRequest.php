<?php

namespace App\Http\Requests\Pengeluaran;

use App\Http\Requests\BaseRequest;

class StorePengeluaranRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string',
            'jumlah'     => 'required|string|max:255|regex:/^[0-9]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required'    => 'Tanggal tidak boleh kosong.',
            'keterangan.required' => 'Keterangan tidak boleh kosong.',
            'jumlah.required'     => 'Jumlah tidak boleh kosong.',
            'jumlah.regex'        => 'Jumlah harus berupa angka saja.',
        ];
    }
}
