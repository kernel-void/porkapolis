<?php

namespace App\Http\Controllers\Kasir;

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
        $menu = Menu::latest()->get();
        $deletedData = Menu::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return view('kasir.menu.index', compact('pengaturan', 'menu', 'deletedData'));
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
                ->with('error', $validator->errors()->first());
        }

        Menu::create([
            'nama_menu' => $request->nama_menu,
            'stok'      => $request->stok,
            'harga'     => $request->harga,
            'keterangan'=> $request->keterangan
        ]);

        return redirect()->route('kasir.menu.index')->with('success', 'Menu berhasil ditambahkan');
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
                ->with('error', $validator->errors()->first());
        }

        $menu = Menu::findOrFail($id);

        $menu->update([
            'nama_menu' => $request->nama_menu,
            'stok'      => $request->stok,
            'harga'     => $request->harga,
            'keterangan'=> $request->keterangan
        ]);

        return redirect()->route('kasir.menu.index')->with('success', 'Data menu berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        
        $menu = Menu::findOrFail($id);
        
        if ($menu->pemasukans()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Menu tidak dapat dihapus karena sudah digunakan pada data pemasukan.');
        }

        $userName = auth()->user()->name;

        $keteranganLama = $menu->keterangan 
            ? $menu->keterangan . ' | ' 
            : '';

        $menu->keterangan = $keteranganLama . '[Dihapus oleh: ' . $userName . ']';
        $menu->save();

        $menu->delete();

        return redirect()
            ->back()
            ->with('success', 'Data menu berhasil dihapus');
    }
}