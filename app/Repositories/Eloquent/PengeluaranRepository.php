<?php

namespace App\Repositories\Eloquent;

use App\Models\Pengeluaran;
use App\Repositories\Contracts\PengeluaranRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PengeluaranRepository implements PengeluaranRepositoryInterface
{
    public function all(): Collection
    {
        return Pengeluaran::latest()->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Pengeluaran::latest()->paginate($perPage);
    }

    public function allTrashed(): Collection
    {
        return Pengeluaran::onlyTrashed()->latest()->get();
    }

    public function find(int $id): Pengeluaran
    {
        return Pengeluaran::findOrFail($id);
    }

    public function findTrashed(int $id): Pengeluaran
    {
        return Pengeluaran::onlyTrashed()->findOrFail($id);
    }

    public function create(array $data): Pengeluaran
    {
        return Pengeluaran::create($data);
    }

    public function update(int $id, array $data): Pengeluaran
    {
        $pengeluaran = $this->find($id);
        $pengeluaran->update($data);

        return $pengeluaran;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->findTrashed($id)->restore();
    }

    public function forExport(array $ids = []): Collection
    {
        $query = Pengeluaran::latest();

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        return $query->get();
    }
}
