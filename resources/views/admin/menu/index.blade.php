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

                <div class="d-flex align-items-center">
                    {{-- Desktop: Tombol Biasa --}}
                    <div class="d-none d-md-flex gap-1">
                        @can('menu.create')
                            <button class="btn btn-primary btn-sm mr-1" data-bs-toggle="modal" data-bs-target="#createModal" title="Create Data">
                                <i class="fas fa-plus"></i> Tambah Data
                            </button>
                        @endcan

                        @can('menu.export')
                            <button type="submit" form="printForm" class="btn btn-outline-secondary btn-sm" title="Export PDF">
                                <i class="fas fa-print"></i> Export PDF
                            </button>
                        @endcan

                        @can('menu.restore')
                            <button class="btn btn-secondary btn-sm ml-1" data-bs-toggle="modal" data-bs-target="#trashModal" title="Data Terhapus">
                                <i class="fas fa-trash-restore"></i> Data Terhapus ({{ $trashed->count() }})
                            </button>
                        @endcan
                    </div>

                    {{-- Mobile: tombol tunggal kalau cuma 1 aksi, selain itu hamburger --}}
                    @php
                        $mobileActions = collect([
                            auth()->user()->can('menu.create'),
                            auth()->user()->can('menu.export'),
                            auth()->user()->can('menu.restore'),
                        ])->filter()->count();
                    @endphp

                    @if ($mobileActions === 1)
                        <div class="d-md-none">
                            @if (auth()->user()->can('menu.create'))
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </button>
                            @elseif (auth()->user()->can('menu.export'))
                                <button type="submit" form="printForm" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-print"></i> Export PDF
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#trashModal">
                                    <i class="fas fa-trash-restore"></i> Data Terhapus ({{ $trashed->count() }})
                                </button>
                            @endif
                        </div>
                    @elseif ($mobileActions > 1)
                        <div class="dropdown no-arrow d-md-none">
                            <a class="dropdown-toggle" href="#" role="button" id="mobileMenuButton"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="mobileMenuButton">
                                <div class="dropdown-header">Opsi:</div>
                                @can('menu.create')
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createModal">
                                        <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Tambah Data
                                    </button>
                                @endcan
                                @can('menu.export')
                                    <button type="submit" form="printForm" class="dropdown-item">
                                        <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i> Export PDF
                                    </button>
                                @endcan
                                @can('menu.restore')
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#trashModal">
                                        <i class="fas fa-trash-restore fa-sm fa-fw mr-2 text-gray-400"></i> Data Terhapus ({{ $trashed->count() }})
                                    </button>
                                @endcan
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card-body">
                <form id="printForm" action="{{ route('admin.menu.exportPdf') }}" method="POST" target="_blank">
                    @csrf
                </form>
                <div class="pt-2 table-responsive">
                    <table class="table table-bordered text-center" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                @can('menu.export')
                                    <th>
                                        <input type="checkbox" id="checkAll" class="mr-1">
                                        No
                                    </th>
                                @else
                                    <th>No</th>
                                @endcan
                                <th>Nama Menu</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Keterangan</th>
                                @canany(['menu.update', 'menu.delete'])
                                    <th>Aksi</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menu as $key => $menuItem)
                            <tr>
                                @can('menu.export')
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <input type="checkbox" name="menu_ids[]" value="{{ $menuItem->id }}" form="printForm" class="row-check mr-2">
                                            <span>{{ $menu->firstItem() + $key }}</span>
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $menu->firstItem() + $key }}</td>
                                @endcan
                                <td>{{ $menuItem->nama_menu }}</td>
                                <td>{{ $menuItem->stok }}</td>
                                <td>Rp{{ number_format($menuItem->harga, 0, ',', '.') }}</td>
                                <td>{{ $menuItem->keterangan ?? '-' }}</td>
                                @canany(['menu.update', 'menu.delete'])
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        @can('menu.update')
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
                                        @endcan

                                        @can('menu.delete')
                                            <button class="btn btn-danger btn-sm btn-circle deleteBtn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-url="{{ route('admin.menu.destroy', $menuItem->id) }}">
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
                </div>
                <div class="mt-3">
                    {{ $menu->links() }}
                </div>
            </div>
        </div>
</div>
@can('menu.create')
    @include('admin.menu.create')
@endcan
@can('menu.update')
    @include('admin.menu.edit')
@endcan
@can('menu.delete')
    @include('admin.menu.delete')
@endcan

@can('menu.restore')
<div class="modal fade" id="trashModal" tabindex="-1" aria-labelledby="trashModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trashModalLabel">Data Menu Terhapus</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                @if ($trashed->isEmpty())
                    <p class="text-center text-muted mb-0">Tidak ada data terhapus.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" width="100%">
                            <thead>
                                <tr>
                                    <th>Nama Menu</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Dihapus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashed as $item)
                                <tr>
                                    <td>{{ $item->nama_menu }}</td>
                                    <td>{{ $item->stok }}</td>
                                    <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ $item->deleted_at->translatedFormat('d F Y, H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.menu.restore', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-trash-restore"></i> Pulihkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endcan

@can('menu.export')
    @include('components.no-selection-modal')
@endcan
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
            const modalEl = document.getElementById('noSelectionModal');
            if (modalEl) new bootstrap.Modal(modalEl).show();
        }
    });
}
</script>
@endsection