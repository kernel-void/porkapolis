<!-- Modal Edit Pemasukan -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form action="" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                    <button class="close" type="button" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="edit_tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                    </div>

                    <label>Daftar Menu</label>
                    <div id="editItemsWrapper">
                        {{-- baris di-generate lewat JS saat modal dibuka --}}
                    </div>

                    <button type="button" id="editAddRow" class="btn btn-outline-primary btn-sm mt-2">+ Tambah Menu</button>

                    <hr>
                    <div class="text-end fw-bold">
                        Total: Rp<span id="editGrandTotal">0</span>
                    </div>

                    <div class="form-group mb-3 mt-3">
                        <label for="edit_keterangan">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2" placeholder="Masukkan keterangan..."></textarea>
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
<!-- End Modal Edit Pemasukan -->