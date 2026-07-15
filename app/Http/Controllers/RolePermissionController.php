<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        $pengaturan = Setting::first();

        $roles = Role::with('permissions')->orderBy('name')->get();

        // Kelompokkan permission berdasarkan modul (bagian sebelum titik)
        $permissions = Permission::orderBy('name')->get();
        $grouped = $permissions->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.role.index', compact('roles', 'grouped', 'pengaturan'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Pengaman: cegah role 'admin' kehilangan akses ke halaman ini sendiri
        // (supaya admin tidak bisa mengunci dirinya sendiri keluar dari halaman kelola permission)
        if ($role->name === 'admin') {
            $incoming = $request->input('permissions', []);
            if (!in_array('role.view', $incoming) || !in_array('role.update', $incoming)) {
                return redirect()->back()->with('error', 'Role Admin wajib memiliki izin "role.view" dan "role.update" agar tidak terkunci dari halaman ini.');
            }
        }

        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.role.index')->with('success', "Permission untuk role \"{$role->name}\" berhasil diperbarui.");
    }
}