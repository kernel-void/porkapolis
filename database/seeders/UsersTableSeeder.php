<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'id_users'      => Str::uuid(), // Pastikan ini UUID
            'username'      => 'kasir',
            'name'          => 'Kasir',
            'password'      => Hash::make('kasir'),
            'bypass'        => 'kasir',
            'role_id'       => '2',
            'login_times'   => now(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        DB::table('users')->insert([
            'id_users'      => Str::uuid(), // Pastikan ini UUID
            'username'      => 'owner',
            'name'          => 'Owner',
            'password'      => Hash::make('owner'),
            'bypass'        => 'owner',
            'role_id'       => '3', 
            'login_times'   => now(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
