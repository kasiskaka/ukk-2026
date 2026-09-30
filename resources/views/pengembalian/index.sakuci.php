@extends('layouts.app')

@section('title', config('app.name') . ' -- Daftar Pengembalian Alat')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
    :root{
        --pm-pink:#fbe4ec; --pm-pink-3:#e58aab; --pm-gray:#eeeef0; --pm-gray-2:#9a9aa3;
        --pm-gray-3:#4a4a53; --pm-white:#ffffff; --pm-gold:#c9a45c; --pm-gold-2:#f3e6c4;
    }
    .pm-page > .d-flex:first-child{ background:linear-gradient(120deg,#fff 45%,var(--pm-pink) 100%); border:1px solid var(--pm-gold-2); border-left:6px solid var(--pm-gold); border-radius:1.2rem; padding:1.4rem 1.8rem; }
    .pm-page h3{ font-weight:800; color:var(--pm-gray-3) !important; }
    .pm-page .btn-primary{ background:var(--pm-gold); border-color:var(--pm-gold); }
    .pm-page .btn-primary:hover{ background:var(--pm-pink-3); border-color:var(--pm-pink-3); }
    .pm-page .card.bg-body-tertiary{ background:#fff !important; border:1px solid var(--pm-gray) !important; }
    .pm-page .card-header{ background:var(--pm-pink) !important; }
    .pm-page .table thead th{ background:var(--pm-gray) !important; font-size:.72rem; white-space:nowrap; }
    .pm-page .table tbody td{ font-size:.88rem; white-space:nowrap; }
    .pm-page .fs-7{ font-size:.88rem !important; }
    .pm-page .fs-8{ font-size:.72rem !important; }
    .pm-page .badge{ padding:.4rem .75rem; white-space:nowrap; }

    /* Ringkasan */
    .pm-box{ background:#fff; border:1px solid var(--pm-gray); border-left:5px solid var(--pm-gold); border-radius:1rem; padding:1rem 1.2rem; height:100%; }
    .pm-box .pm-num{ font-size:1.6rem; font-weight:800; line-height:1.1; color:var(--pm-gray-3); }
    .pm-box .pm-lbl{ font-size:.8rem; color:var(--pm-gray-2); }
    .pm-box.pink{ border-left-color:var(--pm-pink-3); }
    .pm-box.gray{ border-left-color:var(--pm-gray-2); }
    .pm-section{ background:#fff; border:1px solid var(--pm-gray); border-radius:1rem; padding:1.2rem; }
    .pm-section h6{ font-weight:800; color:var(--pm-gray-3); margin-bottom:1rem; }
    .pm-tag{ font-size:.75rem; font-weight:700; padding:.3rem .7rem; border-radius:999px; white-space:nowrap; }
    .pm-tag.ok{ background:var(--pm-gold-2); color:#8a6a25; }
    .pm-tag.warn{ background:#fff; color:#8a6a25; border:1px solid var(--pm-gold); }
    .pm-tag.bad{ background:var(--pm-pink); color:#b04a6f; }
</style>

<div class="container-fluid py-4 px-4 pm-page">
    
    <!-- Header Halaman & Tombol Tambah -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary px-2.5 py-1 rounded-pill fw-semibold small">
                    <i class="bi bi-box-seam me-1"></i> Transaksi pengembalian
                </span>
            </div>
            <h3 class="fw-bold mb-1 text-body">Daftar Pengembalian Alat</h3>
            <p class="text-body-secondary small mb-0">Kelola riwayat permohonan, status persetujuan, dan jadwal peminjaman alat sarpras.</p>
        </div>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary fw-semibold px-3 py-2 rounded-3 shadow-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Pengembalian Baru</span>
        </a>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div class="small fw-medium">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="pm-box"><div class="pm-num">{{ $pmBelum }}</div><div class="pm-lbl">Belum dikembalikan</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box gray"><div class="pm-num">{{ $pmSudah }}</div><div class="pm-lbl">Sudah dikembalikan</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box pink"><div class="pm-num">{{ $pmTelat }}</div><div class="pm-lbl">Terlambat</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box gray"><div class="pm-num" style="font-size:1.25rem;">Rp {{ number_format($pmDenda, 0, ',', '.') }}</div><div class="pm-lbl">Total denda</div></div></div>
    </div>

    <!-- Card Pembungkus Tabel -->
    <div class="card border-0 bg-body-tertiary shadow-sm rounded-4 overflow-hidden mb-4">
        
        <!-- Header Card -->
        <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-check text-primary fs-5"></i>
                <h6 class="fw-bold mb-0 text-body">Data Pengembalian Aktif</h6>
            </div>
            <span class="badge bg-body text-body-secondary border px-3 py-1.5 rounded-pill small fw-normal">
                Total: {{ count($datap) }} Data
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body border-bottom text-body-secondary text-uppercase fs-8 tracking-wider">
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
                    <tbody class="divide-y">
                        @php $no = 1; @endphp
                        @forelse ($datap as $peminjaman)
                        <tr>
                            <td class="px-4 text-center text-body-secondary fw-medium fs-7">{{ $no++ }}</td>
                            
                            <!-- Peminjam -->
                            <td class="px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <span class="fw-bold text-body fs-7">
                                        {{ $peminjaman->user->nama ?? 'User ID: ' . $peminjaman->id_user }}
                                    </span>
                                </div>
                            </td>

                            <!-- Nama Alat -->
                            <td class="px-3">
                                <span class="fw-semibold text-body fs-7">
                                    {{ $peminjaman->alat->nama_alat ?? 'Alat ID: ' . $peminjaman->id_alat }}
                                </span>
                            </td>

                            <!-- Jumlah Unit -->
                            <td class="px-3">
                                <span class="badge bg-body text-body border fw-semibold px-2.5 py-1.5 rounded-2 fs-8">
                                    {{ $peminjaman->jumlah }} Unit
                                </span>
                            </td>

                            <!-- Tgl Pinjam -->
                            <td class="px-3">
                                <span class="text-body-secondary fs-7">
                                    <i class="bi bi-calendar-event me-1"></i>{{ $peminjaman->tanggal_pinjam }}
                                </span>
                            </td>

                            <!-- Tgl Kembali -->
                            <td class="px-3">
                                <span class="text-body-secondary fs-7">
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
                                    <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25 fw-semibold px-2.5 py-1.5 rounded-pill fs-8">
                                        <i class="bi bi-hourglass-split me-1"></i>Pending
                                    </span>
                                @elseif($peminjaman->status == 'Disetujui')
                                    <span class="badge bg-info-subtle text-info border border-info border-opacity-25 fw-semibold px-2.5 py-1.5 rounded-pill fs-8">
                                        <i class="bi bi-check2-circle me-1"></i>Disetujui
                                    </span>
                                @elseif($peminjaman->status == 'Ditolak')
                                    <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 fw-semibold px-2.5 py-1.5 rounded-pill fs-8">
                                        <i class="bi bi-x-circle me-1"></i>Ditolak
                                    </span>
                                @elseif($peminjaman->status == 'Dipinjam')
                                    <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 fw-semibold px-2.5 py-1.5 rounded-pill fs-8">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Dipinjam
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 fw-semibold px-2.5 py-1.5 rounded-pill fs-8">
                                        <i class="bi bi-check-circle-fill me-1"></i>Dikembalikan
                                    </span>
                                @endif
                            </td>

                            <!-- Denda -->
                            <td class="px-3">
                                @if(($peminjaman->denda ?? 0) > 0)
                                    <span class="text-danger fw-bold fs-7">Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-body-secondary fs-7">Rp 0</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-4 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center gap-1.5">
                                    <a href="{{ route('peminjaman.edit', ['id_peminjaman' => $peminjaman->id_peminjaman]) }}" class="btn btn-sm btn-outline-warning fw-medium px-2.5 py-1 rounded-2 d-inline-flex align-items-center gap-1 fs-7" title="Edit Peminjaman">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    
                                    <form action="{{ route('peminjaman.delete', ['id_peminjaman' => $peminjaman->id_peminjaman]) }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-medium px-2.5 py-1 rounded-2 d-inline-flex align-items-center gap-1 fs-7" onclick="return confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?')" title="Hapus Data">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-body-secondary">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <div class="bg-body rounded-circle p-3 mb-1">
                                        <i class="bi bi-inbox text-body-secondary fs-2 opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-body">Belum Ada Data Peminjaman</h6>
                                    <span class="fs-7 text-body-secondary">Data peminjaman alat yang diajukan akan muncul di sini.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Bagian Pagination -->
        @if(method_exists($datap, 'hasPages') && $datap->hasPages())
        <div class="card-footer bg-transparent py-3 border-top d-flex justify-content-end">
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
                    <tr><th>Peminjam</th><th>Nama Alat</th><th>Jumlah</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Keterangan</th></tr>
                </thead>
                <tbody>
                    @forelse ($pmDaftar as $row)
                    <tr>
                        <td>{{ $row[0]->user->nama ?? 'User ID: ' . $row[0]->id_user }}</td>
                        <td>{{ $row[0]->alat->nama_alat ?? 'Alat ID: ' . $row[0]->id_alat }}</td>
                        <td>{{ $row[0]->jumlah }} Unit</td>
                        <td>{{ $row[0]->tanggal_pinjam }}</td>
                        <td>{{ $row[0]->tanggal_kembali ? $row[0]->tanggal_kembali : '-' }}</td>
                        <td>
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
                    <tr><td colspan="6" class="text-center text-body-secondary py-3">Semua alat sudah dikembalikan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection