@extends('layouts.app')

@section('title', config('app.name') . ' -- Daftar Pengembalian Alat')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<?php
    // Hitung ringkasan (PHP sederhana, hanya membaca $datap)
    $pmToday = strtotime(date('Y-m-d'));
    $pmBelum = 0; $pmSudah = 0; $pmTelat = 0; $pmDenda = 0;
    $pmDaftar = array();
    foreach ($datap as $pmx) {
        $pmDenda += (int) $pmx->denda;
        if ($pmx->status == 'Dipinjam') {
            $pmBelum++;
            $pmDue = $pmx->tanggal_kembali ? strtotime($pmx->tanggal_kembali) : false;
            $pmSisa = $pmDue ? (int) round(($pmDue - $pmToday) / 86400) : null;
            if ($pmSisa !== null && $pmSisa < 0) { $pmTelat++; }
            $pmDaftar[] = array($pmx, $pmSisa);
        }
        if ($pmx->status == 'Dikembalikan') { $pmSudah++; }
    }
?>
<style>
    /* =========================================================
       TEMA SERASI DASHBOARD: gelap plum + aksen emas & rose
       ========================================================= */
    .pm-page{
        --pm-card:#2a2428; --pm-card-2:#352c32; --pm-line:rgba(255,255,255,.08);
        --pm-text:#f4eef1; --pm-muted:#a99ea4;
        --pm-gold:#e3c06c; --pm-gold-d:#c9a45c; --pm-rose:#e8a0bb; --pm-rose-d:#d97ea1;
        --pm-ink:#2a1f24;
        max-width:1500px; margin:0 auto; color:var(--pm-text);
    }

    /* Header halaman */
    .pm-head{
        background:linear-gradient(120deg,#46323b 0%,#2d2429 65%);
        border:1px solid var(--pm-line); border-left:4px solid var(--pm-gold);
        border-radius:1rem; padding:1.5rem 1.8rem;
        box-shadow:0 10px 30px rgba(0,0,0,.25);
    }
    .pm-chip{
        display:inline-flex; align-items:center; gap:.4rem;
        border:1px solid rgba(227,192,108,.55); color:var(--pm-gold);
        border-radius:999px; padding:.3rem .85rem; font-size:.74rem; font-weight:600; letter-spacing:.06em;
    }
    .pm-h1{ font-family:'Playfair Display',Georgia,serif; font-weight:700; font-size:1.9rem; color:var(--pm-text); margin:.5rem 0 .35rem; letter-spacing:.01em; }
    .pm-sub{ color:var(--pm-muted); font-size:.9rem; margin:0; }

    .pm-page .btn-pm{
        background:linear-gradient(135deg,var(--pm-gold),var(--pm-gold-d)); border:0; color:var(--pm-ink);
        font-weight:700; border-radius:.75rem; padding:.6rem 1.1rem; transition:filter .2s, background .2s;
    }
    .pm-page .btn-pm:hover{ background:linear-gradient(135deg,var(--pm-rose),var(--pm-rose-d)); color:var(--pm-ink); }

    /* Alert */
    .pm-alert{ background:rgba(80,180,130,.12); border:1px solid rgba(80,180,130,.35); color:#bfe8d3; border-radius:.9rem; padding:.8rem 1rem; }

    /* Ringkasan */
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

    /* Kartu tabel */
    .pm-panelx{ background:var(--pm-card); border:1px solid var(--pm-line); border-radius:1rem; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.22); }
    .pm-panelx-head{
        display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.5rem;
        border-bottom:1px solid var(--pm-line); background:linear-gradient(90deg,rgba(232,160,187,.14),transparent 70%);
    }
    .pm-panelx-head h6{ margin:0; font-weight:700; color:var(--pm-text); }
    .pm-panelx-head .bi{ color:var(--pm-gold); }
    .pm-pill{ border:1px solid rgba(227,192,108,.35); color:var(--pm-gold); border-radius:999px; padding:.3rem .85rem; font-size:.78rem; }
    .pm-panelx-foot{ padding:.9rem 1.5rem; border-top:1px solid var(--pm-line); display:flex; justify-content:flex-end; }

    /* Tabel */
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

    /* Status */
    .pm-st{ display:inline-flex; align-items:center; gap:.35rem; font-size:.72rem; font-weight:600; padding:.35rem .8rem; border-radius:999px; border:1px solid; white-space:nowrap; }
    .pm-st.pending{ color:#f0d58a; background:rgba(227,192,108,.12); border-color:rgba(227,192,108,.35); }
    .pm-st.setuju{ color:#f2b6cb; background:rgba(232,160,187,.12); border-color:rgba(232,160,187,.35); }
    .pm-st.tolak{ color:#ff9aab; background:rgba(220,80,100,.12); border-color:rgba(220,80,100,.35); }
    .pm-st.pinjam{ color:#dccbf2; background:rgba(180,150,220,.12); border-color:rgba(180,150,220,.35); }
    .pm-st.kembali{ color:#a6dcc3; background:rgba(80,180,130,.12); border-color:rgba(80,180,130,.35); }

    /* Tombol aksi */
    .pm-page .btn-aksi-edit{ color:var(--pm-gold); border:1px solid rgba(227,192,108,.5); background:transparent; border-radius:.6rem; font-size:.8rem; padding:.35rem .75rem; }
    .pm-page .btn-aksi-edit:hover{ background:var(--pm-gold); color:var(--pm-ink); }
    .pm-page .btn-aksi-del{ color:var(--pm-rose); border:1px solid rgba(232,160,187,.5); background:transparent; border-radius:.6rem; font-size:.8rem; padding:.35rem .75rem; }
    .pm-page .btn-aksi-del:hover{ background:var(--pm-rose); color:var(--pm-ink); }

    /* Pagination */
    .pm-page .pagination{ margin:0; }
    .pm-page .page-link{ background:var(--pm-card-2); border-color:var(--pm-line); color:var(--pm-muted); }
    .pm-page .page-link:hover{ background:var(--pm-card-2); color:var(--pm-rose); }
    .pm-page .page-item.active .page-link{ background:var(--pm-gold); border-color:var(--pm-gold); color:var(--pm-ink); font-weight:700; }
    .pm-page .page-item.disabled .page-link{ background:var(--pm-card); color:#6f656b; }

    /* Kosong */
    .pm-empty-box{ text-align:center; padding:2.5rem 0; color:var(--pm-muted); }
    .pm-empty-box .pm-ring{ width:64px; height:64px; border-radius:50%; margin:0 auto .8rem; display:flex; align-items:center; justify-content:center;
        border:1px solid rgba(227,192,108,.4); color:var(--pm-gold); font-size:1.6rem; }
    .pm-empty-box h6{ color:var(--pm-text); font-weight:700; }

    /* Catatan jatuh tempo */
    .pm-section{ background:var(--pm-card); border:1px solid var(--pm-line); border-radius:1rem; padding:1.3rem 1.5rem; box-shadow:0 10px 30px rgba(0,0,0,.22); }
    .pm-section h6{ display:flex; align-items:center; gap:.6rem; font-weight:700; color:var(--pm-text); margin-bottom:1rem; }
    .pm-section h6::before{ content:""; width:4px; height:22px; border-radius:3px; background:var(--pm-gold); }
    .pm-tag{ font-size:.72rem; font-weight:600; padding:.3rem .75rem; border-radius:999px; white-space:nowrap; border:1px solid transparent; }
    .pm-tag.ok{ background:rgba(227,192,108,.14); color:var(--pm-gold); border-color:rgba(227,192,108,.3); }
    .pm-tag.warn{ background:transparent; color:#f0d58a; border-color:rgba(227,192,108,.6); }
    .pm-tag.bad{ background:rgba(232,160,187,.18); color:#f6b9cf; border-color:rgba(232,160,187,.45); }
</style>

<div class="container-fluid py-4 px-4 pm-page">

    <!-- Header Halaman & Tombol Tambah -->
    <div class="pm-head d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <span class="pm-chip"><i class="bi bi-box-seam"></i> Transaksi Pengembalian</span>
            <h3 class="pm-h1">Daftar Pengembalian Alat</h3>
            <p class="pm-sub">Kelola riwayat permohonan, status persetujuan, dan jadwal peminjaman alat sarpras.</p>
        </div>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-pm d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Pengembalian Baru</span>
        </a>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert pm-alert alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div class="small fw-medium">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="pm-box"><div class="pm-lbl">Belum dikembalikan</div><div class="pm-num">{{ $pmBelum }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box gray"><div class="pm-lbl">Sudah dikembalikan</div><div class="pm-num">{{ $pmSudah }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box pink"><div class="pm-lbl">Terlambat</div><div class="pm-num">{{ $pmTelat }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box gray"><div class="pm-lbl">Total denda</div><div class="pm-num" style="font-size:1.3rem;">Rp {{ number_format($pmDenda, 0, ',', '.') }}</div></div></div>
    </div>

    <!-- Card Pembungkus Tabel -->
    <div class="pm-panelx mb-4">

        <!-- Header Card -->
        <div class="pm-panelx-head">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-check fs-5"></i>
                <h6>Data Pengembalian Aktif</h6>
            </div>
            <span class="pm-pill">Total: {{ count($datap) }} Data</span>
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
                        <th class="py-3 px-3">Tgl Kembali</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Denda</th>
                        <th class="py-3 px-4 text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($datap as $peminjaman)
                    <tr>
                        <td class="px-4 text-center pm-muted fw-medium">{{ $no++ }}</td>

                        <!-- Peminjam -->
                        <td class="px-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="pm-avatar"><i class="bi bi-person-fill"></i></span>
                                <span class="pm-strong">
                                    {{ $peminjaman->user->nama ?? 'User ID: ' . $peminjaman->id_user }}
                                </span>
                            </div>
                        </td>

                        <!-- Nama Alat -->
                        <td class="px-3">
                            <span class="pm-strong">
                                {{ $peminjaman->alat->nama_alat ?? 'Alat ID: ' . $peminjaman->id_alat }}
                            </span>
                        </td>

                        <!-- Jumlah Unit -->
                        <td class="px-3">
                            <span class="pm-unit">{{ $peminjaman->jumlah }} Unit</span>
                        </td>

                        <!-- Tgl Pinjam -->
                        <td class="px-3">
                            <span class="pm-muted">
                                <i class="bi bi-calendar-event me-1"></i>{{ $peminjaman->tanggal_pinjam }}
                            </span>
                        </td>

                        <!-- Tgl Kembali -->
                        <td class="px-3">
                            <span class="pm-muted">
                                @if($peminjaman->tanggal_kembali)
                                    <i class="bi bi-calendar-check me-1"></i>{{ $peminjaman->tanggal_kembali }}
                                @else
                                    -
                                @endif
                            </span>
                        </td>

                        <!-- Status Peminjaman -->
                        <td class="px-3">
                            @if($peminjaman->status == 'Pending')
                                <span class="pm-st pending"><i class="bi bi-hourglass-split"></i>Pending</span>
                            @elseif($peminjaman->status == 'Disetujui')
                                <span class="pm-st setuju"><i class="bi bi-check2-circle"></i>Disetujui</span>
                            @elseif($peminjaman->status == 'Ditolak')
                                <span class="pm-st tolak"><i class="bi bi-x-circle"></i>Ditolak</span>
                            @elseif($peminjaman->status == 'Dipinjam')
                                <span class="pm-st pinjam"><i class="bi bi-box-arrow-up-right"></i>Dipinjam</span>
                            @else
                                <span class="pm-st kembali"><i class="bi bi-check-circle-fill"></i>Dikembalikan</span>
                            @endif
                        </td>

                        <!-- Denda -->
                        <td class="px-3">
                            @if(($peminjaman->denda ?? 0) > 0)
                                <span class="pm-money-bad">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</span>
                            @else
                                <span class="pm-muted">Rp 0</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center gap-2">
                                <a href="{{ route('peminjaman.edit', ['id_peminjaman' => $peminjaman->id_peminjaman]) }}" class="btn btn-sm btn-aksi-edit d-inline-flex align-items-center gap-1" title="Edit Peminjaman">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="{{ route('peminjaman.delete', ['id_peminjaman' => $peminjaman->id_peminjaman]) }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-aksi-del d-inline-flex align-items-center gap-1" onclick="return confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?')" title="Hapus Data">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="pm-empty-box">
                                <div class="pm-ring"><i class="bi bi-inbox"></i></div>
                                <h6 class="mb-1">Belum Ada Data Peminjaman</h6>
                                <span class="small">Data peminjaman alat yang diajukan akan muncul di sini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bagian Pagination -->
        @if(method_exists($datap, 'hasPages') && $datap->hasPages())
        <div class="pm-panelx-foot">
            {!! $datap->links() !!}
        </div>
        @endif
    </div>

    <!-- Catatan jatuh tempo -->
    <div class="pm-section mb-4">
        <h6>Catatan Jatuh Tempo (Belum Dikembalikan)</h6>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr><th class="py-2 px-3">Peminjam</th><th class="py-2 px-3">Nama Alat</th><th class="py-2 px-3">Jumlah</th><th class="py-2 px-3">Tgl Pinjam</th><th class="py-2 px-3">Jatuh Tempo</th><th class="py-2 px-3">Keterangan</th></tr>
                </thead>
                <tbody>
                    @forelse ($pmDaftar as $row)
                    <tr>
                        <td class="px-3">{{ $row[0]->user->nama ?? 'User ID: ' . $row[0]->id_user }}</td>
                        <td class="px-3">{{ $row[0]->alat->nama_alat ?? 'Alat ID: ' . $row[0]->id_alat }}</td>
                        <td class="px-3">{{ $row[0]->jumlah }} Unit</td>
                        <td class="px-3 pm-muted">{{ $row[0]->tanggal_pinjam }}</td>
                        <td class="px-3 pm-muted">{{ $row[0]->tanggal_kembali ? $row[0]->tanggal_kembali : '-' }}</td>
                        <td class="px-3">
                            @if($row[1] === null)
                                <span class="pm-tag ok">Tanpa tenggat</span>
                            @elseif($row[1] < 0)
                                <span class="pm-tag bad">Telat {{ abs($row[1]) }} hari</span>
                            @elseif($row[1] == 0)
                                <span class="pm-tag warn">Kembali hari ini</span>
                            @elseif($row[1] <= 2)
                                <span class="pm-tag warn">Sisa {{ $row[1] }} hari</span>
                            @else
                                <span class="pm-tag ok">Sisa {{ $row[1] }} hari</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center pm-muted py-4">Semua alat sudah dikembalikan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection