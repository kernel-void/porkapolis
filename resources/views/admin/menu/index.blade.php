@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Menu')
@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Menu</li>
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
        <h1 class="h3 mb-0 text-gray-800">Menu</h1>
    </div>

        <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Menu</h6>

                <div class="d-flex">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal" title="Create Data">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>

                    <button class="btn btn-danger btn-sm ml-1" data-bs-toggle="modal" data-bs-target="#restoreModal" title="Trash Data">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive pt-2">
                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Menu</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menu as $key => $menuItem)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $menuItem->nama_menu }}</td>
                                <td>{{ $menuItem->stok }}</td>
                                <td>Rp{{ number_format($menuItem->harga, 0, ',', '.') }}</td>
                                <td>{{ $menuItem->keterangan ?? '-' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <button class="btn btn-warning btn-sm btn-circle editBtn mr-1"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-url="{{ route('admin.menu.update', $menuItem->id) }}"
                                            data-id="{{ $menuItem->id }}"
                                            data-nama_menu="{{ $menuItem->nama_menu }}"
                                            data-stok="{{ $menuItem->stok }}"
                                            data-harga="{{ $menuItem->harga }}"
                                            data-keterangan="{{ $menuItem->keterangan }}">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm btn-circle deleteBtn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-url="{{ route('admin.menu.destroy', $menuItem->id) }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
@include('admin.menu.create')
@include('admin.menu.edit')
@include('admin.menu.delete')
@include('admin.menu.restore')
@endsection

@section('scripts')
<script>
// Script untuk mengisi data pada modal edit
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.editBtn');
    if (!btn) return;

    let nama_menu = btn.getAttribute('data-nama_menu');
    let stok = btn.getAttribute('data-stok');
    let harga = btn.getAttribute('data-harga');
    let keterangan = btn.getAttribute('data-keterangan');

    document.getElementById('editForm').action = btn.dataset.url;
    document.getElementById('edit_nama_menu').value = nama_menu;
    document.getElementById('edit_stok').value = stok;
    document.getElementById('edit_harga').value = harga;
    document.getElementById('edit_keterangan').value = keterangan;
});

// Script Modal Delete
document.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.deleteBtn');
    if (!deleteBtn) return;

    document.getElementById('deleteForm').action = deleteBtn.dataset.url;
});

// Script Modal Restore Data Tables
$('#restoreModal').on('shown.bs.modal', function () {
    if (!$.fn.DataTable.isDataTable('#restoreTable')) {
        $('#restoreTable').DataTable();
    }
});
</script>
@endsection