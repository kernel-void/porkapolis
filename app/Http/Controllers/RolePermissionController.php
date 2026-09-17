<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\RoleService;
use App\Services\SettingService;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function __construct(
        private RoleService $roleService,
        private SettingService $settingService,
    ) {
    }

    public function index()
    {
        return view('admin.role.index', array_merge($this->roleService->indexData(), [
            'pengaturan' => $this->settingService->current(),
        ]));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $this->roleService->update($role, $request->input('permissions', []));
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.role.index')
            ->with('success', "Permission untuk role \"{$role->name}\" berhasil diperbarui.");
    }
}
