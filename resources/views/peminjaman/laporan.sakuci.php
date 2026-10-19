@extends('layouts.app')

@section('title', config('app.name') . ' -- Laporan Peminjaman')

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

    .pm-page .btn-pm{
        background:linear-gradient(135deg,var(--pm-gold),var(--pm-gold-d)); border:0; color:var(--pm-ink);
        font-weight:700; border-radius:.75rem; padding:.6rem 1.1rem;
    }
    .pm-page .btn-pm:hover{ background:linear-gradient(135deg,var(--pm-rose),var(--pm-rose-d)); color:var(--pm-ink); }
    .pm-page .btn-pm-line{
        background:transparent; border:1px solid rgba(227,192,108,.5); color:var(--pm-gold);
        font-weight:600; border-radius:.75rem; padding:.6rem 1.1rem;
    }
    .pm-page .btn-pm-line:hover{ background:var(--pm-gold); color:var(--pm-ink); }

    /* Filter */
    .pm-filter{ background:var(--pm-card); border:1px solid var(--pm-line); border-radius:1rem; padding:1.1rem 1.3rem; box-shadow:0 8px 24px rgba(0,0,0,.2); }
    .pm-filter label{ font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; color:var(--pm-muted); margin-bottom:.3rem; }
    .pm-filter .form-control, .pm-filter .form-select{
        background:var(--pm-card-2); border:1px solid var(--pm-line); color:var(--pm-text); border-radius:.65rem;
    }
    .pm-filter .form-control:focus, .pm-filter .form-select:focus{ border-color:var(--pm-gold); box-shadow:0 0 0 .2rem rgba(227,192,108,.2); }
    .pm-filter .form-control::-webkit-calendar-picker-indicator{ filter:invert(.8); }

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

    /* Tabel */
    .pm-panelx{ background:var(--pm-card); border:1px solid var(--pm-line); border-radius:1rem; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.22); }
    .pm-panelx-head{
        display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.5rem;
        border-bottom:1px solid var(--pm-line); background:linear-gradient(90deg,rgba(232,160,187,.14),transparent 70%);
    }
    .pm-panelx-head h6{ margin:0; font-weight:700; color:var(--pm-text); }
    .pm-panelx-head .bi{ color:var(--pm-gold); }
    .pm-pill{ border:1px solid rgba(227,192,108,.35); color:var(--pm-gold); border-radius:999px; padding:.3rem .85rem; font-size:.78rem; }

    .pm-page .table{
        --bs-table-bg:transparent; --bs-table-color:var(--pm-text); --bs-table-striped-bg:transparent;
        --bs-table-hover-bg:rgba(232,160,187,.07); --bs-table-hover-color:var(--pm-text);
        --bs-table-border-color:var(--pm-line); color:var(--pm-text); margin:0;
    }
    .pm-page .table thead th{
        background:var(--pm-card-2) !important; color:var(--pm-gold) !important; font-size:.72rem; font-weight:600;
        letter-spacing:.08em; text-transform:uppercase; white-space:nowrap; border-bottom:1px solid rgba(227,192,108,.3);
    }
    .pm-page .table tbody td{ font-size:.88rem; padding-top:.8rem; padding-bottom:.8rem; white-space:nowrap; border-color:var(--pm-line); }
    .pm-page .table tfoot td{ font-weight:700; border-color:var(--pm-line); background:var(--pm-card-2); }
    .pm-strong{ color:var(--pm-text); font-weight:600; }
    .pm-muted{ color:var(--pm-muted); }
    .pm-money-bad{ color:#ff9aab; font-weight:700; }

    .pm-st{ display:inline-flex; align-items:center; font-size:.72rem; font-weight:600; padding:.3rem .75rem; border-radius:999px; border:1px solid; white-space:nowrap; }
    .pm-st.pending{ color:#f0d58a; background:rgba(227,192,108,.12); border-color:rgba(227,192,108,.35); }
    .pm-st.setuju{ color:#f2b6cb; background:rgba(232,160,187,.12); border-color:rgba(232,160,187,.35); }
    .pm-st.tolak{ color:#ff9aab; background:rgba(220,80,100,.12); border-color:rgba(220,80,100,.35); }
    .pm-st.kembali{ color:#a6dcc3; background:rgba(80,180,130,.12); border-color:rgba(80,180,130,.35); }

    .pm-empty-box{ text-align:center; padding:2.5rem 0; color:var(--pm-muted); }
    .pm-empty-box .pm-ring{ width:64px; height:64px; border-radius:50%; margin:0 auto .8rem; display:flex; align-items:center; justify-content:center;
        border:1px solid rgba(227,192,108,.4); color:var(--pm-gold); font-size:1.6rem; }
    .pm-empty-box h6{ color:var(--pm-text); font-weight:700; }

    /* Elemen yang hanya tampil saat dicetak */
    .print-only{ display:none; }

    /* ============ MODE CETAK ============ */
    @media print{
        @page{ size:A4 landscape; margin:14mm; }

        .app-sidebar, .sidebar-backdrop, .app-main > header.navbar, .app-main > footer, footer, .no-print{ display:none !important; }
        body .app-wrapper{ display:block !important; }
        .app-main{ display:block !important; min-height:0 !important; }
        .app-main > main, .container-fluid{ padding:0 !important; }
        html, body{ background:#fff !important; color:#000 !important; }

        .print-only{ display:block !important; }
        .print-title{ text-align:center; margin-bottom:12px; }
        .print-title h2{ margin:0; font-size:18pt; font-weight:700; color:#000; }
        .print-title p{ margin:2px 0 0; font-size:10pt; color:#000; }
        .print-title hr{ border:0; border-top:2px solid #000; margin:8px 0 0; }

        .pm-page{ color:#000 !important; max-width:none; padding:0 !important; }
        .pm-panelx{ background:#fff !important; border:0 !important; box-shadow:none !important; border-radius:0 !important; overflow:visible !important; }
        .pm-page .table{ --bs-table-color:#000; --bs-table-border-color:#777; color:#000 !important; width:100%; }
        .pm-page .table thead th{ background:#e9e9e9 !important; color:#000 !important; border:1px solid #777 !important; font-size:8.5pt; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        .pm-page .table tbody td{ color:#000 !important; border:1px solid #777 !important; font-size:9pt; padding:5px 7px !important; white-space:normal; }
        .pm-page .table tfoot td{ background:#f3f3f3 !important; color:#000 !important; border:1px solid #777 !important; font-size:9pt; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        .pm-page .table tr{ page-break-inside:avoid; }
        .pm-page .table thead{ display:table-header-group; }
        .pm-strong, .pm-muted, .pm-money-bad{ color:#000 !important; }
        .pm-st{ background:transparent !important; border:0 !important; color:#000 !important; padding:0 !important; }

        .print-ringkas{ display:flex !important; gap:24px; margin:10px 0; font-size:10pt; color:#000; }
        .print-ttd{ display:flex !important; justify-content:flex-end; margin-top:28px; font-size:10pt; color:#000; page-break-inside:avoid; }
        .print-ttd div{ text-align:center; min-width:220px; }
        .print-ttd .garis{ margin-top:60px; border-top:1px solid #000; padding-top:3px; }
    }
</style>

<div class="container-fluid py-4 px-4 pm-page">

    <!-- Header Halaman -->
    <div class="pm-head no-print d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <span class="pm-chip"><i class="bi bi-printer"></i> Laporan</span>
            <h3 class="pm-h1">Laporan Peminjaman</h3>
            <p class="pm-sub">Rekap peminjaman, pengembalian, dan denda. Atur filter lalu cetak.</p>
        </div>
        <button type="button" class="btn btn-pm d-inline-flex align-items-center gap-2" onclick="window.print()">
            <i class="bi bi-printer-fill"></i>
            <span>Cetak Laporan</span>
        </button>
    </div>

    <!-- Filter -->
    <form method="GET" action="/admin/laporan" class="pm-filter no-print mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-3">
                <label for="dari">Dari tanggal pinjam</label>
                <input type="date" id="dari" name="dari" value="{{ $dari }}" class="form-control">
            </div>
            <div class="col-12 col-md-3">
                <label for="sampai">Sampai tanggal pinjam</label>
                <input type="date" id="sampai" name="sampai" value="{{ $sampai }}" class="form-control">
            </div>
            <div class="col-12 col-md-3">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="" {{ $status === '' ? 'selected' : '' }}>Semua status</option>
                    <option value="Pending" {{ $status === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Disetujui" {{ $status === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ $status === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Dikembalikan" {{ $status === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-pm flex-grow-1">Terapkan</button>
                <a href="/admin/laporan" class="btn btn-pm-line">Reset</a>
            </div>
        </div>
    </form>

    <!-- Ringkasan (layar) -->
    <div class="row g-3 mb-4 no-print">
        <div class="col-6 col-lg-3"><div class="pm-box"><div class="pm-lbl">Total peminjaman</div><div class="pm-num">{{ $ringkas['total'] }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box gray"><div class="pm-lbl">Sedang dipinjam</div><div class="pm-num">{{ $ringkas['aktif'] }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box pink"><div class="pm-lbl">Terkena denda</div><div class="pm-num">{{ $ringkas['telat'] }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="pm-box gray"><div class="pm-lbl">Total denda</div><div class="pm-num" style="font-size:1.3rem;">Rp {{ number_format($ringkas['denda'], 0, ',', '.') }}</div></div></div>
    </div>

    <!-- Judul laporan (hanya saat dicetak) -->
    <div class="print-only print-title">
        <h2>LAPORAN PEMINJAMAN ALAT</h2>
        <p>Lab NET &amp; PC</p>
        <p>
            Periode:
            @if($dari !== '' || $sampai !== '')
                {{ $dari !== '' ? date('d-m-Y', strtotime($dari)) : 'awal' }} s/d {{ $sampai !== '' ? date('d-m-Y', strtotime($sampai)) : 'sekarang' }}
            @else
                Semua tanggal
            @endif
            &nbsp;|&nbsp; Status: {{ $status !== '' ? $status : 'Semua' }}
        </p>
        <hr>
    </div>

    <div class="print-only print-ringkas">
        <span>Total: <b>{{ $ringkas['total'] }}</b></span>
        <span>Sedang dipinjam: <b>{{ $ringkas['aktif'] }}</b></span>
        <span>Dikembalikan: <b>{{ $ringkas['kembali'] }}</b></span>
        <span>Terkena denda: <b>{{ $ringkas['telat'] }}</b></span>
        <span>Total denda: <b>Rp {{ number_format($ringkas['denda'], 0, ',', '.') }}</b></span>
    </div>

    <!-- Tabel Laporan -->
    <div class="pm-panelx mb-4">

        <div class="pm-panelx-head no-print">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-journal-text fs-5"></i>
                <h6>Data Laporan</h6>
            </div>
            <span class="pm-pill">{{ $ringkas['total'] }} Data</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="py-3 px-3 text-center" style="width: 5%;">No</th>
                        <th class="py-3 px-3">Peminjam</th>
                        <th class="py-3 px-3">Nama Alat</th>
                        <th class="py-3 px-3 text-center">Jumlah</th>
                        <th class="py-3 px-3">Tgl Pinjam</th>
                        <th class="py-3 px-3">Tgl Kembali</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-end">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse ($baris as $row)
                    <tr>
                        <td class="px-3 text-center pm-muted">{{ $no++ }}</td>
                        <td class="px-3"><span class="pm-strong">{{ $row['peminjam'] }}</span></td>
                        <td class="px-3"><span class="pm-strong">{{ $row['alat'] }}</span></td>
                        <td class="px-3 text-center">{{ $row['jumlah'] }}</td>
                        <td class="px-3 pm-muted">{{ $row['tgl_pinjam'] }}</td>
                        <td class="px-3 pm-muted">{{ $row['tgl_kembali'] }}</td>
                        <td class="px-3">
                            @if($row['status'] == 'Pending')
                                <span class="pm-st pending">Pending</span>
                            @elseif($row['status'] == 'Disetujui')
                                <span class="pm-st setuju">Disetujui</span>
                            @elseif($row['status'] == 'Ditolak')
                                <span class="pm-st tolak">Ditolak</span>
                            @else
                                <span class="pm-st kembali">{{ $row['status'] }}</span>
                            @endif
                        </td>
                        <td class="px-3 text-end">
                            @if($row['denda'] > 0)
                                <span class="pm-money-bad">Rp {{ number_format($row['denda'], 0, ',', '.') }}</span>
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
                                <h6 class="mb-1">Tidak Ada Data</h6>
                                <span class="small">Ubah filter untuk menampilkan data peminjaman.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($ringkas['total'] > 0)
                <tfoot>
                    <tr>
                        <td colspan="7" class="px-3 text-end">Total Denda</td>
                        <td class="px-3 text-end">Rp {{ number_format($ringkas['denda'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Tanda tangan (hanya saat dicetak) -->
    <div class="print-only print-ttd">
        <div>
            <div>{{ date('d-m-Y H:i') }}</div>
            <div>Dicetak oleh,</div>
            <div class="garis">{{ $pencetak }}</div>
        </div>
    </div>

</div>
@endsection