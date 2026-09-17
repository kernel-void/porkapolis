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

                <div class="d-flex align-items-center">
                    {{-- Desktop: Tombol Biasa --}}
                    <div class="d-none d-md-flex gap-1">
                        @can('pemasukan.create')
                            <button class="btn btn-primary btn-sm mr-1" data-bs-toggle="modal" data-bs-target="#createModal" title="Create Data">
                                <i class="fas fa-plus"></i> Tambah Data
                            </button>
                        @endcan

                        @can('pemasukan.export')
                            <button type="submit" form="printForm" class="btn btn-outline-secondary btn-sm" title="Export PDF">
                                <i class="fas fa-print"></i> Export PDF
                            </button>
                        @endcan

                        @can('pemasukan.restore')
                            <button class="btn btn-secondary btn-sm ml-1" data-bs-toggle="modal" data-bs-target="#trashModal" title="Data Terhapus">
                                <i class="fas fa-trash-restore"></i> Data Terhapus ({{ $trashed->count() }})
                            </button>
                        @endcan
                    </div>

                    {{-- Mobile: tombol tunggal kalau cuma 1 aksi, selain itu hamburger --}}
                    @php
                        $mobileActions = collect([
                            auth()->user()->can('pemasukan.create'),
                            auth()->user()->can('pemasukan.export'),
                            auth()->user()->can('pemasukan.restore'),
                        ])->filter()->count();
                    @endphp

                    @if ($mobileActions === 1)
                        <div class="d-md-none">
                            @if (auth()->user()->can('pemasukan.create'))
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                    <i class="fas fa-plus"></i> Tambah Data
                                </button>
                            @elseif (auth()->user()->can('pemasukan.export'))
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
                                @can('pemasukan.create')
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createModal">
                                        <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Tambah Data
                                    </button>
                                @endcan
                                @can('pemasukan.export')
                                    <button type="submit" form="printForm" class="dropdown-item">
                                        <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i> Export PDF
                                    </button>
                                @endcan
                                @can('pemasukan.restore')
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
                <form id="printForm" action="{{ route('admin.pemasukan.exportPdf') }}" method="POST" target="_blank">
                    @csrf
                </form>
                <div class="table-responsive pt-2">
                    <table class="table table-bordered text-center table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                @can('pemasukan.export')
                                    <th>
                                        <input type="checkbox" id="checkAll" class="mr-1">
                                        No
                                    </th>
                                @else
                                    <th>No</th>
                                @endcan
                                <th>Tanggal</th>
                                <th>Rincian Menu</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                                @canany(['pemasukan.update', 'pemasukan.delete'])
                                    <th>Aksi</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemasukan as $key => $masukan)
                            <tr>
                                @can('pemasukan.export')
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <input type="checkbox" name="pemasukan_ids[]" value="{{ $masukan->id }}" form="printForm" class="row-check mr-2">
                                            <span>{{ $pemasukan->firstItem() + $key }}</span>
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $pemasukan->firstItem() + $key }}</td>
                                @endcan
                                <td>{{ \Carbon\Carbon::parse($masukan->tanggal)->translatedFormat('d F Y') }}</td>
                                <td class="text-start">
                                    <button type="button" class="btn btn-info btn-sm rincianBtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rincianModal"
                                        data-tanggal="{{ \Carbon\Carbon::parse($masukan->tanggal)->translatedFormat('d F Y') }}"
                                        data-total="Rp{{ number_format($masukan->total, 0, ',', '.') }}"
                                        data-items="{{ $masukan->details->map(fn ($d) => ['menu' => $d->menu->nama_menu ?? '(menu dihapus)', 'qty' => $d->qty, 'subtotal' => $d->subtotal])->toJson() }}">
                                        <i class="fas fa-list"></i> Lihat ({{ $masukan->details->count() }})
                                    </button>
                                </td>
                                <td class="fw-bold">Rp{{ number_format($masukan->total, 0, ',', '.') }}</td>
                                <td>{{ $masukan->keterangan ?? '-' }}</td>
                                @canany(['pemasukan.update', 'pemasukan.delete'])
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        @can('pemasukan.update')
                                            <button class="btn btn-warning btn-sm btn-circle editBtn mr-1"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                data-url="{{ route('admin.pemasukan.update', $masukan->id) }}"
                                                data-tanggal="{{ $masukan->tanggal }}"
                                                data-keterangan="{{ $masukan->keterangan }}"
                                                data-items="{{ $masukan->details->map(fn($d) => ['menu_id' => $d->menu_id, 'qty' => $d->qty])->toJson() }}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        @endcan

                                        @can('pemasukan.delete')
                                            <button class="btn btn-danger btn-sm btn-circle deleteBtn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-url="{{ route('admin.pemasukan.destroy', $masukan->id) }}">
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
                    {{ $pemasukan->links() }}
                </div>
            </div>
        </div>
</div>
@can('pemasukan.create')
    @include('admin.pemasukan.create')
@endcan
@can('pemasukan.update')
    @include('admin.pemasukan.edit')
@endcan
@can('pemasukan.delete')
    @include('admin.pemasukan.delete')
@endcan

{{-- Modal Rincian Menu --}}
<div class="modal fade" id="rincianModal" tabindex="-1" aria-labelledby="rincianModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rincianModalLabel">Rincian Menu</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Tanggal: <strong id="rincianTanggal"></strong></span>
                    <span>Total: <strong id="rincianTotal"></strong></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Menu</th>
                                <th style="width: 80px;">Qty</th>
                                <th style="width: 130px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="rincianBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@can('pemasukan.restore')
<div class="modal fade" id="trashModal" tabindex="-1" aria-labelledby="trashModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trashModalLabel">Data Pemasukan Terhapus</h5>
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
                                    <th>Tanggal</th>
                                    <th>Rincian</th>
                                    <th>Total</th>
                                    <th>Dihapus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashed as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="text-start">
                                        <ul class="mb-0 ps-3">
                                            @foreach ($item->details as $detail)
                                                <li>
                                                    {{ $detail->menu->nama_menu ?? '(menu dihapus)' }}
                                                    <span class="text-muted">&times; {{ $detail->qty }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td>{{ $item->deleted_at->translatedFormat('d F Y, H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.pemasukan.restore', $item->id) }}" method="POST">
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

@can('pemasukan.export')
    @include('components.no-selection-modal')
@endcan

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // ==== Builder baris item, dipakai untuk Create & Edit ====
    function buildItemRow(wrapperId, index, menuOptionsHTML, selectedMenuId = '', qtyValue = '') {
        const row = document.createElement('div');
        row.classList.add('row', 'item-row', 'mb-2', 'align-items-end');
        row.innerHTML = `
            <div class="col-5">
                <select name="items[${index}][menu_id]" class="form-control menu-select" required>
                    ${menuOptionsHTML}
                </select>
            </div>
            <div class="col-3">
                <input type="number" name="items[${index}][qty]" class="form-control qty-input" placeholder="Qty" min="1" value="${qtyValue}" required>
            </div>
            <div class="col-3">
                <input type="text" class="form-control subtotal-display" placeholder="Subtotal" disabled>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-danger btn-sm remove-row">×</button>
            </div>
        `;
        document.getElementById(wrapperId).appendChild(row);

        if (selectedMenuId) {
            row.querySelector('.menu-select').value = selectedMenuId;
        }

        return row;
    }

    function attachRowEvents(row, wrapperId, grandTotalId) {
        function hitung() { hitungGrandTotal(wrapperId, grandTotalId); }

        row.querySelector('.menu-select').addEventListener('change', hitung);
        row.querySelector('.qty-input').addEventListener('input', hitung);
        row.querySelector('.remove-row').addEventListener('click', function () {
            row.remove();
            hitung();
            toggleRemoveButtons(wrapperId);
        });
    }

    function toggleRemoveButtons(wrapperId) {
        const rows = document.querySelectorAll('#' + wrapperId + ' .item-row');
        rows.forEach(row => {
            row.querySelector('.remove-row').style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }

    function hitungGrandTotal(wrapperId, grandTotalId) {
        let total = 0;
        document.querySelectorAll('#' + wrapperId + ' .item-row').forEach(row => {
            const select = row.querySelector('.menu-select');
            const qtyInput = row.querySelector('.qty-input');
            const subtotalDisplay = row.querySelector('.subtotal-display');

            const selectedOption = select.options[select.selectedIndex];
            const harga = selectedOption ? parseInt(selectedOption.dataset.harga || 0) : 0;
            const qty = parseInt(qtyInput.value) || 0;
            const subtotal = harga * qty;

            subtotalDisplay.value = subtotal > 0 ? 'Rp' + formatRupiah(subtotal) : '';
            total += subtotal;
        });
        document.getElementById(grandTotalId).textContent = formatRupiah(total);
    }

    // ==== Init form Create (hanya jika modal create dirender / user punya izin) ====
    const createModalEl = document.getElementById('createModal');
    let createMenuOptions = null;

    if (createModalEl) {
        createMenuOptions = createModalEl.querySelector('.menu-select').innerHTML;
        let createIndex = 1;

        attachRowEvents(createModalEl.querySelector('.item-row'), 'itemsWrapper', 'grandTotal');

        const addRowBtn = document.getElementById('addRow');
        if (addRowBtn) {
            addRowBtn.addEventListener('click', function () {
                const row = buildItemRow('itemsWrapper', createIndex, createMenuOptions);
                attachRowEvents(row, 'itemsWrapper', 'grandTotal');
                toggleRemoveButtons('itemsWrapper');
                createIndex++;
            });
        }
    }

    // ==== Init form Edit (hanya jika modal edit dirender / user punya izin) ====
    const editModalEl = document.getElementById('editModal');

    if (editModalEl) {
        const editMenuOptions = createMenuOptions ?? editModalEl.querySelector('.menu-select')?.innerHTML;

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.editBtn');
            if (!btn) return;

            document.getElementById('editForm').action = btn.dataset.url;
            document.getElementById('edit_tanggal').value = btn.dataset.tanggal;
            document.getElementById('edit_keterangan').value = btn.dataset.keterangan;

            const wrapper = document.getElementById('editItemsWrapper');
            wrapper.innerHTML = '';

            const items = JSON.parse(btn.dataset.items || '[]');
            let editIndex = 0;

            items.forEach(item => {
                const row = buildItemRow('editItemsWrapper', editIndex, editMenuOptions, item.menu_id, item.qty);
                attachRowEvents(row, 'editItemsWrapper', 'editGrandTotal');
                editIndex++;
            });

            toggleRemoveButtons('editItemsWrapper');
            hitungGrandTotal('editItemsWrapper', 'editGrandTotal');
        });

        const editAddRowBtn = document.getElementById('editAddRow');
        if (editAddRowBtn) {
            editAddRowBtn.addEventListener('click', function () {
                const wrapper = document.getElementById('editItemsWrapper');
                const nextIndex = wrapper.querySelectorAll('.item-row').length;
                const row = buildItemRow('editItemsWrapper', nextIndex, editMenuOptions);
                attachRowEvents(row, 'editItemsWrapper', 'editGrandTotal');
                toggleRemoveButtons('editItemsWrapper');
            });
        }
    }

});

// Script Modal Delete
document.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.deleteBtn');
    if (!deleteBtn) return;

    document.getElementById('deleteForm').action = deleteBtn.dataset.url;
});

// Script Modal Rincian Menu
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.rincianBtn');
    if (!btn) return;

    const items = JSON.parse(btn.dataset.items || '[]');
    const tbody = document.getElementById('rincianBody');
    tbody.innerHTML = '';

    const rupiah = (angka) => 'Rp' + new Intl.NumberFormat('id-ID').format(angka);

    items.forEach(function (item, index) {
        const tr = document.createElement('tr');

        [
            { text: index + 1 },
            { text: item.menu, align: 'text-start' },
            { text: item.qty },
            { text: rupiah(item.subtotal), align: 'text-end' },
        ].forEach(function (cell) {
            const td = document.createElement('td');
            if (cell.align) td.className = cell.align;
            td.textContent = cell.text;
            tr.appendChild(td);
        });

        tbody.appendChild(tr);
    });

    document.getElementById('rincianTanggal').textContent = btn.dataset.tanggal;
    document.getElementById('rincianTotal').textContent = btn.dataset.total;
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