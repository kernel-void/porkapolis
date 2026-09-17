<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_users'   => (string) Str::uuid(),
                'username'   => 'owner',
                'name'       => 'Owner',
                'password'   => bcrypt('password'),
                'role_id'    => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_users'   => (string) Str::uuid(),
                'username'   => 'admin',
                'name'       => 'Administrator',
                'password'   => bcrypt('password'),
                'role_id'    => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_users'   => (string) Str::uuid(),
                'username'   => 'kasir',
                'name'       => 'Kasir',
                'password'   => bcrypt('password'),
                'role_id'    => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('settings')->insert([
            'id_settings'     => (string) Str::uuid(),
            'nama_aplikasi'   => 'Keuangan',
            'ikon_sidebar'    => 'fas fa-wallet',
            'tema'            => 'primary',
            'footer'          => 'Copyright © Keuangan 2025',
            'logo'            => 'assets/img/logo-login/logo.png',
            'captcha_enabled' => true,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }
}