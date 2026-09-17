<?php

namespace App\Services;

use App\Models\Pemasukan;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\PemasukanRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PemasukanService
{
    public function __construct(
        private PemasukanRepositoryInterface $pemasukan,
        private MenuRepositoryInterface $menus,
        private SettingRepositoryInterface $settings,
        private PdfService $pdf,
    ) {
    }

    public function all(): Collection
    {
        return $this->pemasukan->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->pemasukan->paginate($perPage);
    }

    public function allTrashed(): Collection
    {
        return $this->pemasukan->allTrashed();
    }

    public function menus(): Collection
    {
        return $this->menus->all();
    }

    public function create(array $data): Pemasukan
    {
        return DB::transaction(function () use ($data) {
            [$total, $items] = $this->buildItems($data['items']);

            $pemasukan = $this->pemasukan->create([
                'tanggal'    => $data['tanggal'],
                'total'      => $total,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            foreach ($items as $item) {
                $this->pemasukan->addDetail($pemasukan, [
                    'menu_id'  => $item['menu']->id,
                    'qty'      => $item['qty'],
                    'subtotal' => $item['subtotal'],
                ]);

                if (!$this->menus->decrementStokIfAvailable($item['menu']->id, $item['qty'])) {
                    throw new \RuntimeException("Stok {$item['menu']->nama_menu} tidak mencukupi.");
                }
            }

            return $pemasukan;
        });
    }

    public function update(int $id, array $data): Pemasukan
    {
        return DB::transaction(function () use ($id, $data) {
            $pemasukan = $this->pemasukan->find($id);

            foreach ($pemasukan->details as $oldDetail) {
                $this->menus->incrementStok($oldDetail->menu_id, $oldDetail->qty);
            }

            [$total, $items] = $this->buildItems($data['items']);

            $this->pemasukan->deleteDetails($pemasukan);

            foreach ($items as $item) {
                $this->pemasukan->addDetail($pemasukan, [
                    'menu_id'  => $item['menu']->id,
                    'qty'      => $item['qty'],
                    'subtotal' => $item['subtotal'],
                ]);

                if (!$this->menus->decrementStokIfAvailable($item['menu']->id, $item['qty'])) {
                    throw new \RuntimeException("Stok {$item['menu']->nama_menu} tidak mencukupi.");
                }
            }

            $pemasukan->update([
                'tanggal'    => $data['tanggal'],
                'total'      => $total,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            return $pemasukan;
        });
    }

    public function delete(int $id): void
    {
        $pemasukan = $this->pemasukan->find($id);

        // Kembalikan stok menu; detail tetap disimpan supaya bisa dipulihkan
        foreach ($pemasukan->details as $detail) {
            $this->menus->incrementStok($detail->menu_id, $detail->qty);
        }

        $this->pemasukan->delete($id);
    }

    public function restore(int $id): void
    {
        $pemasukan = $this->pemasukan->findTrashed($id);

        foreach ($pemasukan->details as $detail) {
            $this->menus->decrementStok($detail->menu_id, $detail->qty);
        }

        $this->pemasukan->restore($id);
    }

    public function exportPdf(array $ids = []): Response
    {
        return $this->pdf->inline('admin.pemasukan.pdf', [
            'pemasukan'  => $this->pemasukan->forExport($ids),
            'pengaturan' => $this->settings->current(),
        ], 'laporan-pemasukan-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * @return array{0:int,1:array<int,array{menu:\App\Models\Menu,qty:int,subtotal:int}>}
     */
    private function buildItems(array $items): array
    {
        $total = 0;
        $result = [];

        foreach ($items as $item) {
            $menu = $this->menus->find((int) $item['menu_id']);
            $qty = (int) $item['qty'];

            if ($menu->stok < $qty) {
                throw new \RuntimeException("Stok {$menu->nama_menu} tidak mencukupi. Sisa: {$menu->stok}");
            }

            $subtotal = $menu->harga * $qty;
            $total += $subtotal;

            $result[] = [
                'menu'     => $menu,
                'qty'      => $qty,
                'subtotal' => $subtotal,
            ];
        }

        return [$total, $result];
    }
}
