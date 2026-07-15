<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = ['pemasukan', 'pengeluaran', 'menu', 'user', 'pengaturan', 'role'];
        $actions = ['view', 'create', 'update', 'delete', 'export', 'restore'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
            }
        }

        $owner = Role::firstOrCreate(['name' => 'owner']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $kasir = Role::firstOrCreate(['name' => 'kasir']);

        // Owner: full akses transaksi & menu, TAPI TIDAK termasuk pengaturan & manajemen user
        $ownerPermissions = Permission::whereNotIn('name', [
            'user.view', 'user.create', 'user.update', 'user.delete', 'user.export', 'user.restore',
            'pengaturan.view', 'pengaturan.create', 'pengaturan.update', 'pengaturan.delete', 'pengaturan.export', 'pengaturan.restore',
        ])->get();
        $owner->syncPermissions($ownerPermissions);

        // Admin: kelola menu & pemasukan & pengeluaran penuh, PLUS pengaturan & manajemen user (khusus admin)
        $admin->syncPermissions([
            'menu.view', 'menu.create', 'menu.update', 'menu.delete', 'menu.restore',
            'pemasukan.view', 'pemasukan.create', 'pemasukan.update', 'pemasukan.delete',
            'pengeluaran.view', 'pengeluaran.create', 'pengeluaran.update', 'pengeluaran.delete', 'pengeluaran.restore', 'pengeluaran.export',
            'user.view', 'user.update',
            'pengaturan.view', 'pengaturan.update',
            'role.view', 'role.update',
        ]);

        // Kasir: input transaksi harian, tidak bisa hapus/restore, tidak ada akses pengaturan/user
        $kasir->syncPermissions([
            'pemasukan.view', 'pemasukan.create', 'pemasukan.update',
            'pengeluaran.view', 'pengeluaran.create', 'pengeluaran.update',
            'menu.view',
        ]);

        // Migrasi user lama (role_id enum) ke role Spatie
        User::where('role_id', '3')->get()->each(fn ($u) => $u->syncRoles('owner'));
        User::where('role_id', '1')->get()->each(fn ($u) => $u->syncRoles('admin'));
        User::where('role_id', '2')->get()->each(fn ($u) => $u->syncRoles('kasir'));
    }
}