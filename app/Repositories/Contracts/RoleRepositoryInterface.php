<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function allWithPermissions(): Collection;

    public function groupedPermissions(): Collection;

    public function find(int $id): Role;

    public function syncPermissions(Role $role, array $permissions): void;
}
