<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 10): LengthAwarePaginator;

    public function allTrashed(): Collection;

    public function forExport(array $ids = []): Collection;

    public function find(string $id): User;

    public function findByUsername(string $username): ?User;

    public function findTrashed(string $id): User;

    public function create(array $data): User;

    public function update(string $id, array $data): User;

    public function delete(string $id): bool;

    public function restore(string $id): bool;

    public function syncRoles(User $user, string|array $roles): void;
}
