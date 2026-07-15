<!-- Modal Edit Pemasukan -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Ubah Data</h5>
                    <button class="close" type="button" data-bs-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="menu_id">Nama Menu</label>
                        <select name="menu_id" id="edit_menu_id" class="form-control" required>
                            <option value="">-- Pilih Menu --</option>
                            @foreach ($menu as $m)
                                <option value="{{ $m->id }}" data-harga="{{ $m->harga }}">
                                    {{ $m->nama_menu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_qty">Jumlah Terjual</label>
                        <input type="number" name="qty" id="edit_qty" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_total">Total</label>
                        <input type="text" name="total" id="edit_total" class="form-control" required readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_keterangan">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan..."></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
</div>
<!-- End Modal Edit Pemasukan -->