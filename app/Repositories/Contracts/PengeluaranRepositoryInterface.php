<?php

namespace App\Repositories\Contracts;

use App\Models\Pengeluaran;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PengeluaranRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function allTrashed(): Collection;

    public function find(int $id): Pengeluaran;

    public function findTrashed(int $id): Pengeluaran;

    public function create(array $data): Pengeluaran;

    public function update(int $id, array $data): Pengeluaran;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

    public function forExport(array $ids = []): Collection;
}
