<?php

namespace App\Repositories\Contracts;

use App\Models\Menu;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MenuRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function allTrashed(): Collection;

    public function forExport(array $ids = []): Collection;

    public function find(int $id): Menu;

    public function findTrashed(int $id): Menu;

    public function create(array $data): Menu;

    public function update(int $id, array $data): Menu;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

    public function isReferenced(int $id): bool;

    public function incrementStok(int $id, int $qty): void;

    public function decrementStok(int $id, int $qty): void;

    public function decrementStokIfAvailable(int $id, int $qty): bool;
}
