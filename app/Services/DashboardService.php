<?php

namespace App\Services;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Setting;
use Carbon\Carbon;

class DashboardService
{
    public function summary(?string $bulan = null): array
    {
        Carbon::setLocale('id');

        $bulanDipilih = $this->normalizeBulan($bulan);
        $awalBulan = $bulanDipilih->copy()->startOfMonth();
        $akhirBulan = $bulanDipilih->copy()->endOfMonth();
        $tahunTerpilih = $bulanDipilih->year;

        // === PEMASUKAN / PENGELUARAN HARI INI ===
        $pemasukanHariIni = Pemasukan::whereDate('tanggal', Carbon::today())->sum('total');
        $pengeluaranHariIni = Pengeluaran::whereDate('tanggal', Carbon::today())->sum('jumlah');

        // === BULAN TERPILIH ===
        $pemasukanBulanIni = Pemasukan::whereBetween('tanggal', [$awalBulan, $akhirBulan])->sum('total');
        $pengeluaranBulanIni = Pengeluaran::whereBetween('tanggal', [$awalBulan, $akhirBulan])->sum('jumlah');
        $namaBulanIni = $bulanDipilih->translatedFormat('F Y');

        // === TAHUN DARI BULAN TERPILIH ===
        $pemasukanTahunIni = Pemasukan::whereYear('tanggal', $tahunTerpilih)->sum('total');
        $pengeluaranTahunIni = Pengeluaran::whereYear('tanggal', $tahunTerpilih)->sum('jumlah');
        $tahunBerjalan = $tahunTerpilih;

        // === TOTAL KEUANGAN (keseluruhan) ===
        $totalKeuangan = Pemasukan::sum('total') - Pengeluaran::sum('jumlah');

        // === DATA HARIAN BULAN TERPILIH ===
        $pemasukanHarian = Pemasukan::selectRaw('DAY(tanggal) as hari, SUM(total) as total')
            ->whereYear('tanggal', $tahunTerpilih)
            ->whereMonth('tanggal', $bulanDipilih->month)
            ->groupBy('hari')
            ->get();

        $pengeluaranHarian = Pengeluaran::selectRaw('DAY(tanggal) as hari, SUM(jumlah) as total')
            ->whereYear('tanggal', $tahunTerpilih)
            ->whereMonth('tanggal', $bulanDipilih->month)
            ->groupBy('hari')
            ->get();

        $labels = [];
        $dataPemasukan = [];
        $dataPengeluaran = [];

        for ($i = 1; $i <= $awalBulan->daysInMonth; $i++) {
            $labels[] = $i;

            $foundPemasukan = $pemasukanHarian->firstWhere('hari', $i);
            $dataPemasukan[] = $foundPemasukan ? (int) $foundPemasukan->total : 0;

            $foundPengeluaran = $pengeluaranHarian->firstWhere('hari', $i);
            $dataPengeluaran[] = $foundPengeluaran ? (int) $foundPengeluaran->total : 0;
        }

        $bulanDipilihValue = $bulanDipilih->format('Y-m');
        $pengaturan = Setting::first();

        return compact(
            'namaBulanIni',
            'tahunBerjalan',
            'pemasukanHariIni',
            'pemasukanBulanIni',
            'pemasukanTahunIni',
            'pengeluaranHariIni',
            'pengeluaranBulanIni',
            'pengeluaranTahunIni',
            'totalKeuangan',
            'labels',
            'dataPemasukan',
            'dataPengeluaran',
            'bulanDipilihValue',
            'pengaturan'
        );
    }

    /**
     * Normalisasi input bulan "YYYY-MM" menjadi Carbon awal bulan.
     * Fallback ke bulan berjalan kalau tidak valid.
     */
    private function normalizeBulan(?string $bulan): Carbon
    {
        if ($bulan && preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            try {
                return Carbon::createFromFormat('Y-m-d', $bulan . '-01')->startOfMonth();
            } catch (\Exception $e) {
                // jatuh ke default
            }
        }

        return Carbon::now()->startOfMonth();
    }
}
