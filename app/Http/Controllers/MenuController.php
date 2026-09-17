<?php

namespace App\Http\Controllers;

use App\Http\Requests\Menu\StoreMenuRequest;
use App\Http\Requests\Menu\UpdateMenuRequest;
use App\Services\MenuService;
use App\Services\SettingService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        private MenuService $menuService,
        private SettingService $settingService,
    ) {
    }

    public function index()
    {
        return view('admin.menu.index', [
            'pengaturan' => $this->settingService->current(),
            'menu'       => $this->menuService->paginate(),
            'trashed'    => $this->menuService->allTrashed(),
        ]);
    }

    public function store(StoreMenuRequest $request)
    {
        $this->menuService->create($request->validated());

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function update(UpdateMenuRequest $request, $id)
    {
        $this->menuService->update((int) $id, $request->validated());

        return redirect()->route('admin.menu.index')->with('success', 'Data menu berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $this->menuService->delete((int) $id);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()
            ->back()
            ->with('success', 'Data menu berhasil dihapus. Masih bisa dipulihkan dari Data Terhapus.');
    }

    public function restore($id)
    {
        $this->menuService->restore((int) $id);

        return redirect()->back()->with('success', 'Data menu berhasil dipulihkan.');
    }

    public function exportPdf(Request $request)
    {
        return $this->menuService->exportPdf($request->input('menu_ids', []));
    }
}
