<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemantauan Peminjaman</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #222; margin: 24px; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        .sub { color: #666; font-size: 13px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        th { background: #f1e3ea; }
        .tengah { text-align: center; }
        .kanan { text-align: right; }
        .total { margin-top: 14px; font-size: 14px; font-weight: bold; }
        .aksi { margin-bottom: 14px; }
        .aksi button, .aksi .tombol-keluar { padding: 8px 16px; cursor: pointer; font-size: 13px; }
        .aksi .tombol-keluar { display: inline-block; margin-right: 8px; border: 1px solid #767676; border-radius: 3px; background: #efefef; color: #222; text-decoration: none; }
        @media print { .aksi { display: none; } body { margin: 0; } }
    </style>
</head>
<body>

    <div class="aksi">
        <a href="{{ route('peminjaman.pemantauan') }}" class="tombol-keluar">&larr; Keluar</a>
        <button type="button" onclick="window.print()">Cetak</button>
    </div>

    <h1>Laporan Pemantauan Peminjaman</h1>
    <div class="sub">Lab NET &amp; PC &mdash; dicetak {{ date('d-m-Y H:i') }}</div>

    @php
        $totalDenda = 0;
        $no = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th class="tengah">No</th>
                <th>Peminjam</th>
                <th>Nama Alat</th>
                <th class="tengah">Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Keterangan</th>
                <th class="kanan">Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datap as $p)
                @php
                    $no++;
                    $jatuhTempo = date('Y-m-d', strtotime(date('Y-m-d', strtotime((string) $p->tanggal_pinjam)) . ' +3 days'));
                    $telat = (int) floor((strtotime(date('Y-m-d')) - strtotime($jatuhTempo)) / 86400);
                    $denda = (int) $p->denda;
                    $totalDenda += $denda;
                @endphp
                <tr>
                    <td class="tengah">{{ $no }}</td>
                    <td>{{ $namaUser[$p->id_user] ?? ('#' . $p->id_user) }}</td>
                    <td>{{ $namaAlat[$p->id_alat] ?? ('#' . $p->id_alat) }}</td>
                    <td class="tengah">{{ $p->jumlah }} Unit</td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>{{ $jatuhTempo }}</td>
                    <td>{{ $telat > 0 ? 'Telat ' . $telat . ' hari' : 'Belum jatuh tempo' }}</td>
                    <td class="kanan">Rp {{ number_format($denda, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            @if ($no === 0)
                <tr><td colspan="8" class="tengah">Tidak ada alat yang sedang dipinjam.</td></tr>
            @endif
        </tbody>
    </table>

    <div class="total">Total denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>

    <script>
        window.addEventListener('load', function () { window.print(); });
    </script>
</body>
</html>