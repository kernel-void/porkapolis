<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function allWithPermissions(): Collection
    {
        return Role::with('permissions')->orderBy('name')->get();
    }

    public function groupedPermissions(): Collection
    {
        return Permission::orderBy('name')->get()->groupBy(
            fn ($permission) => explode('.', $permission->name)[0]
        );
    }

    public function find(int $id): Role
    {
        return Role::findOrFail($id);
    }

    public function syncPermissions(Role $role, array $permissions): void
    {
        $role->syncPermissions($permissions);
    }
}
