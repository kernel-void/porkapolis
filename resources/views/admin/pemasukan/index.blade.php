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
                                <th>Tanggal</th>
                                <th>Rincian Menu</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
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
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <button class="btn btn-warning btn-sm btn-circle editBtn mr-1"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-url="{{ route('admin.pemasukan.update', $masukan->id) }}"
                                            data-tanggal="{{ $masukan->tanggal }}"
                                            data-keterangan="{{ $masukan->keterangan }}"
                                            data-items="{{ $masukan->details->map(fn($d) => ['menu_id' => $d->menu_id, 'qty' => $d->qty])->toJson() }}">
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

    // ==== Init form Create ====
    const createMenuOptions = document.querySelector('#createModal .menu-select').innerHTML;
    let createIndex = 1;
    attachRowEvents(document.querySelector('#createModal .item-row'), 'itemsWrapper', 'grandTotal');

    document.getElementById('addRow').addEventListener('click', function () {
        const row = buildItemRow('itemsWrapper', createIndex, createMenuOptions);
        attachRowEvents(row, 'itemsWrapper', 'grandTotal');
        toggleRemoveButtons('itemsWrapper');
        createIndex++;
    });

    // ==== Init form Edit (isi ulang saat tombol edit diklik) ====
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.editBtn');
        if (!btn) return;

        document.getElementById('editForm').action = btn.dataset.url;
        document.getElementById('edit_tanggal').value = btn.dataset.tanggal;
        document.getElementById('edit_keterangan').value = btn.dataset.keterangan;

        const wrapper = document.getElementById('editItemsWrapper');
        wrapper.innerHTML = ''; // kosongkan dulu

        const items = JSON.parse(btn.dataset.items || '[]');
        let editIndex = 0;

        items.forEach(item => {
            const row = buildItemRow('editItemsWrapper', editIndex, createMenuOptions, item.menu_id, item.qty);
            attachRowEvents(row, 'editItemsWrapper', 'editGrandTotal');
            editIndex++;
        });

        toggleRemoveButtons('editItemsWrapper');
        hitungGrandTotal('editItemsWrapper', 'editGrandTotal');
    });

    document.getElementById('editAddRow').addEventListener('click', function () {
        const wrapper = document.getElementById('editItemsWrapper');
        const nextIndex = wrapper.querySelectorAll('.item-row').length;
        const row = buildItemRow('editItemsWrapper', nextIndex, createMenuOptions);
        attachRowEvents(row, 'editItemsWrapper', 'editGrandTotal');
        toggleRemoveButtons('editItemsWrapper');
    });

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