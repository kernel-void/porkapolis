<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Siswa;
use App\Models\Biaya;

class TagihanSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = Siswa::where('status', 'AKTIF')->get();

        foreach ($siswas as $siswa) {
            $biaya = Biaya::where('kategori', $siswa->category)->where('status', 'AKTIF')->get();

            foreach ($biaya as $item) {
                DB::table('tagihan')->insert([
                    'id_tagihan'       => Str::uuid(),
                    'id_siswa'         => $siswa->id_siswa,
                    'nama'             => $siswa->nama,
                    'nis'              => $siswa->nis,
                    'id_biaya'         => $item->id_biaya,
                    'nama_pembayaran'  => $item->nama,
                    'jenis'            => $item->jenis,
                    'jumlah'           => $item->jumlah,
                    'bulan'            => 'Maret 2025',
                    'status'           => 'BELUM DIBAYAR',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
}

