<!-- Modal Create Pemasukan -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.pemasukan.store') }}" method="POST" id="formPemasukan">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Data</h5>
                    <button class="close" type="button" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <label>Daftar Menu</label>
                    <div id="itemsWrapper">
                        <div class="row item-row mb-2 align-items-end">
                            <div class="col-5">
                                <select name="items[0][menu_id]" class="form-control menu-select" required>
                                    <option value="">-- Pilih Menu --</option>
                                    @foreach ($menu as $m)
                                        <option value="{{ $m->id }}" data-harga="{{ $m->harga }}">
                                            {{ $m->nama_menu }} - Rp{{ number_format($m->harga) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="items[0][qty]" class="form-control qty-input" placeholder="Qty" min="1" required>
                            </div>
                            <div class="col-3">
                                <input type="text" class="form-control subtotal-display" placeholder="Subtotal" disabled>
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-danger btn-sm remove-row" style="display:none;">×</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addRow" class="btn btn-outline-primary btn-sm mt-2">+ Tambah Menu</button>

                    <hr>
                    <div class="text-end fw-bold">
                        Total: Rp<span id="grandTotal">0</span>
                    </div>

                    <div class="form-group mb-3 mt-3">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukkan keterangan..."></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- End Modal Create Pemasukan -->