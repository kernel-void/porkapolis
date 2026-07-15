<!-- Modal Restore -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Data Terhapus</h5>
                <button class="close" type="button" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>

            <div class="modal-body">
                @if ($deletedData->isEmpty())
                    <div class="p-3 text-center text-muted font-italic">
                        Tidak ada data yang terhapus.
                    </div>
                @else
                    <div class="table-responsive pt-2">
                        <table class="table table-bordered text-center table-hover" id="restoreTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Menu</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Terjual</th>
                                    <th>Total</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deletedData as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->menu->nama_menu ?? '(menu dihapus)' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('admin.pemasukan.restore', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm btn-circle" title="Restore">
                                                    <i class="fas fa-sync"></i>
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
<!-- End Modal Restore -->
