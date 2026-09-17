<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemasukan</title>
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
        .rincian { margin: 0; padding-left: 14px; }
        tfoot td { font-weight: bold; background-color: #f1f1f1; border: 1px solid #ddd; padding: 8px 6px; font-size: 12px; }
        .footer-note { margin-top: 30px; font-size: 10px; color: #888; text-align: right; }
        .empty-state { text-align: center; padding: 30px; color: #999; font-style: italic; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Pemasukan</h2>
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
            <td>{{ $pemasukan->count() }} transaksi</td>
        </tr>
    </table>

    @if($pemasukan->isEmpty())
        <div class="empty-state">Tidak ada data pemasukan untuk ditampilkan.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th class="text-center" style="width:30px;">No</th>
                    <th style="width:90px;">Tanggal</th>
                    <th>Rincian Menu</th>
                    <th class="text-right" style="width:110px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php $totalKeseluruhan = 0; @endphp
                @foreach ($pemasukan as $key => $item)
                    @php $totalKeseluruhan += $item->total; @endphp
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>
                            <ul class="rincian">
                                @foreach ($item->details as $detail)
                                    <li>
                                        {{ $detail->menu->nama_menu ?? '(menu dihapus)' }}
                                        &times; {{ $detail->qty }}
                                        = Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-right">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Total Pemasukan</td>
                    <td class="text-right">Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="footer-note">
        Dicetak oleh sistem {{ $pengaturan->nama_aplikasi ?? 'Aplikasi' }} pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>
