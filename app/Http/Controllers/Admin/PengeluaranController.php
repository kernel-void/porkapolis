<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Validator;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengaturan = Setting::first();
        $pengeluaran = Pengeluaran::latest()->get();   // data aktif
        $deletedData = Pengeluaran::onlyTrashed()->orderBy('deleted_at', 'desc')->get(); // data terhapus
        return view('admin.pengeluaran.index', compact('pengaturan', 'pengeluaran', 'deletedData'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'       => 'required|date',
            'keterangan'    => 'required|string',
            'jumlah'         => 'required|string|max:255|regex:/^[0-9]+$/',
        ], [
            'tanggal.required'        => 'Tanggal tidak boleh kosong.',
            'keterangan.required'     => 'Keterangan tidak boleh kosong.',
            'jumlah.required'          => 'Jumlah tidak boleh kosong.',
            'jumlah.regex'             => 'Jumlah harus berupa angka saja.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first()); // tampilkan error flash
        }

        // Simpan ke tabel siswa
        Pengeluaran::create([
            'tanggal'   => $request->tanggal,
            'keterangan'=> $request->keterangan,
            'jumlah'     => $request->jumlah,
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'       => 'required|date',
            'keterangan'    => 'required|string',
            'jumlah'         => 'required|string|max:255|regex:/^[0-9]+$/',
        ], [
            'tanggal.required'        => 'Tanggal tidak boleh kosong.',
            'keterangan.required'     => 'Keterangan tidak boleh kosong.',
            'jumlah.required'          => 'Jumlah tidak boleh kosong.',
            'jumlah.regex'             => 'Jumlah harus berupa angka saja.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first()); // tampilkan error flash
        }

        $pengeluaran = Pengeluaran::findOrFail($id);

        // Update data siswa
        $pengeluaran->update([
            'tanggal'   => $request->tanggal,
            'keterangan'=> $request->keterangan,
            'jumlah'     => $request->jumlah,
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        
        $pengeluaran = Pengeluaran::findOrFail($id);

        $userName = auth()->user()->name;

        // Ambil keterangan lama (jika ada)
        $keteranganLama = $pengeluaran->keterangan 
            ? $pengeluaran->keterangan . ' | ' 
            : '';

        // Simpan keterangan penghapusan ke kolom keterangan (opsional)
        $pengeluaran->keterangan = $keteranganLama . '[Dihapus oleh: ' . $userName . ']';
        $pengeluaran->save();

        $pengeluaran->delete(); // soft delete

        return redirect()
            ->back()
            ->with('success', 'Data pengeluaran berhasil dihapus');
    }

    public function restore($id)
    {
        $data = Pengeluaran::onlyTrashed()->where('id', $id)->first();

        if ($data) {
            // Kosongkan kolom keterangan
            $data->keterangan = null;
            $data->save();

            // Restore data
            $data->restore();
        }

        return redirect()->back()->with('success', 'Data berhasil direstore');
    }

}