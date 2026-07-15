<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuruSeeder extends Seeder
{
    public function run()
    {
        DB::table('gurus')->insert([
            'id_guru' => Str::uuid(),
            'nip' => '1234567890',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1980-05-15',
            'agama' => 'Islam',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
