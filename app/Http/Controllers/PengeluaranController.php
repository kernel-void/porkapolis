<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pengeluaran\StorePengeluaranRequest;
use App\Http\Requests\Pengeluaran\UpdatePengeluaranRequest;
use App\Services\PengeluaranService;
use App\Services\SettingService;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function __construct(
        private PengeluaranService $pengeluaranService,
        private SettingService $settingService,
    ) {
    }

    public function index()
    {
        return view('admin.pengeluaran.index', [
            'pengaturan'  => $this->settingService->current(),
            'pengeluaran' => $this->pengeluaranService->paginate(),
            'trashed'     => $this->pengeluaranService->allTrashed(),
        ]);
    }

    public function store(StorePengeluaranRequest $request)
    {
        $this->pengeluaranService->create($request->validated());

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    public function update(UpdatePengeluaranRequest $request, $id)
    {
        $this->pengeluaranService->update((int) $id, $request->validated());

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Data pengeluaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->pengeluaranService->delete((int) $id);

        return redirect()
            ->back()
            ->with('success', 'Data pengeluaran berhasil dihapus. Masih bisa dipulihkan dari Data Terhapus.');
    }

    public function restore($id)
    {
        $this->pengeluaranService->restore((int) $id);

        return redirect()->back()->with('success', 'Data pengeluaran berhasil dipulihkan.');
    }

    public function exportPdf(Request $request)
    {
        return $this->pengeluaranService->exportPdf($request->input('pengeluaran_ids', []));
    }
}
