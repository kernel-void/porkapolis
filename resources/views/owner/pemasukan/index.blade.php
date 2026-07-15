@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Data Pemasukan')
@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pemasukan</li>
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
        <h1 class="h3 mb-0 text-gray-800">Pemasukan</h1>
    </div>

        <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Data Pemasukan
                </h6>
            
                <button type="submit"
                        form="printForm"
                        class="btn btn-outline-secondary btn-sm"
                        title="Export PDF">
                    <i class="fas fa-print mr-1"></i> Export PDF
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive pt-2">
                    <form id="printForm" action="{{ route('owner.pemasukan.exportPdf') }}" method="POST" target="_blank">
                    @csrf
                        <table class="table table-bordered text-center table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">
                                        <input type="checkbox" id="checkAll" class="mr-1">
                                        No
                                    </th>
                                    <th>Nama Menu</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Terjual</th>
                                    <th>Total</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemasukan as $key => $masukan)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <input type="checkbox"
                                                   name="pemasukan_ids[]"
                                                   value="{{ $masukan->id }}"
                                                   form="printForm"
                                                   class="row-check mr-2">
                                            <span>{{ $key + 1 }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $masukan->menu->nama_menu }}</td>
                                    <td>{{ \Carbon\Carbon::parse($masukan->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td>{{ $masukan->qty }}</td>
                                    <td>Rp{{ number_format($masukan->total, 0, ',', '.') }}</td>
                                    <td>{{ $masukan->keterangan ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
</div>
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
