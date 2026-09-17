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

        // Permission didefinisikan sesuai fitur yang benar-benar ada di aplikasi.
        $moduleActions = [
            'menu'        => ['view', 'create', 'update', 'delete', 'export', 'restore'],
            'pemasukan'   => ['view', 'create', 'update', 'delete', 'export', 'restore'],
            'pengeluaran' => ['view', 'create', 'update', 'delete', 'export', 'restore'],
            'user'        => ['view', 'create', 'update', 'delete', 'export', 'restore'],
            'pengaturan'  => ['view', 'update'],
            'role'        => ['view', 'update'],
        ];

        $allNames = [];

        foreach ($moduleActions as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
                $allNames[] = "{$module}.{$action}";
            }
        }

        // Buang permission lama yang sudah tidak dipakai fitur apa pun
        Permission::whereNotIn('name', $allNames)->delete();

        $owner = Role::firstOrCreate(['name' => 'owner']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $kasir = Role::firstOrCreate(['name' => 'kasir']);

        // Owner: semua transaksi & menu, TANPA manajemen user & pengaturan
        $ownerPermissions = Permission::whereNotIn('name', [
            'user.view', 'user.create', 'user.update', 'user.delete', 'user.export', 'user.restore',
            'pengaturan.view', 'pengaturan.update',
        ])->pluck('name');
        $owner->syncPermissions($ownerPermissions);

        // Admin: seluruh permission
        $admin->syncPermissions($allNames);

        // Kasir: input transaksi harian, tanpa hapus/export/restore
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
