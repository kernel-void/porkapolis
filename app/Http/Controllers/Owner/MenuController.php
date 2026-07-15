<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $pengaturan = Setting::first();
        $menu = Menu::latest()->get();   // data aktif
        $deletedData = Menu::onlyTrashed()->orderBy('deleted_at', 'desc')->get(); // data terhapus
        return view('owner.menu.index', compact('pengaturan', 'menu', 'deletedData'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_menu' => 'required|string',
            'stok'      => 'required|string|max:255|regex:/^[0-9]+$/',
            'harga'     => 'required|string|max:255|regex:/^[0-9]+$/',
            'keterangan'=> 'nullable|string',
        ], [
            'nama_menu.required'        => 'Nama Menu tidak boleh kosong.',
            'stok.required'     => 'Stok tidak boleh kosong.',
            'harga.required'          => 'Harga tidak boleh kosong.',
            'harga.regex'             => 'Harga harus berupa angka saja.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first()); // tampilkan error flash
        }

        // Simpan ke tabel siswa
        Menu::create([
            'nama_menu' => $request->nama_menu,
            'stok'      => $request->stok,
            'harga'     => $request->harga,
            'keterangan'=> $request->keterangan,
        ]);

        return redirect()->route('owner.menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_menu' => 'required|string',
            'stok'      => 'required|string|max:255|regex:/^[0-9]+$/',
            'harga'     => 'required|string|max:255|regex:/^[0-9]+$/',
            'keterangan'=> 'nullable|string',
        ], [
            'nama_menu.required'        => 'Nama Menu tidak boleh kosong.',
            'stok.required'     => 'Stok tidak boleh kosong.',
            'harga.required'          => 'Harga tidak boleh kosong.',
            'harga.regex'             => 'Harga harus berupa angka saja.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first()); // tampilkan error flash
        }

        $menu = Menu::findOrFail($id);

        // Update data siswa
        $menu->update([
            'nama_menu' => $request->nama_menu,
            'stok'      => $request->stok,
            'harga'     => $request->harga,
            'keterangan'=> $request->keterangan,
        ]);

        return redirect()->route('owner.menu.index')->with('success', 'Data menu berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        
        $menu = Menu::findOrFail($id);

        $userName = auth()->user()->name;

        // Simpan keterangan penghapusan ke kolom keterangan (opsional)
        $menu->keterangan = '[Dihapus oleh: ' . $userName . ']';
        $menu->save();

        $menu->delete(); // soft delete

        return redirect()
            ->back()
            ->with('success', 'Data menu berhasil dihapus');
    }

    public function restore($id)
    {
        $data = Menu::onlyTrashed()->where('id', $id)->first();

        if ($data) {
            $data->restore();
        }

        return redirect()->back()->with('success', 'Data berhasil direstore');
    }

}