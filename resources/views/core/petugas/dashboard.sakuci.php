<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Petugas</title>
</head>
<body>
    <h1>Selamat Datang, <?= $user->nama ?>!</h1>
    <p>Ini Halaman Khusus Petugas (Pengelola Alat & Peminjaman)</p>

    <hr>

    <h3>Ringkasan Data:</h3>
    <ul>
        <li>Peminjaman Pending: <strong><?= $totalPending ?></strong></li>
        <li>Peminjaman Disetujui: <strong><?= $totalDisetujui ?></strong></li>
        <li>Total Data Alat: <strong><?= $totalAlat ?></strong></li>
    </ul>

    <hr>

    <h3>Menu Tugas Petugas:</h3>
    <ul>
        <li><a href="/admin/peminjaman">Persetujuan Peminjaman</a></li>
        <li><a href="/admin/pengembalian">Kelola Pengembalian</a></li>
        <li><a href="/admin/alat">Data Alat</a></li>
    </ul>

    <br>
    <form action="/logout" method="POST">
        <button type="submit">Logout</button>
    </form>
</body>
</html>