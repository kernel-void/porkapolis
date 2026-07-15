<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class AplikasiController extends Controller
{
    public function index()
    {
        $pengaturan = Setting::first();

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_aplikasi' => 'required|max:30',
            'ikon_sidebar'  => 'required',
            'tema'          => 'required',
            'footer'        => 'required',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg|max:5048',
        ], [
            'nama_aplikasi.required' => 'Nama Aplikasi tidak boleh kosong.',
            'nama_aplikasi.max'      => 'Nama aplikasi maksimal 30 karakter.',
            'ikon_sidebar'           => 'Ikon Sidebar tidak boleh kosong.',
            'tema.required'          => 'Tema tidak boleh kosong.',
            'footer.required'        => 'Footer tidak boleh kosong.',
            'logo.image'             => 'File logo harus berupa gambar.',
            'logo.mimes'             => 'Logo harus berekstensi PNG, JPG, atau JPEG.',
            'logo.max'               => 'Ukuran logo maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $setting = Setting::first();

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($setting->logo && file_exists(public_path($setting->logo))) {
                unlink(public_path($setting->logo));
            }

            // Simpan logo baru
            $logo = $request->file('logo');
            $logo->move(public_path('assets/img/logo-login'), 'logo.png');

            // Update path logo
            $setting->logo = 'assets/img/logo-login/logo.png';
        }

        // Update data lainnya
        $setting->nama_aplikasi = $request->nama_aplikasi;
        $setting->ikon_sidebar = strtolower($request->ikon_sidebar);
        $setting->tema = $request->tema;
        $setting->footer = $request->footer;
        $setting->save();

        return redirect()->route('admin.pengaturan')->with('success', 'Pengaturan berhasil diperbarui!');
    }

}

