<!-- Modal Create Pemasukan -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="{{ route('kasir.pemasukan.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Data</h5>
                    <button class="close" type="button" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>

                <div class="modal-body">

                    <div class="form-group mb-3">
                        <label for="nama_menu">Nama Menu</label>
                        <select name="menu_id" id="menu_id" class="form-control" required>
                            <option value="">-- Pilih Menu --</option>
                            @foreach ($menu as $m)
                                <option value="{{ $m->id }}" data-harga="{{ $m->harga }}">
                                    {{ $m->nama_menu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required >
                    </div>

                    <div class="form-group mb-3">
                        <label for="qty">Jumlah Terjual</label>
                        <input type="text" inputmode="numeric" name="qty" id="qty" class="form-control" required placeholder="Jumlah Terjual" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    </div>

                    <div class="form-group mb-3">
                        <label for="total">Total</label>
                        <input type="text" inputmode="numeric" name="total" id="total" class="form-control" placeholder="Masukkan total">
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
<!-- End Modal Create Pemasukan -->