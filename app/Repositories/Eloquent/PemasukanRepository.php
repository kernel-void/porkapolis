<?php

namespace App\Repositories\Eloquent;

use App\Models\Pemasukan;
use App\Repositories\Contracts\PemasukanRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PemasukanRepository implements PemasukanRepositoryInterface
{
    public function all(): Collection
    {
        return Pemasukan::with('details.menu')->latest()->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Pemasukan::with('details.menu')->latest()->paginate($perPage);
    }

    public function allTrashed(): Collection
    {
        return Pemasukan::onlyTrashed()->with('details.menu')->latest()->get();
    }

    public function forExport(array $ids = []): Collection
    {
        $query = Pemasukan::with('details.menu')->latest();

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        return $query->get();
    }

    public function find(int $id): Pemasukan
    {
        return Pemasukan::with('details')->findOrFail($id);
    }

    public function findTrashed(int $id): Pemasukan
    {
        return Pemasukan::onlyTrashed()->with('details')->findOrFail($id);
    }

    public function create(array $data): Pemasukan
    {
        return Pemasukan::create($data);
    }

    public function addDetail(Pemasukan $pemasukan, array $data): void
    {
        $pemasukan->details()->create($data);
    }

    public function deleteDetails(Pemasukan $pemasukan): void
    {
        $pemasukan->details()->delete();
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->findTrashed($id)->restore();
    }
}
