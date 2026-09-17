<?php

namespace App\Repositories\Eloquent;

use App\Models\Menu;
use App\Models\PemasukanDetail;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    public function all(): Collection
    {
        return Menu::latest()->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Menu::latest()->paginate($perPage);
    }

    public function allTrashed(): Collection
    {
        return Menu::onlyTrashed()->latest()->get();
    }

    public function forExport(array $ids = []): Collection
    {
        $query = Menu::latest();

        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        return $query->get();
    }

    public function find(int $id): Menu
    {
        return Menu::findOrFail($id);
    }

    public function findTrashed(int $id): Menu
    {
        return Menu::onlyTrashed()->findOrFail($id);
    }

    public function create(array $data): Menu
    {
        return Menu::create($data);
    }

    public function update(int $id, array $data): Menu
    {
        $menu = $this->find($id);
        $menu->update($data);

        return $menu;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function restore(int $id): bool
    {
        return $this->findTrashed($id)->restore();
    }

    public function isReferenced(int $id): bool
    {
        return PemasukanDetail::where('menu_id', $id)->exists();
    }

    public function incrementStok(int $id, int $qty): void
    {
        Menu::where('id', $id)->increment('stok', $qty);
    }

    public function decrementStok(int $id, int $qty): void
    {
        Menu::where('id', $id)->decrement('stok', $qty);
    }

    public function decrementStokIfAvailable(int $id, int $qty): bool
    {
        // Atomic: hanya potong kalau stok masih cukup, cegah race condition.
        return Menu::where('id', $id)
            ->where('stok', '>=', $qty)
            ->decrement('stok', $qty) > 0;
    }
}
