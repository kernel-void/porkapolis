<?php

namespace App\Http\Requests\Dashboard;

use App\Http\Requests\BaseRequest;

class FilterBulanRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'bulan' => 'required|date_format:Y-m',
        ];
    }

    public function messages(): array
    {
        return [
            'bulan.required'    => 'Bulan wajib dipilih.',
            'bulan.date_format' => 'Format bulan tidak valid.',
        ];
    }
}
