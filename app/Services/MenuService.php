<?php

namespace App\Services;

use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MenuService
{
    public function __construct(
        private MenuRepositoryInterface $menus,
        private SettingRepositoryInterface $settings,
        private PdfService $pdf,
    ) {
    }

    public function all(): Collection
    {
        return $this->menus->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->menus->paginate($perPage);
    }

    public function allTrashed(): Collection
    {
        return $this->menus->allTrashed();
    }

    public function create(array $data): Menu
    {
        return $this->menus->create($data);
    }

    public function update(int $id, array $data): Menu
    {
        return $this->menus->update($id, $data);
    }

    public function delete(int $id): void
    {
        if ($this->menus->isReferenced($id)) {
            throw new \RuntimeException('Menu tidak bisa dihapus karena masih memiliki riwayat transaksi pemasukan.');
        }

        $this->menus->delete($id);
    }

    public function restore(int $id): void
    {
        $this->menus->restore($id);
    }

    public function exportPdf(array $ids = []): Response
    {
        return $this->pdf->inline('admin.menu.pdf', [
            'menu'       => $this->menus->forExport($ids),
            'pengaturan' => $this->settings->current(),
        ], 'laporan-menu-' . now()->format('Y-m-d') . '.pdf');
    }
}
