@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Data Pemasukan')
@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
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
                <h6 class="m-0 font-weight-bold text-primary">Data Pemasukan</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive pt-2">
                    <table class="table table-bordered text-center table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Rincian Menu</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemasukan as $key => $masukan)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($masukan->tanggal)->translatedFormat('d F Y') }}</td>
                                <td class="text-start">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($masukan->details as $detail)
                                            <li>
                                                {{ $detail->menu->nama_menu ?? '(menu dihapus)' }}
                                                <span class="text-muted">
                                                    &times; {{ $detail->qty }}
                                                    = Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="fw-bold">Rp{{ number_format($masukan->total, 0, ',', '.') }}</td>
                                <td>{{ $masukan->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

@endsection

@section('scripts')
@endsection