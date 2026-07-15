<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // === PEMASUKAN HARI INI ===
        $pemasukanHariIni = Pemasukan::whereDate('tanggal', Carbon::today())
            ->sum('total');

        // === PEMASUKAN BULAN INI ===
        Carbon::setLocale('id');

        $pemasukanBulanIni = Pemasukan::whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->sum('total');

        $namaBulanIni = Carbon::now()->translatedFormat('F Y');

        // === PEMASUKAN TAHUN INI ===
        $pemasukanTahunIni = Pemasukan::whereYear('tanggal', Carbon::now()->year)
            ->sum('total');

        $tahunBerjalan = Carbon::now()->year;

        // === PENGELUARAN HARI INI ===
        $pengeluaranHariIni = Pengeluaran::whereDate('tanggal', Carbon::today())
            ->sum('jumlah');

        $pengeluaranBulanIni = Pengeluaran::whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->sum('jumlah');

        $pengeluaranTahunIni = Pengeluaran::whereYear('tanggal', Carbon::now()->year)
            ->sum('jumlah');

        // === Total Keuangan ===
        $totalPemasukan = Pemasukan::sum('total');
        $totalPengeluaran = Pengeluaran::sum('jumlah');

        $totalKeuangan = $totalPemasukan - $totalPengeluaran;

        // Ambil tahun unik dari tabel pemasukan
        $tahunList = Pemasukan::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $tahunDipilih = $request->tahun ?? $tahunList->first();

        $pemasukanBulanan = DB::table('pemasukans')
            ->selectRaw('MONTH(tanggal) as bulan, SUM(total) as total')
            ->whereYear('tanggal', $tahunDipilih)
            ->groupBy('bulan')
            ->get();

        $pengeluaranBulanan = DB::table('pengeluarans')
            ->selectRaw('MONTH(tanggal) as bulan, SUM(jumlah) as total')
            ->whereYear('tanggal', $tahunDipilih)
            ->groupBy('bulan')
            ->get();

        $labels = [];
        $dataPemasukan = [];
        $dataPengeluaran = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->translatedFormat('F');

            $foundPemasukan = $pemasukanBulanan->firstWhere('bulan', $i);
            $dataPemasukan[] = $foundPemasukan ? $foundPemasukan->total : 0;

            $foundPengeluaran = $pengeluaranBulanan->firstWhere('bulan', $i);
            $dataPengeluaran[] = $foundPengeluaran ? $foundPengeluaran->total : 0;
        }

        $pengaturan = Setting::first();

        return view('admin.dashboard', compact(
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
            'tahunList',
            'tahunDipilih',
            'pemasukanBulanan',
            'pengeluaranBulanan',
            'pengaturan'
        ));
    }
}