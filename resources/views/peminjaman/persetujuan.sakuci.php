@extends('layouts.app')

@section('title', config('app.name') . ' -- Persetujuan Peminjaman')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

    .pm-panelx{ background:var(--pm-card); border:1px solid var(--pm-line); border-radius:1rem; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.22); }
    .pm-panelx-head{
        display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.5rem;
        border-bottom:1px solid var(--pm-line); background:linear-gradient(90deg,rgba(232,160,187,.14),transparent 70%);
    }
    .pm-panelx-head h6{ margin:0; font-weight:700; color:var(--pm-text); }
    .pm-panelx-head .bi{ color:var(--pm-gold); }
    .pm-pill{ border:1px solid rgba(227,192,108,.35); color:var(--pm-gold); border-radius:999px; padding:.3rem .85rem; font-size:.78rem; }
    .pm-panelx-foot{ padding:.9rem 1.5rem; border-top:1px solid var(--pm-line); display:flex; justify-content:flex-end; }

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

    .pm-st{ display:inline-flex; align-items:center; gap:.35rem; font-size:.72rem; font-weight:600; padding:.35rem .8rem; border-radius:999px; border:1px solid;
        color:#f0d58a; background:rgba(227,192,108,.12); border-color:rgba(227,192,108,.35); }

    .pm-page .btn-aksi-ok{ color:#a6dcc3; border:1px solid rgba(80,180,130,.55); background:transparent; border-radius:.6rem; font-size:.8rem; padding:.35rem .75rem; }
    .pm-page .btn-aksi-ok:hover{ background:#50b482; color:var(--pm-ink); }
    .pm-page .btn-aksi-no{ color:var(--pm-rose); border:1px solid rgba(232,160,187,.5); background:transparent; border-radius:.6rem; font-size:.8rem; padding:.35rem .75rem; }
    .pm-page .btn-aksi-no:hover{ background:var(--pm-rose); color:var(--pm-ink); }

    .pm-page .pagination{ margin:0; }
    .pm-page .page-link{ background:var(--pm-card-2); border-color:var(--pm-line); color:var(--pm-muted); }
    .pm-page .page-item.active .page-link{ background:var(--pm-gold); border-color:var(--pm-gold); color:var(--pm-ink); font-weight:700; }

    .pm-empty-box{ text-align:center; padding:2.5rem 0; color:var(--pm-muted); }
    .pm-empty-box .pm-ring{ width:64px; height:64px; border-radius:50%; margin:0 auto .8rem; display:flex; align-items:center; justify-content:center;
        border:1px solid rgba(227,192,108,.4); color:var(--pm-gold); font-size:1.6rem; }
    .pm-empty-box h6{ color:var(--pm-text); font-weight:700; }
</style>

<div class="container-fluid py-4 px-4 pm-page">

    <!-- Header Halaman -->
    <div class="pm-head mb-4">
        <span class="pm-chip"><i class="bi bi-check2-square"></i> Persetujuan</span>
        <h3 class="pm-h1">Persetujuan Peminjaman</h3>
        <p class="pm-sub">Setujui atau tolak pengajuan peminjaman alat yang masuk.</p>
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

    <!-- Card Tabel -->
    <div class="pm-panelx mb-4">

        <div class="pm-panelx-head">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-hourglass-split fs-5"></i>
                <h6>Pengajuan Menunggu Persetujuan</h6>
            </div>
            <span class="pm-pill">Total: {{ count($datap) }} Pengajuan</span>
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
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-4 text-center" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($datap as $peminjaman)
                    <tr>
                        <td class="px-4 text-center pm-muted fw-medium">{{ $no++ }}</td>

                        <td class="px-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="pm-avatar"><i class="bi bi-person-fill"></i></span>
                                <span class="pm-strong">
                                    {{ $peminjaman->user->nama ?? $peminjaman->user->username ?? 'User ID: ' . $peminjaman->id_user }}
                                </span>
                            </div>
                        </td>

                        <td class="px-3">
                            <span class="pm-strong">
                                {{ $peminjaman->alat->nama_alat ?? 'Alat ID: ' . $peminjaman->id_alat }}
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
                            <span class="pm-st"><i class="bi bi-hourglass-split"></i>Pending</span>
                        </td>

                        <td class="px-4 text-center">
                            <div class="d-inline-flex align-items-center justify-content-center gap-2">
                                <form action="/admin/peminjaman/{{ $peminjaman->id_peminjaman }}/setujui" method="POST" class="d-inline m-0"
                                      onsubmit="return confirm('Setujui peminjaman ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-aksi-ok d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-check2-circle"></i> Setujui
                                    </button>
                                </form>

                                <form action="/admin/peminjaman/{{ $peminjaman->id_peminjaman }}/tolak" method="POST" class="d-inline m-0"
                                      onsubmit="return confirm('Tolak peminjaman ini?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-aksi-no d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="pm-empty-box">
                                <div class="pm-ring"><i class="bi bi-inbox"></i></div>
                                <h6 class="mb-1">Tidak Ada Pengajuan</h6>
                                <span class="small">Pengajuan peminjaman yang masuk akan muncul di sini.</span>
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