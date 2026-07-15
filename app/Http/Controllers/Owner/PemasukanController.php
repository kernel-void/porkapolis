<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Pemasukan;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;

class PemasukanController extends Controller
{
    public function index()
    {
        $pengaturan = Setting::first();
        $pemasukan = Pemasukan::latest()->get();   // data aktif
        $menu = Menu::latest()->get();   // data aktif
        $deletedData = Pemasukan::onlyTrashed()->orderBy('deleted_at', 'desc')->get(); // data terhapus
        return view('owner.pemasukan.index', compact('pengaturan', 'pemasukan', 'menu', 'deletedData'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu_id'   => 'required|exists:menus,id',
            'tanggal'   => 'required|date',
            'qty'       => 'required|string',
            'total'     => 'required|string|max:255|regex:/^[0-9]+$/',
            'keterangan'=> 'nullable|string',
        ], [
            'menu_id.required' => 'Menu tidak boleh kosong.',
            'menu_id.exists'   => 'Menu tidak valid.',
            'tanggal.required'=> 'Tanggal tidak boleh kosong.',
            'qty.required'    => 'Jumlah yang terjual tidak boleh kosong.',
            'total.required'  => 'Total tidak boleh kosong.',
            'total.regex'     => 'Total harus berupa angka saja.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first()); // tampilkan error flash
        }

        // AMBIL MENU YANG DIPILIH
        $menu = Menu::find($request->menu_id);

        // CEK JIKA STOK TIDAK CUKUP
        if ($menu->stok < $request->qty) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Stok tidak mencukupi. Stok tersisa: ' . $menu->stok);
        }
    
        // Simpan ke tabel siswa
        Pemasukan::create([
            'menu_id'   => $request->menu_id,
            'tanggal'   => $request->tanggal,
            'qty'       => $request->qty,
            'total'     => $request->total,
            'keterangan'=> $request->keterangan,
        ]);

        // KURANGI STOK MENU
        $menu->update([
            'stok' => $menu->stok - $request->qty,
        ]);

        return redirect()->route('owner.pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'menu_id'   => 'required|exists:menus,id',
            'tanggal'   => 'required|date',
            'qty'       => 'required|numeric',
            'total'     => 'required|numeric',
            'keterangan'=> 'nullable|string',
        ], [
            'menu_id.required' => 'Menu tidak boleh kosong.',
            'menu_id.exists'   => 'Menu tidak valid.',
            'tanggal.required'=> 'Tanggal tidak boleh kosong.',
            'qty.required'     => 'Jumlah yang terjual tidak boleh kosong.',
            'total.required'   => 'Total tidak boleh kosong.',
            'total.regex'      => 'Total harus berupa angka saja.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $pemasukan = Pemasukan::findOrFail($id);
        $menu = Menu::findOrFail($request->menu_id);

        // --- HITUNG STOK YANG BENAR ---
        // Kembalikan stok lama
        $stok_after_rollback = $menu->stok + $pemasukan->qty;

        // Periksa apakah stok cukup setelah diubah
        if ($stok_after_rollback < $request->qty) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Stok tidak mencukupi. Stok tersisa: ' . $stok_after_rollback);
        }

        // Hitung stok akhir
        $stok_final = $stok_after_rollback - $request->qty;

        // Update pemasukan
        $pemasukan->update([
            'menu_id'     => $request->menu_id,
            'tanggal'     => $request->tanggal,
            'qty'         => $request->qty,
            'total'       => $request->total,
            'keterangan'  => $request->keterangan,
        ]);

        // Update stok menu
        $menu->update([
            'stok' => $stok_final,
        ]);

        return redirect()->route('owner.pemasukan.index')->with('success', 'Data pemasukan berhasil diperbarui.');
    }
    
    public function exportPdf(Request $request)
    {
        $request->validate([
            'pemasukan_ids' => 'required|array'
        ]);
    
        $pemasukan = Pemasukan::with('menu')
            ->whereIn('id', $request->pemasukan_ids)
            ->get();
    
        $html = view('owner.pemasukan.pdf', compact('pemasukan'))->render();
    
        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
        ]);
    
        $mpdf->WriteHTML($html);
    
        return response($mpdf->Output(
            'pemasukan-terpilih.pdf',
            'S'
        ))->header('Content-Type', 'application/pdf');
    }

    public function destroy(Request $request, $id)
    {
        
        $pemasukan = Pemasukan::findOrFail($id);

        $userName = auth()->user()->name;

        // Simpan keterangan penghapusan ke kolom keterangan (opsional)
        $pemasukan->keterangan = '[Dihapus oleh: ' . $userName . ']';
        $pemasukan->save();

        $pemasukan->delete(); // soft delete

        return redirect()
            ->back()
            ->with('success', 'Data pemasukan berhasil dihapus');
    }

    public function restore($id)
    {
        $data = Pemasukan::onlyTrashed()->where('id', $id)->first();

        if ($data) {
            $data->restore();
        }

        return redirect()->back()->with('success', 'Data berhasil direstore');
    }

}