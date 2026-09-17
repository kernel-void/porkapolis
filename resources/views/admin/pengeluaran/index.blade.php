@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Data Pengeluaran')
@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
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

                <div class="d-flex gap-1">
                    @can('pengeluaran.create')
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal" title="Create Data">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                    @endcan

                    @can('pengeluaran.export')
                        <button type="submit" form="printForm" class="btn btn-outline-secondary btn-sm ml-1" title="Export PDF">
                            <i class="fas fa-print mr-1"></i> Export PDF
                        </button>
                    @endcan
                </div>
            </div>
            
            <div class="card-body">
                <div class="pt-2 table-responsive">
                    <form id="printForm" action="{{ route('admin.pengeluaran.exportPdf') }}" method="POST" target="_blank">
                    @csrf
                        <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    @can('pengeluaran.export')
                                        <th style="width: 70px;">
                                            <input type="checkbox" id="checkAll" class="mr-1">
                                            No
                                        </th>
                                    @else
                                        <th style="width: 50px;">No</th>
                                    @endcan
                                    <th style="width: 130px;">Tanggal</th>
                                    <th style="width: 150px;">Jumlah</th>
                                    <th>Keterangan</th>
                                    @canany(['pengeluaran.update', 'pengeluaran.delete'])
                                        <th style="width: 110px;">Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengeluaran as $key => $keluar)
                                <tr>
                                    @can('pengeluaran.export')
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
                                    @else
                                        <td>{{ $key + 1 }}</td>
                                    @endcan
                                    <td>{{ \Carbon\Carbon::parse($keluar->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td>Rp{{ number_format($keluar->jumlah, 0, ',', '.') }}</td>
                                    <td class="text-break">{{ $keluar->keterangan ?? '-' }}</td>
                                    @canany(['pengeluaran.update', 'pengeluaran.delete'])
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            @can('pengeluaran.update')
                                                <button class="btn btn-warning btn-sm btn-circle editBtn mr-1"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal"
                                                    data-url="{{ route('admin.pengeluaran.update', $keluar->id) }}"
                                                    data-id="{{ $keluar->id }}"
                                                    data-tanggal="{{ $keluar->tanggal }}"
                                                    data-keterangan="{{ $keluar->keterangan }}"
                                                    data-jumlah="{{ $keluar->jumlah }}">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                            @endcan

                                            @can('pengeluaran.delete')
                                                <button class="btn btn-danger btn-sm btn-circle deleteBtn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-url="{{ route('admin.pengeluaran.destroy', $keluar->id) }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                    @endcanany
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
</div>
@can('pengeluaran.create')
    @include('admin.pengeluaran.create')
@endcan
@can('pengeluaran.update')
    @include('admin.pengeluaran.edit')
@endcan
@can('pengeluaran.delete')
    @include('admin.pengeluaran.delete')
@endcan
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

// Script Modal Delete
document.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.deleteBtn');
    if (!deleteBtn) return;

    document.getElementById('deleteForm').action = deleteBtn.dataset.url;
});

// Checklist semua (hanya ada kalau user punya izin export)
const checkAllEl = document.getElementById('checkAll');
if (checkAllEl) {
    checkAllEl.addEventListener('change', function () {
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.checked = this.checked;
        });
    });
}

// Cegah export kalau tidak ada yang dipilih
const printFormEl = document.getElementById('printForm');
if (printFormEl) {
    printFormEl.addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('.row-check:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal satu data untuk diexport.');
        }
    });
}
</script>
@endsection