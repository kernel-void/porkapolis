<!-- Modal Edit Pemasukan -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Ubah Data</h5>
                    <button class="close" type="button" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="edit_tanggal" name="tanggal" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="jumlah">Jumlah</label>
                        <input type="text" id="edit_jumlah" inputmode="numeric" name="jumlah" class="form-control" placeholder="Masukkan jumlah" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>

                    <div class="form-group mb-3">
                        <label for="keterangan">Keterangan</label>
                        <textarea id="edit_keterangan" name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan..." required></textarea>
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

