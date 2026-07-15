<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengaturan = Setting::first();
        $pengeluaran = Pengeluaran::latest()->get();
        return view('admin.pengeluaran.index', compact('pengaturan', 'pengeluaran'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string',
            'jumlah'     => 'required|string|max:255|regex:/^[0-9]+$/',
        ], [
            'tanggal.required'    => 'Tanggal tidak boleh kosong.',
            'keterangan.required' => 'Keterangan tidak boleh kosong.',
            'jumlah.required'     => 'Jumlah tidak boleh kosong.',
            'jumlah.regex'        => 'Jumlah harus berupa angka saja.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        Pengeluaran::create([
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jumlah'     => $request->jumlah,
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string',
            'jumlah'     => 'required|string|max:255|regex:/^[0-9]+$/',
        ], [
            'tanggal.required'    => 'Tanggal tidak boleh kosong.',
            'keterangan.required' => 'Keterangan tidak boleh kosong.',
            'jumlah.required'     => 'Jumlah tidak boleh kosong.',
            'jumlah.regex'        => 'Jumlah harus berupa angka saja.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $pengeluaran = Pengeluaran::findOrFail($id);

        $pengeluaran->update([
            'tanggal'    => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jumlah'     => $request->jumlah,
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        $pengeluaran->delete(); // hard delete permanen

        return redirect()
            ->back()
            ->with('success', 'Data pengeluaran berhasil dihapus permanen');
    }

    public function exportPdf(Request $request)
    {
        $pengaturan = Setting::first();

        $query = Pengeluaran::latest();

        if ($request->filled('pengeluaran_ids')) {
            $query->whereIn('id', $request->pengeluaran_ids);
        }

        $pengeluaran = $query->get();

        $html = view('admin.pengeluaran.pdf', compact('pengeluaran', 'pengaturan'))->render();

        $mpdf = new Mpdf([
            'mode'        => 'utf-8',
            'format'      => 'A4',
            'orientation' => 'P',
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('laporan-pengeluaran-' . now()->format('Y-m-d') . '.pdf', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan-pengeluaran-' . now()->format('Y-m-d') . '.pdf"');
    }
}