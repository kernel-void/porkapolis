<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Pemasukan;
use App\Models\PemasukanDetail;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
    public function index()
    {
        $pengaturan = Setting::first();
        $pemasukan = Pemasukan::with('details.menu')->latest()->get();
        $menu = Menu::latest()->get();
        $deletedData = Pemasukan::onlyTrashed()->with('details.menu')->orderBy('deleted_at', 'desc')->get();
        return view('admin.pemasukan.index', compact('pengaturan', 'pemasukan', 'menu', 'deletedData'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.qty'     => 'required|integer|min:1',
        ], [
            'tanggal.required'         => 'Tanggal tidak boleh kosong.',
            'items.required'           => 'Minimal harus ada 1 menu.',
            'items.*.menu_id.required' => 'Menu tidak boleh kosong.',
            'items.*.qty.required'     => 'Jumlah tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($request) {
                $totalKeseluruhan = 0;
                $itemsData = [];

                // Validasi stok dulu, sebelum simpan apapun
                foreach ($request->items as $item) {
                    $menu = Menu::findOrFail($item['menu_id']);

                    if ($menu->stok < $item['qty']) {
                        throw new \Exception("Stok {$menu->nama_menu} tidak mencukupi. Sisa: {$menu->stok}");
                    }

                    $subtotal = $menu->harga * $item['qty'];
                    $totalKeseluruhan += $subtotal;

                    $itemsData[] = [
                        'menu'     => $menu,
                        'qty'      => $item['qty'],
                        'subtotal' => $subtotal,
                    ];
                }

                // Buat header
                $pemasukan = Pemasukan::create([
                    'tanggal'    => $request->tanggal,
                    'total'      => $totalKeseluruhan,
                    'keterangan' => $request->keterangan,
                ]);

                // Buat detail + kurangi stok
                foreach ($itemsData as $data) {
                    $pemasukan->details()->create([
                        'menu_id'  => $data['menu']->id,
                        'qty'      => $data['qty'],
                        'subtotal' => $data['subtotal'],
                    ]);

                    $data['menu']->decrement('stok', $data['qty']);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.qty'     => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($request, $id) {
                $pemasukan = Pemasukan::with('details')->findOrFail($id);

                // Kembalikan stok dari detail lama
                foreach ($pemasukan->details as $oldDetail) {
                    Menu::where('id', $oldDetail->menu_id)->increment('stok', $oldDetail->qty);
                }

                // Validasi stok untuk data baru
                $totalKeseluruhan = 0;
                $itemsData = [];

                foreach ($request->items as $item) {
                    $menu = Menu::findOrFail($item['menu_id']);

                    if ($menu->stok < $item['qty']) {
                        throw new \Exception("Stok {$menu->nama_menu} tidak mencukupi. Sisa: {$menu->stok}");
                    }

                    $subtotal = $menu->harga * $item['qty'];
                    $totalKeseluruhan += $subtotal;

                    $itemsData[] = [
                        'menu'     => $menu,
                        'qty'      => $item['qty'],
                        'subtotal' => $subtotal,
                    ];
                }

                // Hapus detail lama, buat baru
                $pemasukan->details()->delete();

                foreach ($itemsData as $data) {
                    $pemasukan->details()->create([
                        'menu_id'  => $data['menu']->id,
                        'qty'      => $data['qty'],
                        'subtotal' => $data['subtotal'],
                    ]);

                    $data['menu']->decrement('stok', $data['qty']);
                }

                $pemasukan->update([
                    'tanggal'    => $request->tanggal,
                    'total'      => $totalKeseluruhan,
                    'keterangan' => $request->keterangan,
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.pemasukan.index')->with('success', 'Data pemasukan berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        $userName = auth()->user()->name;

        $keteranganLama = $pemasukan->keterangan ? $pemasukan->keterangan . ' | ' : '';
        $pemasukan->keterangan = $keteranganLama . '[Dihapus oleh: ' . $userName . ']';
        $pemasukan->save();

        $pemasukan->delete();

        return redirect()->back()->with('success', 'Data pemasukan berhasil dihapus');
    }

    public function restore($id)
    {
        $data = Pemasukan::onlyTrashed()->where('id', $id)->first();

        if ($data) {
            $data->keterangan = null;
            $data->save();
            $data->restore();
        }

        return redirect()->back()->with('success', 'Data berhasil direstore');
    }
}