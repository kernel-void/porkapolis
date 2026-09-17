<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan User</title>
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
        .footer-note { margin-top: 30px; font-size: 10px; color: #888; text-align: right; }
        .empty-state { text-align: center; padding: 30px; color: #999; font-style: italic; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan User</h2>
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
            <td>{{ $users->count() }} user</td>
        </tr>
    </table>

    @if($users->isEmpty())
        <div class="empty-state">Tidak ada data user untuk ditampilkan.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th class="text-center" style="width:30px;">No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->username }}</td>
                        <td>{{ ucfirst($roleLabels[$item->role_id] ?? $item->role_id) }}</td>
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
