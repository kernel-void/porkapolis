<!-- Modal Create Stok Keluar -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('kasir.menu.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Data</h5>
                    <button class="close" type="button" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="nama_menu">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control" placeholder="Masukkan Nama Menu" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="stok">Stok</label>
                        <input type="text" inputmode="numeric" name="stok" class="form-control" placeholder="Masukkan stok" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>

                    <div class="form-group mb-3">
                        <label for="harga">Harga</label>
                        <input type="text" inputmode="numeric" name="harga" class="form-control" placeholder="Masukkan harga" required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>

                    <div class="form-group mb-3">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan..."></textarea>
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
<!-- End Modal Create Stok Keluar -->