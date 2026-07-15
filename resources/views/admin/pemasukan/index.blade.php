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
                    <table class="table table-bordered text-center table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Menu</th>
                                <th>Tanggal</th>
                                <th>Jumlah Terjual</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemasukan as $key => $masukan)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $masukan->menu->nama_menu }}</td>
                                <td>{{ \Carbon\Carbon::parse($masukan->tanggal)->translatedFormat('d F Y') }}</td>
                                <td>{{ $masukan->qty }}</td>
                                <td>Rp{{ number_format($masukan->total, 0, ',', '.') }}</td>
                                <td>{{ $masukan->keterangan ?? '-' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <button class="btn btn-warning btn-sm btn-circle editBtn mr-1"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-menu_id="{{ $masukan->menu_id }}"
                                            data-url="{{ route('admin.pemasukan.update', $masukan->id) }}"
                                            data-id="{{ $masukan->id }}"
                                            data-tanggal="{{ $masukan->tanggal }}"
                                            data-qty="{{ $masukan->qty }}"
                                            data-total="{{ $masukan->total }}"
                                            data-keterangan="{{ $masukan->keterangan }}">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm btn-circle deleteBtn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-url="{{ route('admin.pemasukan.destroy', $masukan->id) }}">
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
@include('admin.pemasukan.create')
@include('admin.pemasukan.edit')
@include('admin.pemasukan.delete')
@include('admin.pemasukan.restore')

@endsection

@section('scripts')
<script>
// Script untuk mengisi data pada modal edit
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.editBtn');
    if (!btn) return;

    let menu = btn.getAttribute('data-menu_id');
    let tanggal = btn.getAttribute('data-tanggal');
    let qty = btn.getAttribute('data-qty');
    let total = btn.getAttribute('data-total');
    let keterangan = btn.getAttribute('data-keterangan');

    document.getElementById('edit_menu_id').value = btn.dataset.menu_id;
    document.getElementById('editForm').action = btn.dataset.url;
    document.getElementById('edit_tanggal').value = tanggal;
    document.getElementById('edit_qty').value = qty;
    document.getElementById('edit_total').value = total;
    document.getElementById('edit_keterangan').value = keterangan;
});

// penginputan otomatis total berdasarkan menu dan qty
document.addEventListener('DOMContentLoaded', function () {

    function setupAutoTotal(menuId, qtyId, totalId) {
        const menuSelect = document.getElementById(menuId);
        const qtyInput = document.getElementById(qtyId);
        const totalInput = document.getElementById(totalId);

        // Kalau element tidak ada, jangan jalankan apapun
        if (!menuSelect || !qtyInput || !totalInput) return;

        function hitungTotal() {
            let harga = menuSelect.selectedOptions[0]?.getAttribute('data-harga') || 0;
            let qty = qtyInput.value || 0;
            totalInput.value = harga * qty;
        }

        menuSelect.addEventListener('change', hitungTotal);
        qtyInput.addEventListener('input', hitungTotal);

        // Hitung otomatis saat modal edit dibuka (jika ada)
        document.addEventListener('shown.bs.modal', hitungTotal);
    }

    // 🔥 Panggil untuk form Create
    setupAutoTotal('menu_id', 'qty', 'total');

    // 🔥 Panggil untuk form Edit
    setupAutoTotal('edit_menu_id', 'edit_qty', 'edit_total');

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
