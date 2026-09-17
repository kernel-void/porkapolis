<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Menu</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; color: #666; }
        .info-row { width: 100%; margin-bottom: 15px; font-size: 11px; }
        .info-row td { padding: 2px 0; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background-color: #4e73df; color: #fff; padding: 8px 6px; font-size: 11px; text-align: left; border: 1px solid #4e73df; }
        table.data td { padding: 6px; font-size: 11px; border: 1px solid #ddd; vertical-align: top; }
        table.data tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        tfoot td { font-weight: bold; background-color: #f1f1f1; border: 1px solid #ddd; padding: 8px 6px; font-size: 12px; }
        .footer-note { margin-top: 30px; font-size: 10px; color: #888; text-align: right; }
        .empty-state { text-align: center; padding: 30px; color: #999; font-style: italic; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Menu</h2>
        <p>{{ $pengaturan->nama_aplikasi ?? 'Aplikasi' }}</p>
    </div>

    <table class="info-row">
        <tr>
            <td style="width: 100px;">Tanggal Cetak</td>
            <td style="width: 10px;">:</td>
            <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td>Jumlah Data</td>
            <td>:</td>
            <td>{{ $menu->count() }} menu</td>
        </tr>
    </table>

    @if($menu->isEmpty())
        <div class="empty-state">Tidak ada data menu untuk ditampilkan.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th class="text-center" style="width:30px;">No</th>
                    <th>Nama Menu</th>
                    <th class="text-center">Stok</th>
                    <th class="text-right">Harga</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menu as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $item->nama_menu }}</td>
                        <td class="text-center">{{ $item->stok }}</td>
                        <td class="text-right">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer-note">
        Dicetak oleh sistem {{ $pengaturan->nama_aplikasi ?? 'Aplikasi' }} pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>
