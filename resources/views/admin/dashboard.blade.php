@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Dashboard')
@section('content')
<style>
    .icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-warning-light {
        background-color: rgba(246, 194, 62, 0.15);
    }
    .icon-circle i {
        font-size: 1.1rem;
    }
</style>
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Home</li>
    </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Beranda</h1>

        <form method="GET" action="" class="mb-3">
            <div class="d-flex align-items-center">
                <label for="tahun" class="mr-3 mt-2">Tahun</label>

                <select name="tahun" id="tahun" class="form-control select2" style="width: 150px;" onchange="this.form.submit()">
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>      
    </div>

    <!-- Content Row -->
    <div class="row">
    
        <!-- KOLOM 1: Hari ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <!-- Pemasukan Hari ini -->
            <div class="card border-left-primary shadow py-2 mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pemasukan Hari ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp{{ number_format($pemasukanHariIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Pengeluaran Hari ini -->
            <div class="card border-left-danger shadow py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pengeluaran Hari ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp{{ number_format($pengeluaranHariIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- KOLOM 2: Bulan ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <!-- Pemasukan Bulan ini -->
            <div class="card border-left-primary shadow py-2 mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pemasukan Bulan {{ $namaBulanIni }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp{{ number_format($pemasukanBulanIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Pengeluaran Bulan ini -->
            <div class="card border-left-danger shadow py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pengeluaran Bulan {{ $namaBulanIni }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp{{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- KOLOM 3: Tahun ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <!-- Pemasukan Tahun ini -->
            <div class="card border-left-primary shadow py-2 mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pemasukan Tahun {{ $tahunBerjalan }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp{{ number_format($pemasukanTahunIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Pengeluaran Tahun ini -->
            <div class="card border-left-info shadow py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pengeluaran Tahun {{ $tahunBerjalan }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp{{ number_format($pengeluaranTahunIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
       <!-- KOLOM 4: Keuangan (gabungan 2 baris) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body d-flex flex-column justify-content-between h-100">
        
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="text-xs font-weight-bold text-warning text-uppercase">
                            Keuangan
                        </div>
                        <div class="icon-circle bg-warning-light">
                            <i class="fas fa-wallet text-warning"></i>
                        </div>
                    </div>
        
                    <!-- Total -->
                    <div class="my-3">
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            Rp{{ number_format($totalKeuangan, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-muted mt-1">Saldo saat ini</div>
                    </div>
        
                    <!-- Breakdown -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-xs text-gray-600">
                                <i class="fas fa-arrow-up text-success mr-1"></i> Pemasukan
                            </span>
                            <span class="text-xs font-weight-bold text-gray-800">
                                Rp{{ number_format($pemasukanTahunIni, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-xs text-gray-600">
                                <i class="fas fa-arrow-down text-danger mr-1"></i> Pengeluaran
                            </span>
                            <span class="text-xs font-weight-bold text-gray-800">
                                Rp{{ number_format($pengeluaranTahunIni, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
        
                </div>
            </div>
        </div>
    
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-{{ $pengaturan->tema }}">Tabel Pemasukan dan Pengeluaran Pertahun</h6>
            
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bars fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opsi:</div>
                            <a class="dropdown-item" href="#" id="fullscreenBar">Full Screen</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" id="downloadPNG">Download PNG</a>
                            <a class="dropdown-item" href="#" id="downloadJPEG">Download JPEG</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" id="downloadPDF">Download PDF</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-bar" id="chartBarContainer">
                        <canvas id="myBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/demo/chart-bar-demo.js') }}"></script>
    <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script>
    <script>
        // Bar Chart
        var chartLabels = {!! json_encode($labels) !!};
        var dataPemasukan = {!! json_encode($dataPemasukan) !!};
        var dataPengeluaran = {!! json_encode($dataPengeluaran) !!};
    </script>
@endpush
