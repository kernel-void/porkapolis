<?php

namespace App\Repositories\Contracts;

use App\Models\Pemasukan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PemasukanRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function allTrashed(): Collection;

    public function forExport(array $ids = []): Collection;

    public function find(int $id): Pemasukan;

    public function findTrashed(int $id): Pemasukan;

    public function create(array $data): Pemasukan;

    public function addDetail(Pemasukan $pemasukan, array $data): void;

    public function deleteDetails(Pemasukan $pemasukan): void;

    public function delete(int $id): bool;

    public function restore(int $id): bool;
}
