<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct(private RoleRepositoryInterface $roles)
    {
    }

    public function indexData(): array
    {
        return [
            'roles'   => $this->roles->allWithPermissions(),
            'grouped' => $this->roles->groupedPermissions(),
        ];
    }

    public function update(Role $role, array $permissions): Role
    {
        // Pengaman: cegah role 'admin' kehilangan akses ke halaman ini sendiri
        // (supaya admin tidak bisa mengunci dirinya sendiri keluar dari halaman kelola permission)
        if ($role->name === 'admin') {
            if (!in_array('role.view', $permissions) || !in_array('role.update', $permissions)) {
                throw new \RuntimeException('Role Admin wajib memiliki izin "role.view" dan "role.update" agar tidak terkunci dari halaman ini.');
            }
        }

        $this->roles->syncPermissions($role, $permissions);

        return $role;
    }
}
