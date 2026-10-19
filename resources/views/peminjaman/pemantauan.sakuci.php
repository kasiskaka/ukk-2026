@extends('layouts.app')

@section('title', config('app.name') . ' -- Pemantauan Peminjaman')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<?php
    // Ringkasan + jatuh tempo (PHP sederhana, hanya membaca $datap)
    $pmToday = strtotime(date('Y-m-d'));
    $pmLamaPinjam = 3; // samakan dengan LAMA_PINJAM_HARI di PeminjamanController
    $pmTotal = 0; $pmTelat = 0; $pmDenda = 0;
    $pmInfo = array(); // id_peminjaman => [jatuh tempo, sisa hari]

    // Hanya petugas yang boleh mencetak laporan (admin tidak)
    $pmUser = \App\Models\User::current();
    $pmBolehCetak = $pmUser && $pmUser->role === 'petugas';
    foreach ($datap as $pmx) {
        $pmTotal++;
        $pmDenda += (int) $pmx->denda;
        $pmDue  = $pmx->tanggal_pinjam ? strtotime($pmx->tanggal_pinjam . ' +' . $pmLamaPinjam . ' days') : false;
        $pmSisa = $pmDue ? (int) round(($pmDue - $pmToday) / 86400) : null;
        if ($pmSisa !== null && $pmSisa < 0) { $pmTelat++; }
        $pmInfo[$pmx->id_peminjaman] = array($pmDue, $pmSisa);
    }
?>

<style>
    .pm-page{
        --pm-card:#2a2428; --pm-card-2:#352c32; --pm-line:rgba(255,255,255,.08);
        --pm-text:#f4eef1; --pm-muted:#a99ea4;
        --pm-gold:#e3c06c; --pm-gold-d:#c9a45c; --pm-rose:#e8a0bb; --pm-rose-d:#d97ea1;
        --pm-ink:#2a1f24;
        max-width:1500px; margin:0 auto; color:var(--pm-text);
    }
    .pm-head{
        background:linear-gradient(120deg,#46323b 0%,#2d2429 65%);
        border:1px solid var(--pm-line); border-left:4px solid var(--pm-gold);
        border-radius:1rem; padding:1.5rem 1.8rem; box-shadow:0 10px 30px rgba(0,0,0,.25);
    }
    .pm-chip{
        display:inline-flex; align-items:center; gap:.4rem;
        border:1px solid rgba(227,192,108,.55); color:var(--pm-gold);
        border-radius:999px; padding:.3rem .85rem; font-size:.74rem; font-weight:600; letter-spacing:.06em;
    }
    .pm-h1{ font-weight:700; font-size:1.9rem; color:var(--pm-text); margin:.5rem 0 .35rem; }
    .pm-sub{ color:var(--pm-muted); font-size:.9rem; margin:0; }

    .pm-alert{ background:rgba(80,180,130,.12); border:1px solid rgba(80,180,130,.35); color:#bfe8d3; border-radius:.9rem; padding:.8rem 1rem; }
    .pm-alert-err{ background:rgba(220,80,100,.12); border:1px solid rgba(220,80,100,.35); color:#ffb3c0; border-radius:.9rem; padding:.8rem 1rem; }

    .pm-box{
        background:var(--pm-card); border:1px solid var(--pm-line); border-left:4px solid var(--pm-gold);
        border-radius:.9rem; padding:1rem 1.25rem; height:100%; box-shadow:0 8px 24px rgba(0,0,0,.2);
    }
    .pm-box .pm-lbl{ font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; color:var(--pm-muted); }
    .pm-box .pm-num{ font-size:1.6rem; font-weight:800; line-height:1.2; color:var(--pm-gold); margin-top:.15rem; }
    .pm-box.pink{ border-left-color:var(--pm-rose-d); }
    .pm-box.pink .pm-num{ color:var(--pm-rose); }
    .pm-box.gray{ border-left-color:#8f8699; }
    .pm-box.gray .pm-num{ color:var(--pm-text); }

    .pm-panelx{ background:var(--pm-card); border:1px solid var(--pm-line); border-radius:1rem; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.22); }
    .pm-panelx-head{
        display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.5rem;
        border-bottom:1px solid var(--pm-line); background:linear-gradient(90deg,rgba(232,160,187,.14),transparent 70%);
    }
    .pm-panelx-head h6{ margin:0; font-weight:700; color:var(--pm-text); }
    .pm-panelx-head .bi{ color:var(--pm-gold); }
    .pm-pill{ border:1px solid rgba(227,192,108,.35); color:var(--pm-gold); border-radius:999px; padding:.3rem .85rem; font-size:.78rem; }
    .pm-panelx-foot{ padding:.9rem 1.5rem; border-top:1px solid var(--pm-line); display:flex; justify-content:flex-end; }

    /* Tombol cetak laporan */
    .pm-btn-print{
        display:inline-flex; align-items:center; gap:.4rem; cursor:pointer;
        background:linear-gradient(120deg,var(--pm-gold),var(--pm-gold-d)); color:var(--pm-ink);
        border:0; border-radius:999px; padding:.4rem 1rem; font-size:.8rem; font-weight:700;
    }
    .pm-btn-print:hover{ filter:brightness(1.07); }
    .pm-btn-print .bi{ color:var(--pm-ink); }

    .pm-page .table{
        --bs-table-bg:transparent; --bs-table-color:var(--pm-text); --bs-table-striped-bg:transparent;
        --bs-table-hover-bg:rgba(232,160,187,.07); --bs-table-hover-color:var(--pm-text);
        --bs-table-border-color:var(--pm-line); color:var(--pm-text); margin:0;
    }
    .pm-page .table thead th{
        background:var(--pm-card-2) !important; color:var(--pm-gold) !important; font-size:.72rem; font-weight:600;
        letter-spacing:.08em; text-transform:uppercase; white-space:nowrap; border-bottom:1px solid rgba(227,192,108,.3);
    }
    .pm-page .table tbody td{ font-size:.88rem; padding-top:.9rem; padding-bottom:.9rem; white-space:nowrap; border-color:var(--pm-line); }
    .pm-strong{ color:var(--pm-text); font-weight:600; }
    .pm-muted{ color:var(--pm-muted); }
    .pm-avatar{ width:34px; height:34px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;
        background:rgba(232,160,187,.16); color:var(--pm-rose); border:1px solid rgba(232,160,187,.3); }
    .pm-unit{ display:inline-block; border:1px solid rgba(227,192,108,.35); color:var(--pm-gold); border-radius:.55rem; padding:.25rem .6rem; font-size:.74rem; font-weight:600; }
    .pm-money-bad{ color:#ff9aab; font-weight:700; }

    .pm-tag{ font-size:.72rem; font-weight:600; padding:.3rem .75rem; border-radius:999px; white-space:nowrap; border:1px solid transparent; }
    .pm-tag.ok{ background:rgba(227,192,108,.14); color:var(--pm-gold); border-color:rgba(227,192,108,.3); }
    .pm-tag.warn{ background:transparent; color:#f0d58a; border-color:rgba(227,192,108,.6); }
    .pm-tag.bad{ background:rgba(232,160,187,.18); color:#f6b9cf; border-color:rgba(232,160,187,.45); }

    .pm-page .pagination{ margin:0; }
    .pm-page .page-link{ background:var(--pm-card-2); border-color:var(--pm-line); color:var(--pm-muted); }
    .pm-page .page-item.active .page-link{ background:var(--pm-gold); border-color:var(--pm-gold); color:var(--pm-ink); font-weight:700; }

    .pm-empty-box{ text-align:center; padding:2.5rem 0; color:var(--pm-muted); }
    .pm-empty-box .pm-ring{ width:64px; height:64px; border-radius:50%; margin:0 auto .8rem; display:flex; align-items:center; justify-content:center;
        border:1px solid rgba(227,192,108,.4); color:var(--pm-gold); font-size:1.6rem; }
    .pm-empty-box h6{ color:var(--pm-text); font-weight:700; }

    /* Judul khusus cetak: disembunyikan di layar, tampil saat dicetak */
    .pm-print-head{ display:none; }

    /* ===== Tampilan saat dicetak: putih, hemat tinta, tanpa sidebar ===== */
    @page { size: A4 landscape; margin: 12mm; }

    @media print{
        body{ background:#ffffff !important; }

        /* sembunyikan sidebar, topbar, footer, tombol, notifikasi */
        .app-sidebar,
        .sidebar-backdrop,
        .app-main > header.navbar,
        footer,
        .no-print,
        .alert,
        .pm-head,
        .pm-avatar,
        .pm-panelx-foot{ display:none !important; }

        .app-wrapper{ display:block !important; }
        .app-main{ display:block !important; min-height:0 !important; }
        .app-main > main{ padding:0 !important; }

        .pm-page{
            --pm-card:#ffffff; --pm-card-2:#eeeeee; --pm-line:#999999;
            --pm-text:#000000; --pm-muted:#333333;
            --pm-gold:#000000; --pm-gold-d:#000000; --pm-rose:#000000; --pm-rose-d:#000000;
            padding:0 !important; max-width:100%; color:#000000;
        }

        .pm-print-head{ display:block; text-align:center; margin-bottom:14px; }
        .pm-print-head h3{ margin:0 0 4px; font-size:18pt; font-weight:700; color:#000000; }
        .pm-print-head p{ margin:0; font-size:10pt; color:#333333; }

        .pm-box, .pm-panelx{ box-shadow:none !important; border:1px solid #999999 !important; border-radius:6px !important; }
        .pm-panelx-head{ background:none !important; }
        .pm-page .table thead th{ background:#eeeeee !important; color:#000000 !important; border-bottom:1px solid #666666 !important; }
        .pm-page .table tbody td{ color:#000000 !important; padding-top:6px; padding-bottom:6px; }
        .pm-tag, .pm-unit, .pm-pill{ background:none !important; color:#000000 !important; border:1px solid #999999 !important; }
        .pm-money-bad{ color:#000000 !important; }
        .pm-page .table-responsive{ overflow:visible !important; }

        /* paksa warna/garis ikut tercetak */
        *{ -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        tr{ page-break-inside:avoid; }
    }
</style>

@if(! $pmBolehCetak)
<style>
    /* Bukan petugas: kalau tetap menekan Ctrl+P, hasil cetaknya kosong */
    @media print{ .app-wrapper{ display:none !important; } }
</style>
@endif

<div class="container-fluid py-4 px-4 pm-page">

    <!-- Judul khusus cetak (hanya muncul saat dicetak) -->
    <div class="pm-print-head">
        <h3>Laporan Pemantauan Peminjaman</h3>
        <p>Lab NET &amp; PC &mdash; Dicetak pada {{ date('d/m/Y H:i') }}</p>
    </div>

    <!-- Header Halaman -->
    <div class="pm-head mb-4">
        <span class="pm-chip"><i class="bi bi-search"></i> Pemantauan</span>
        <h3 class="pm-h1">Pemantauan Peminjaman</h3>
        <p class="pm-sub">Pantau alat yang sedang dipinjam, jatuh tempo, dan denda keterlambatan.</p>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert pm-alert alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div class="small fw-medium">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert pm-alert-err alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <div class="small fw-medium">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-4"><div class="pm-box"><div class="pm-lbl">Sedang dipinjam</div><div class="pm-num">{{ $pmTotal }}</div></div></div>
        <div class="col-6 col-lg-4"><div class="pm-box pink"><div class="pm-lbl">Terlambat</div><div class="pm-num">{{ $pmTelat }}</div></div></div>
        <div class="col-12 col-lg-4"><div class="pm-box gray"><div class="pm-lbl">Total denda</div><div class="pm-num" style="font-size:1.3rem;">Rp {{ number_format($pmDenda, 0, ',', '.') }}</div></div></div>
    </div>

    <!-- Card Tabel -->
    <div class="pm-panelx mb-4">

        <div class="pm-panelx-head">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-box-arrow-up-right fs-5"></i>
                <h6>Alat yang Sedang Dipinjam</h6>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="pm-pill">Total: {{ $pmTotal }} Data</span>
                @if($pmBolehCetak)
                <button type="button" class="pm-btn-print no-print" onclick="window.print()">
                    <i class="bi bi-printer-fill"></i> Cetak Laporan
                </button>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="py-3 px-4 text-center" style="width: 5%;">No</th>
                        <th class="py-3 px-3">Peminjam</th>
                        <th class="py-3 px-3">Nama Alat</th>
                        <th class="py-3 px-3">Jumlah</th>
                        <th class="py-3 px-3">Tgl Pinjam</th>
                        <th class="py-3 px-3">Jatuh Tempo</th>
                        <th class="py-3 px-3">Keterangan</th>
                        <th class="py-3 px-3">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($datap as $peminjaman)
                    @php
                        $due  = $pmInfo[$peminjaman->id_peminjaman][0];
                        $sisa = $pmInfo[$peminjaman->id_peminjaman][1];
                    @endphp
                    <tr>
                        <td class="px-4 text-center pm-muted fw-medium">{{ $no++ }}</td>

                        <td class="px-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="pm-avatar"><i class="bi bi-person-fill"></i></span>
                                <span class="pm-strong">
                                    {{ $namaUser[$peminjaman->id_user] ?? 'User ID: ' . $peminjaman->id_user }}
                                </span>
                            </div>
                        </td>

                        <td class="px-3">
                            <span class="pm-strong">
                                {{ $namaAlat[$peminjaman->id_alat] ?? 'Alat ID: ' . $peminjaman->id_alat }}
                            </span>
                        </td>

                        <td class="px-3">
                            <span class="pm-unit">{{ $peminjaman->jumlah }} Unit</span>
                        </td>

                        <td class="px-3">
                            <span class="pm-muted">
                                <i class="bi bi-calendar-event me-1"></i>{{ $peminjaman->tanggal_pinjam }}
                            </span>
                        </td>

                        <td class="px-3">
                            <span class="pm-muted">
                                <i class="bi bi-calendar-check me-1"></i>{{ $due ? date('Y-m-d', $due) : '-' }}
                            </span>
                        </td>

                        <td class="px-3">
                            @if($sisa === null)
                                <span class="pm-tag ok">Tanpa tenggat</span>
                            @elseif($sisa < 0)
                                <span class="pm-tag bad">Telat {{ abs($sisa) }} hari</span>
                            @elseif($sisa == 0)
                                <span class="pm-tag warn">Kembali hari ini</span>
                            @elseif($sisa <= 2)
                                <span class="pm-tag warn">Sisa {{ $sisa }} hari</span>
                            @else
                                <span class="pm-tag ok">Sisa {{ $sisa }} hari</span>
                            @endif
                        </td>

                        <td class="px-3">
                            @if(($peminjaman->denda ?? 0) > 0)
                                <span class="pm-money-bad">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</span>
                            @else
                                <span class="pm-muted">Rp 0</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="pm-empty-box">
                                <div class="pm-ring"><i class="bi bi-inbox"></i></div>
                                <h6 class="mb-1">Tidak Ada Alat yang Sedang Dipinjam</h6>
                                <span class="small">Peminjaman yang sudah disetujui akan muncul di sini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($datap, 'hasPages') && $datap->hasPages())
        <div class="pm-panelx-foot">
            {!! $datap->links() !!}
        </div>
        @endif
    </div>

</div>
@endsection