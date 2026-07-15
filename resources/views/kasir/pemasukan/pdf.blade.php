<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h3 style="text-align:center">Laporan Pemasukan</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Menu</th>
            <th>Tanggal</th>
            <th>Total Pemesanan</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pemasukan as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->menu->nama_menu }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
            <td>{{ $item->qty }}</td>
            <td>Rp{{ number_format($item->total,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
