<?php

namespace App\Services;

use App\Models\Pengeluaran;
use App\Repositories\Contracts\PengeluaranRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PengeluaranService
{
    public function __construct(
        private PengeluaranRepositoryInterface $pengeluaran,
        private SettingRepositoryInterface $settings,
        private PdfService $pdf,
    ) {
    }

    public function all(): Collection
    {
        return $this->pengeluaran->all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->pengeluaran->paginate($perPage);
    }

    public function allTrashed(): Collection
    {
        return $this->pengeluaran->allTrashed();
    }

    public function create(array $data): Pengeluaran
    {
        return $this->pengeluaran->create($data);
    }

    public function update(int $id, array $data): Pengeluaran
    {
        return $this->pengeluaran->update($id, $data);
    }

    public function delete(int $id): void
    {
        $this->pengeluaran->delete($id);
    }

    public function restore(int $id): void
    {
        $this->pengeluaran->restore($id);
    }

    public function exportPdf(array $ids = []): Response
    {
        return $this->pdf->inline('admin.pengeluaran.pdf', [
            'pengeluaran' => $this->pengeluaran->forExport($ids),
            'pengaturan'  => $this->settings->current(),
        ], 'laporan-pengeluaran-' . now()->format('Y-m-d') . '.pdf');
    }
}
