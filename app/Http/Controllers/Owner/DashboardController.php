<?php
namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

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
        $this->middleware('auth'); // Pastikan hanya user yang login bisa mengakses
    }

    public function index(Request $request)
    {
         // === PEMASUKAN HARI INI ===
        $pemasukanHariIni = Pemasukan::whereDate('tanggal', Carbon::today())
            ->whereNull('deleted_at')
            ->sum('total');
            
        // === PEMASUKAN BULAN INI ===
        Carbon::setLocale('id');
        
        $pemasukanBulanIni = Pemasukan::whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereNull('deleted_at')
            ->sum('total');
        
        $namaBulanIni = Carbon::now()->translatedFormat('F Y');
        
        // === PEMASUKAN TAHUN INI ===
        $pemasukanTahunIni = Pemasukan::whereYear('tanggal', Carbon::now()->year)
            ->whereNull('deleted_at')
            ->sum('total');
        
        $tahunBerjalan = Carbon::now()->year; // contoh: 2026

        // === PENGELUARAN HARI INI ===
        $pengeluaranHariIni = Pengeluaran::whereDate('tanggal', Carbon::today())
            ->whereNull('deleted_at')
            ->sum('jumlah');
            
        $pengeluaranBulanIni = Pengeluaran::whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereNull('deleted_at')
            ->sum('jumlah');
            
        $pengeluaranTahunIni = Pengeluaran::whereYear('tanggal', Carbon::now()->year)
            ->whereNull('deleted_at')
            ->sum('jumlah');

        // === Total Keuangan ===
        $totalPemasukan = Pemasukan::whereNull('deleted_at')->sum('total');
        $totalPengeluaran = Pengeluaran::whereNull('deleted_at')->sum('jumlah');

        $totalKeuangan = $totalPemasukan - $totalPengeluaran;

        // Data untuk chart pemasukan dan pengeluaran bulanan
        $tahunPemasukan = DB::table('pemasukans')
            ->selectRaw('YEAR(created_at) as tahun')
            ->groupBy('tahun')
            ->pluck('tahun');

        $tahunPengeluaran = DB::table('pengeluarans')
            ->selectRaw('YEAR(created_at) as tahun')
            ->groupBy('tahun')
            ->pluck('tahun');

        // Ambil tahun unik dari tabel pemasukan
        $tahunList = Pemasukan::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Tahun yang dipilih (default: tahun terbaru dari database)
        $tahunDipilih = request()->tahun ?? $tahunList->first();

        // Query data pemasukan berdasarkan tahun
        $pemasukan = Pemasukan::whereYear('tanggal', $tahunDipilih)->get();

        // Query data chart sesuai tahun
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
            // Label Bulan
            $labels[] = Carbon::create()->month($i)->translatedFormat('F');

            // Ambil data pemasukan bulan ini
            $foundPemasukan = $pemasukanBulanan->firstWhere('bulan', $i);
            $dataPemasukan[] = $foundPemasukan ? $foundPemasukan->total : 0;

            // Ambil data pengeluaran bulan ini
            $foundPengeluaran = $pengeluaranBulanan->firstWhere('bulan', $i);
            $dataPengeluaran[] = $foundPengeluaran ? $foundPengeluaran->total : 0;
        }
 
        $pengaturan = Setting::first();

        return view('owner.dashboard', compact(
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