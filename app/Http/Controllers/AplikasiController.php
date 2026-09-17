<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Services\SettingService;

class AplikasiController extends Controller
{
    public function __construct(private SettingService $settingService)
    {
    }

    public function index()
    {
        return view('admin.pengaturan.index', [
            'pengaturan' => $this->settingService->current(),
        ]);
    }

    public function update(UpdateSettingRequest $request)
    {
        $data = $request->validated();

        $this->settingService->update([
            'nama_aplikasi' => $data['nama_aplikasi'],
            'ikon_sidebar'  => strtolower($data['ikon_sidebar']),
            'tema'          => $data['tema'],
            'footer'        => $data['footer'],
        ], $request->file('logo'));

        return redirect()->route('admin.pengaturan')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
