<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pemasukan\StorePemasukanRequest;
use App\Http\Requests\Pemasukan\UpdatePemasukanRequest;
use App\Services\PemasukanService;
use App\Services\SettingService;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function __construct(
        private PemasukanService $pemasukanService,
        private SettingService $settingService,
    ) {
    }

    public function index()
    {
        return view('admin.pemasukan.index', [
            'pengaturan' => $this->settingService->current(),
            'pemasukan'  => $this->pemasukanService->paginate(),
            'trashed'    => $this->pemasukanService->allTrashed(),
            'menu'       => $this->pemasukanService->menus(),
        ]);
    }

    public function store(StorePemasukanRequest $request)
    {
        try {
            $this->pemasukanService->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan');
    }

    public function update(UpdatePemasukanRequest $request, $id)
    {
        try {
            $this->pemasukanService->update((int) $id, $request->validated());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.pemasukan.index')->with('success', 'Data pemasukan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->pemasukanService->delete((int) $id);

        return redirect()->back()->with('success', 'Data pemasukan berhasil dihapus. Masih bisa dipulihkan dari Data Terhapus.');
    }

    public function restore($id)
    {
        $this->pemasukanService->restore((int) $id);

        return redirect()->back()->with('success', 'Data pemasukan berhasil dipulihkan.');
    }

    public function exportPdf(Request $request)
    {
        return $this->pemasukanService->exportPdf($request->input('pemasukan_ids', []));
    }
}
