<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #666;
        }

        .info-row {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .info-row td {
            padding: 2px 0;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data th {
            background-color: #dc3545;
            color: #fff;
            padding: 8px 6px;
            font-size: 11px;
            text-align: left;
            border: 1px solid #dc3545;
        }

        table.data td {
            padding: 6px;
            font-size: 11px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        table.data tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-col {
            width: 30px;
        }

        .tanggal-col {
            width: 90px;
        }

        .jumlah-col {
            width: 110px;
        }

        tfoot td {
            font-weight: bold;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            padding: 8px 6px;
            font-size: 12px;
        }

        .footer-note {
            margin-top: 30px;
            font-size: 10px;
            color: #888;
            text-align: right;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Pengeluaran</h2>
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
            <td>{{ $pengeluaran->count() }} transaksi</td>
        </tr>
    </table>

    @if($pengeluaran->isEmpty())
        <div class="empty-state">
            Tidak ada data pengeluaran untuk ditampilkan.
        </div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th class="no-col text-center">No</th>
                    <th class="tanggal-col">Tanggal</th>
                    <th>Keterangan</th>
                    <th class="jumlah-col text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $totalKeseluruhan = 0; @endphp
                @foreach ($pengeluaran as $key => $keluar)
                    @php $totalKeseluruhan += $keluar->jumlah; @endphp
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($keluar->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ $keluar->keterangan ?? '-' }}</td>
                        <td class="text-right">Rp{{ number_format($keluar->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Total Pengeluaran</td>
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