@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Data Pengeluaran')
@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengeluaran</li>
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
        <h1 class="h3 mb-0 text-gray-800">Pengeluaran</h1>
    </div>

        <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran</h6>
                
                <button type="submit"
                        form="printForm"
                        class="btn btn-outline-secondary btn-sm"
                        title="Export PDF">
                    <i class="fas fa-print mr-1"></i> Export PDF
                </button>
            </div>
            
            <div class="card-body">
                <form id="printForm" action="{{ route('owner.pengeluaran.exportPdf') }}" method="POST" target="_blank">
                @csrf
                    <div class="table-scroll-wrapper pt-2">
                        <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">
                                        <input type="checkbox" id="checkAll" class="mr-1">
                                        No
                                    </th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengeluaran as $key => $keluar)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <input type="checkbox"
                                                   name="pengeluaran_ids[]"
                                                   value="{{ $keluar->id }}"
                                                   form="printForm"
                                                   class="row-check mr-2">
                                            <span>{{ $key + 1 }}</span>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($keluar->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td>Rp{{ number_format($keluar->jumlah, 0, ',', '.') }}</td>
                                    <td>{{ $keluar->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection

@section('styles')
<style>
    .table-scroll-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        scrollbar-width: thin;
    }

    .table-scroll-wrapper table {
        min-width: 700px; /* paksa lebar minimum supaya scroll muncul di layar sempit */
    }

    .table-scroll-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-thumb {
        background-color: #c1c1c1;
        border-radius: 4px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
</style>
@endsection

@section('scripts')
<script>
    // Checklist semua
    document.getElementById('checkAll').addEventListener('change', function () {
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Cegah export kalau tidak ada yang dipilih
    document.getElementById('printForm').addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('.row-check:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal satu data untuk diexport.');
        }
    });
</script>
@endsection