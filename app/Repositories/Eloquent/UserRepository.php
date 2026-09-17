<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::all();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return User::orderByDesc('last_seen')->paginate($perPage);
    }

    public function allTrashed(): Collection
    {
        return User::onlyTrashed()->get();
    }

    public function forExport(array $ids = []): Collection
    {
        $query = User::query();

        if (!empty($ids)) {
            $query->whereIn('id_users', $ids);
        }

        return $query->get();
    }

    public function find(string $id): User
    {
        return User::findOrFail($id);
    }

    public function findByUsername(string $username): ?User
    {
        return User::where('username', $username)->first();
    }

    public function findTrashed(string $id): User
    {
        return User::onlyTrashed()->findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(string $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
    }

    public function delete(string $id): bool
    {
        return $this->find($id)->delete();
    }

    public function restore(string $id): bool
    {
        return $this->findTrashed($id)->restore();
    }

    public function syncRoles(User $user, string|array $roles): void
    {
        $user->syncRoles($roles);
    }
}
