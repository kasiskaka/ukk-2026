@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- BARU: styling tampilan --}}
<style>
    .dash-hero {
        border: 0;
        border-radius: 20px;
        background: linear-gradient(135deg, #fff 0%, #fff5f8 55%, #fbeed2 100%);
        box-shadow: 0 10px 28px rgba(0,0,0,0.07);
        border-left: 6px solid #d4af37;
    }
    [data-bs-theme="dark"] .dash-hero {
        background: linear-gradient(135deg, #1f1a1d 0%, #2a2025 60%, #33291c 100%);
    }
    .dash-judul { font-weight: 800; position: relative; display: inline-block; }
    .dash-judul::after {
        content: ""; position: absolute; left: 0; bottom: -8px;
        width: 55px; height: 4px; border-radius: 10px; background: #ed8fbc;
    }

    .status-banner {
        display: flex; align-items: center; gap: 18px;
        padding: 20px 24px; border-radius: 18px;
        margin-bottom: 18px; border: 2px solid;
    }
    .status-banner .ikon {
        font-size: 44px; line-height: 1;
        width: 70px; height: 70px;
        display: flex; align-items: center; justify-content: center;
        background: #fff; border-radius: 50%;
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    .status-banner h2 { font-size: 20px; font-weight: 800; margin: 0 0 4px; }
    .status-banner p { margin: 0; font-size: 14px; }

    .status-banner.pending   { background: #fff8e1; border-color: #f0c040; color: #7a5600; }
    .status-banner.disetujui { background: #e6f7ee; border-color: #4caf7d; color: #1b6b43; }
    .status-banner.ditolak   { background: #fdeaea; border-color: #e57373; color: #9b2230; }

    .status-banner.pending { animation: denyut 2s ease-in-out infinite; }
    .status-banner.pending .ikon { animation: pasir-putar 2.4s ease-in-out infinite; }
    .status-banner.disetujui .ikon { animation: muncul .6s ease-out; }

    .titik-tunggu span { display: inline-block; animation: titik 1.2s infinite; }
    .titik-tunggu span:nth-child(2) { animation-delay: .2s; }
    .titik-tunggu span:nth-child(3) { animation-delay: .4s; }

    @keyframes pasir-putar { 0%,40% { transform: rotate(0); } 60%,100% { transform: rotate(180deg); } }
    @keyframes denyut { 0%,100% { box-shadow: 0 0 0 0 rgba(240,192,64,0.45); } 50% { box-shadow: 0 0 0 10px rgba(240,192,64,0); } }
    @keyframes muncul { 0% { transform: scale(.2); opacity: 0; } 70% { transform: scale(1.2); } 100% { transform: scale(1); opacity: 1; } }
    @keyframes titik { 0%,100% { opacity: .2; } 50% { opacity: 1; } }

    .stat-box {
        border-radius: 16px; padding: 16px 18px; background: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        border-left: 5px solid #d4af37;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    [data-bs-theme="dark"] .stat-box { background: #1f1a1d; }
    .stat-box:hover { transform: translateY(-4px); box-shadow: 0 10px 22px rgba(0,0,0,0.1); }
    .stat-box.pink  { border-left-color: #ed8fbc; }
    .stat-box.hijau { border-left-color: #4caf7d; }
    .stat-angka { font-size: 28px; font-weight: 800; line-height: 1; }
    .stat-label { font-size: 13px; color: #8a7a82; margin-top: 4px; }

    .tabel-kartu {
        border: 1px solid rgba(214,177,92,0.35);
        border-radius: 18px; overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06); background: #fff;
    }
    [data-bs-theme="dark"] .tabel-kartu { background: #1f1a1d; }
    .tabel-kartu .tabel-judul {
        padding: 16px 22px; background: rgba(237,143,188,0.12);
        border-bottom: 2px solid #d4af37; font-weight: 700;
    }
    .tabel-kartu th { background: rgba(214,177,92,0.14); padding: 12px 18px; font-weight: 700; }
    .tabel-kartu td { padding: 13px 18px; }
    .tabel-kartu tbody tr:hover { background: rgba(237,143,188,0.08); }

    .badge-status { display: inline-block; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
    .badge-status.pending   { background: #fff1d0; color: #9a6b00; }
    .badge-status.disetujui { background: #dcf5e7; color: #1f7a4d; }
    .badge-status.ditolak   { background: #fde0e0; color: #b02a37; }
    .badge-status.lainnya   { background: #ececec; color: #555; }

    .btn-hover-naik { transition: transform .2s ease; }
    .btn-hover-naik:hover { transform: translateY(-2px); }

    @media (max-width: 575.98px) {
        .status-banner { flex-direction: column; text-align: center; }
    }
    @media (prefers-reduced-motion: reduce) {
        .status-banner, .status-banner .ikon, .titik-tunggu span { animation: none !important; }
    }
</style>

{{-- BARU: hitung ringkasan dan pengajuan terbaru --}}
@php
    $jmlSemua = 0;
    $jmlPending = 0;
    $jmlSetuju = 0;
    $terbaru = null;
    foreach ($peminjamanSaya as $x) {
        if ($terbaru === null) { $terbaru = $x; }
        $jmlSemua++;
        $st = strtolower($x->status);
        if ($st === 'pending') { $jmlPending++; }
        if ($st === 'disetujui') { $jmlSetuju++; }
    }
    $statusTerbaru = $terbaru ? strtolower($terbaru->status) : '';
@endphp

    {{-- BARU: banner status pengajuan terbaru --}}
    @if ($statusTerbaru === 'pending')
        <div class="status-banner pending">
            <div class="ikon">⏳</div>
            <div>
                <h2>Tunggu, pengajuanmu sedang dipending! <span class="titik-tunggu"><span>.</span><span>.</span><span>.</span></span></h2>
                <p>Pengajuan <strong>{{ $terbaru->nama_alat }}</strong> sudah terkirim dan menunggu persetujuan admin.</p>
            </div>
        </div>
    @elseif ($statusTerbaru === 'disetujui')
        <div class="status-banner disetujui">
            <div class="ikon">✅</div>
            <div>
                <h2>Selesai! Pengajuanmu disetujui 🎉</h2>
                <p><strong>{{ $terbaru->nama_alat }}</strong> sudah boleh kamu pinjam. Silakan ambil di lab.</p>
            </div>
        </div>
    @elseif ($statusTerbaru === 'ditolak')
        <div class="status-banner ditolak">
            <div class="ikon">❌</div>
            <div>
                <h2>Pengajuanmu ditolak</h2>
                <p>Pengajuan <strong>{{ $terbaru->nama_alat }}</strong> belum bisa disetujui. Kamu bisa mengajukan alat lain.</p>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm dash-hero">
        <div class="card-body p-4 p-lg-5">
            <span class="badge rounded-pill badge-brand px-3 py-2 mb-3">Role: {{ $user->role }}</span>
            <h1 class="h4 dash-judul mb-2">Halo, {{ $user->username }} 👋</h1>
            <p class="text-secondary mb-1 mt-3">Ini halaman dashboard umum, bisa diakses semua role yang sudah login.</p>
            {{-- BARU --}}
            <p class="text-secondary mb-3">Cari alat jaringan atau komputer di Lab NET &amp; PC, lalu ajukan peminjaman.</p>

            <a href="{{ route('peminjaman.ajukan') }}" class="btn btn-gold btn-hover-naik">
                ➕ Ajukan Peminjaman
            </a>

            <a href="{{ route('peminjaman.katalog') }}" class="btn btn-outline-secondary btn-hover-naik">
                🔍 Cari Alat
            </a>

            {{-- BARU: hiasan alat jaringan dan komputer --}}
            @include('partials.alat_jaringan')
        </div>
    </div>

    {{-- BARU: kartu angka --}}
    <div class="row g-3 mt-1">
        <div class="col-12 col-md-4">
            <div class="stat-box">
                <div class="stat-angka">{{ $jmlSemua }}</div>
                <div class="stat-label">💻 Total pengajuan</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-box pink">
                <div class="stat-angka">{{ $jmlPending }}</div>
                <div class="stat-label">⏳ Menunggu persetujuan</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-box hijau">
                <div class="stat-angka">{{ $jmlSetuju }}</div>
                <div class="stat-label">✅ Disetujui</div>
            </div>
        </div>
    </div>

    <div class="tabel-kartu mt-4">
        <div class="tabel-judul">📋 Peminjaman Saya</div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <tr>
                    <th>Alat</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                </tr>

                @forelse ($peminjamanSaya as $p)
                @php
                    $s = strtolower($p->status);
                    $kelas = 'lainnya';
                    if ($s === 'pending') { $kelas = 'pending'; }
                    if ($s === 'disetujui') { $kelas = 'disetujui'; }
                    if ($s === 'ditolak') { $kelas = 'ditolak'; }
                @endphp
                <tr>
                    <td class="fw-semibold">🖥️ {{ $p->nama_alat }}</td>
                    <td>{{ $p->tanggal_pinjam }}</td>
                    <td>{{ $p->tgl_kembali ?? '-' }}</td>
                    <td><span class="badge-status {{ $kelas }}">{{ ucfirst($p->status) }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-secondary py-5">
                        📡 Belum ada pengajuan peminjaman.
                    </td>
                </tr>
                @endforelse
            </table>
        </div>
    </div>

@endsection