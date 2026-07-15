<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use Illuminate\Support\Str;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        Siswa::create([
            'id_siswa' => Str::uuid(),
            'nama' => 'Ahmad Fauzan',
            'nis' => '20231001',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2005-06-15',
            'kelas' => 'XII IPA 1',
            'category' => 'atas',
        ]);

        Siswa::create([
            'id_siswa' => Str::uuid(),
            'nama' => 'Siti Aisyah',
            'nis' => '20231002',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2006-08-22',
            'kelas' => 'XI IPS 2',
            'category' => 'menengah',
        ]);
    }
}

