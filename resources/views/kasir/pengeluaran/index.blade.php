@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Data Pengeluaran')
@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Home</a></li>
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
                <h6 class="m-0 font-weight-bold text-primary">
                    Data Pengeluaran
                </h6>
            
                <div class="d-flex gap-2">
            
                    <button class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#createModal"
                            title="Tambah Data">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="pt-2">
                    <form id="printForm" action="{{ route('kasir.pengeluaran.exportPdf') }}" method="POST" target="_blank">
                    @csrf
                        <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0" style="table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th style="width: 130px;">Tanggal</th>
                                    <th style="width: 150px;">Jumlah</th>
                                    <th>Keterangan</th>
                                    <th style="width: 110px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengeluaran as $key => $keluar)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($keluar->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td>Rp{{ number_format($keluar->jumlah, 0, ',', '.') }}</td>
                                    <td class="text-break">{{ $keluar->keterangan ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button class="btn btn-warning btn-sm btn-circle editBtn mr-1"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                data-url="{{ route('kasir.pengeluaran.update', $keluar->id) }}"
                                                data-id="{{ $keluar->id }}"
                                                data-tanggal="{{ $keluar->tanggal }}"
                                                data-keterangan="{{ $keluar->keterangan }}"
                                                data-jumlah="{{ $keluar->jumlah }}">
                                                <i class="fas fa-pen"></i>
                                            </button>
    
                                            <button class="btn btn-danger btn-sm btn-circle deleteBtn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-url="{{ route('kasir.pengeluaran.destroy', $keluar->id) }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
</div>
@include('kasir.pengeluaran.create')
@include('kasir.pengeluaran.edit')
@include('kasir.pengeluaran.delete')
@endsection

@section('scripts')
<script>
    // Script untuk mengisi data pada modal edit
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.editBtn');
        if (!btn) return;
    
        let tanggal = btn.getAttribute('data-tanggal');
        let keterangan = btn.getAttribute('data-keterangan');
        let jumlah = btn.getAttribute('data-jumlah');
    
        document.getElementById('editForm').action = btn.dataset.url;
        document.getElementById('edit_tanggal').value = tanggal;
        document.getElementById('edit_keterangan').value = keterangan;
        document.getElementById('edit_jumlah').value = jumlah;
    });

    document.addEventListener('DOMContentLoaded', function () {
        const tgl = document.getElementById('tanggal');
        if (tgl) {
            let today = new Date().toISOString().split('T')[0];
            tgl.value = today; // otomatis isi hari ini
        }
    });

    // Script Modal Delete
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.deleteBtn');
        if (!deleteBtn) return;
    
        document.getElementById('deleteForm').action = deleteBtn.dataset.url;
    });
</script>
@endsection