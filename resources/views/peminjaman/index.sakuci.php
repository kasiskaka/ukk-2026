@extends('layouts.app')

@section('title', config('app.name') . ' -- Daftar Peminjaman Alat')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

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
</style>

<div class="container-fluid py-4 px-4 pm-page">

    <!-- Header Halaman & Tombol Tambah -->
    <div class="pm-head d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <span class="pm-chip"><i class="bi bi-box-seam"></i> Transaksi Peminjaman</span>
            <h3 class="pm-h1">Daftar Peminjaman Alat</h3>
            <p class="pm-sub">Kelola riwayat permohonan, status persetujuan, dan jadwal peminjaman alat.</p>
        </div>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-pm d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Peminjaman Baru</span>
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

    <!-- Card Pembungkus Tabel -->
    <div class="pm-panelx mb-4">

        <!-- Header Card -->
        <div class="pm-panelx-head">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-check fs-5"></i>
                <h6>Data Peminjaman Aktif</h6>
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

    {{-- =====================================================================
         BAGIAN TAMBAHAN: catatan & laporan (tema gelap, tanpa emoji)
         ===================================================================== --}}

    @php
        // Murni PHP biasa (tanpa collect / Carbon). Dibungkus try/catch: kalau gagal,
        // bagian tambahan tidak tampil, tabel asli tetap normal.
        $pmOk = true;
        try {
            $pmToday = strtotime(date('Y-m-d'));
            $pmItems = [];
            foreach ($datap as $pmX) { $pmItems[] = $pmX; }

            // Ikon Bootstrap Icons berdasarkan kata kunci nama alat (pengganti emoji)
            $pmIcon = function ($nama) {
                $n = strtolower((string) $nama);
                $map = ['bor'=>'bi-tools','obeng'=>'bi-tools','palu'=>'bi-hammer','gergaji'=>'bi-tools','tang'=>'bi-wrench','kunci'=>'bi-wrench',
                        'laptop'=>'bi-laptop','komputer'=>'bi-pc-display','pc'=>'bi-pc-display','kamera'=>'bi-camera','proyektor'=>'bi-projector','mic'=>'bi-mic',
                        'speaker'=>'bi-speaker','kabel'=>'bi-ethernet','lan'=>'bi-ethernet','router'=>'bi-router','switch'=>'bi-diagram-3','access'=>'bi-broadcast',
                        'tangga'=>'bi-ladder','solder'=>'bi-fire','meter'=>'bi-rulers','printer'=>'bi-printer','lampu'=>'bi-lightbulb','gunting'=>'bi-scissors',
                        'keyboard'=>'bi-keyboard','mouse'=>'bi-mouse','harddisk'=>'bi-device-hdd'];
                foreach ($map as $kw => $ic) { if (strpos($n, $kw) !== false) return $ic; }
                return 'bi-box-seam';
            };
            $pmTs = function ($v) {
                if (!$v) return null;
                $t = strtotime($v);
                return $t ? strtotime(date('Y-m-d', $t)) : null;
            };
            $pmNama = function ($p) { return isset($p->alat->nama_alat) ? $p->alat->nama_alat : ('Alat ID: ' . $p->id_alat); };
            $pmUser = function ($p) { return isset($p->user->nama) ? $p->user->nama : ('User ID: ' . $p->id_user); };

            // Info jatuh tempo: kelas warna, teks, sisa hari, progres waktu (%)
            $pmDueInfo = function ($p) use ($pmTs, $pmToday) {
                $d = $pmTs($p->tanggal_kembali); $s = $pmTs($p->tanggal_pinjam);
                $i = ['cls' => 'ok', 'txt' => 'Tanpa tenggat', 'sisa' => null, 'pct' => 0];
                if ($d) {
                    $sisa = (int) round(($d - $pmToday) / 86400);
                    $i['sisa'] = $sisa;
                    if ($sisa < 0)       { $i['cls'] = 'bad';  $i['txt'] = 'Telat ' . abs($sisa) . ' hari'; }
                    elseif ($sisa == 0)  { $i['cls'] = 'warn'; $i['txt'] = 'Jatuh tempo hari ini'; }
                    elseif ($sisa <= 2)  { $i['cls'] = 'warn'; $i['txt'] = 'Sisa ' . $sisa . ' hari'; }
                    else                 { $i['cls'] = 'ok';   $i['txt'] = 'Sisa ' . $sisa . ' hari'; }
                    if ($s && $d > $s) { $i['pct'] = (int) min(100, max(0, round(($pmToday - $s) / ($d - $s) * 100))); }
                    else { $i['pct'] = $sisa < 0 ? 100 : 0; }
                }
                return $i;
            };

            $pmAktif = []; $pmKembaliAll = []; $pmKembaliTelat = []; $pmTerlambat = [];
            $pmCount = []; $pmAlat = []; $pmBulan = []; $pmTotalDenda = 0;

            foreach ($pmItems as $p) {
                $st = $p->status;
                $pmCount[$st] = isset($pmCount[$st]) ? $pmCount[$st] + 1 : 1;
                $pmTotalDenda += (int) ($p->denda ?? 0);

                if ($st == 'Pending' || $st == 'Disetujui' || $st == 'Dipinjam') { $pmAktif[] = $p; }
                if ($st == 'Dikembalikan') {
                    $pmKembaliAll[] = $p;
                    if (($p->denda ?? 0) > 0) { $pmKembaliTelat[] = $p; }
                }
                if ($st != 'Ditolak') {
                    $nm = $pmNama($p);
                    if (!isset($pmAlat[$nm])) { $pmAlat[$nm] = ['kali' => 0, 'unit' => 0]; }
                    $pmAlat[$nm]['kali']++;
                    $pmAlat[$nm]['unit'] += (int) $p->jumlah;
                }
                $bl = substr((string) $p->tanggal_pinjam, 0, 7);
                if (strlen($bl) == 7) { $pmBulan[$bl] = isset($pmBulan[$bl]) ? $pmBulan[$bl] + 1 : 1; }
            }

            usort($pmAktif, function ($a, $b) {
                return strcmp((string) ($a->tanggal_kembali ?? '9999-12-31'), (string) ($b->tanggal_kembali ?? '9999-12-31'));
            });
            foreach ($pmAktif as $p) {
                $d = $pmTs($p->tanggal_kembali);
                if ($p->status == 'Dipinjam' && $d && $d < $pmToday) { $pmTerlambat[] = $p; }
            }
            usort($pmKembaliAll, function ($a, $b) {
                return strcmp((string) ($b->tanggal_kembali ?? ''), (string) ($a->tanggal_kembali ?? ''));
            });
            $pmKembali = array_slice($pmKembaliAll, 0, 8);
            $pmTelatSemua = array_merge($pmTerlambat, $pmKembaliTelat);

            // Rekap status + donut: [ikon, warna]
            $pmStatusList = [
                'Pending'      => ['bi-hourglass-split',     '#e8d08f'],
                'Disetujui'    => ['bi-check2-circle',       '#e8a0bb'],
                'Dipinjam'     => ['bi-box-arrow-up-right',  '#c9a45c'],
                'Dikembalikan' => ['bi-check-circle',        '#8f8699'],
                'Ditolak'      => ['bi-x-circle',            '#b8506f'],
            ];
            $pmTotal = max(count($pmItems), 1);
            $pmParts = []; $pmAcc = 0;
            foreach ($pmStatusList as $k => $meta) {
                $c = isset($pmCount[$k]) ? $pmCount[$k] : 0;
                if ($c > 0) {
                    $from = $pmAcc; $pmAcc += $c / $pmTotal * 100;
                    $pmParts[] = $meta[1] . ' ' . round($from, 2) . '% ' . round($pmAcc, 2) . '%';
                }
            }
            $pmConic = count($pmParts) ? implode(', ', $pmParts) : '#352c32 0% 100%';

            // Tren bulanan (6 bulan terakhir)
            ksort($pmBulan);
            $pmBulan = array_slice($pmBulan, -6, 6, true);
            $pmBulanMax = 1;
            foreach ($pmBulan as $v) { if ($v > $pmBulanMax) { $pmBulanMax = $v; } }
            $pmNamaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

            // Alat paling sering dipakai
            uasort($pmAlat, function ($a, $b) { return $b['kali'] - $a['kali']; });
            $pmTopList = [];
            foreach (array_slice($pmAlat, 0, 5, true) as $n => $v) { $pmTopList[] = ['nama' => $n, 'kali' => $v['kali'], 'unit' => $v['unit']]; }
            $pmTopMax = 1;
            foreach ($pmTopList as $v) { if ($v['kali'] > $pmTopMax) { $pmTopMax = $v['kali']; } }
        } catch (\Throwable $pmErr) { $pmOk = false; }
    @endphp

    @if(!empty($pmOk))
    <style>
        /* ===== Bagian tambahan ===== */
        .pm-wrap{ color:var(--pm-text); margin-top:.5rem; }

        .pm-hero{
            border-radius:1rem; padding:1.4rem 1.8rem; margin-bottom:1.3rem;
            background:linear-gradient(120deg,#46323b 0%,#2d2429 65%);
            border:1px solid var(--pm-line); border-left:4px solid var(--pm-rose);
            box-shadow:0 10px 30px rgba(0,0,0,.25);
            display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;
            animation:pmFade .5s ease both;
        }
        .pm-hero h4{ font-family:'Playfair Display',Georgia,serif; font-weight:700; margin:0 0 .3rem; color:var(--pm-text); }
        .pm-hero p{ margin:0; color:var(--pm-muted); font-size:.9rem; max-width:560px; }
        .pm-hero .pm-fl{ display:flex; gap:.7rem; }
        .pm-hero .pm-fl span{ width:46px; height:46px; border-radius:50%; display:flex; align-items:center; justify-content:center;
            border:1px solid rgba(227,192,108,.55); background:rgba(0,0,0,.25); color:var(--pm-gold); font-size:1.25rem; }
        .pm-btn{ margin-top:.8rem; border:1px solid rgba(227,192,108,.55); background:transparent; color:var(--pm-gold);
            font-weight:600; font-size:.84rem; padding:.45rem 1.1rem; border-radius:999px; transition:background .2s, color .2s; }
        .pm-btn:hover{ background:var(--pm-gold); color:var(--pm-ink); }

        .pm-card{
            background:var(--pm-card); border:1px solid var(--pm-line); border-radius:1rem; padding:1.25rem; height:100%;
            box-shadow:0 8px 24px rgba(0,0,0,.2); animation:pmFade .5s ease both;
        }
        .pm-title{ font-weight:700; display:flex; align-items:center; gap:.6rem; margin-bottom:1rem; color:var(--pm-text); }
        .pm-title::before{ content:""; width:4px; height:22px; border-radius:3px; background:var(--pm-gold); }

        .pm-stat{ display:flex; align-items:center; gap:.9rem; }
        .pm-stat .pm-ico{ width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.3rem;
            background:rgba(232,160,187,.16); color:var(--pm-rose); }
        .pm-stat.gold .pm-ico{ background:rgba(227,192,108,.16); color:var(--pm-gold); }
        .pm-stat.gray .pm-ico{ background:rgba(255,255,255,.08); color:#cfc6cb; }
        .pm-stat .pm-num{ font-size:1.5rem; font-weight:800; line-height:1.1; color:var(--pm-gold); }
        .pm-stat .pm-lbl{ font-size:.78rem; color:var(--pm-muted); }

        /* Tab tanpa JavaScript */
        .pm-radio{ position:absolute; opacity:0; pointer-events:none; }
        .pm-tabbar{ display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:1rem; padding-bottom:.9rem; border-bottom:1px solid var(--pm-line); }
        .pm-tabbar label{ cursor:pointer; display:inline-flex; align-items:center; gap:.45rem; padding:.5rem 1.1rem; border-radius:999px;
            background:var(--pm-card-2); font-weight:600; font-size:.85rem; color:var(--pm-muted); transition:background .2s, color .2s; }
        .pm-tabbar label:hover{ color:var(--pm-rose); }
        #pmt1:checked ~ .pm-tabbar label[for="pmt1"],
        #pmt2:checked ~ .pm-tabbar label[for="pmt2"],
        #pmt3:checked ~ .pm-tabbar label[for="pmt3"]{ background:var(--pm-gold); color:var(--pm-ink); }
        .pm-panel{ display:none; }
        #pmt1:checked ~ .pm-panels .pm-p1,
        #pmt2:checked ~ .pm-panels .pm-p2,
        #pmt3:checked ~ .pm-panels .pm-p3{ display:block; }

        .pm-search{ width:100%; border:1px solid var(--pm-line); border-radius:999px; padding:.55rem 1.1rem; margin-bottom:.9rem; font-size:.88rem; outline:none;
            background:var(--pm-card-2); color:var(--pm-text); }
        .pm-search::placeholder{ color:var(--pm-muted); }
        .pm-search:focus{ border-color:var(--pm-gold); box-shadow:0 0 0 3px rgba(227,192,108,.2); }

        .pm-list{ list-style:none; margin:0; padding:0; }
        .pm-item{ display:flex; align-items:center; gap:.9rem; padding:.8rem .95rem; margin-bottom:.55rem; border-radius:.85rem;
            background:rgba(255,255,255,.03); border:1px solid var(--pm-line); border-left:4px solid var(--pm-gold); }
        .pm-item.late{ border-left-color:var(--pm-rose-d); background:rgba(232,160,187,.08); }
        .pm-item .pm-emo{ width:42px; height:42px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center;
            border:1px solid rgba(227,192,108,.45); color:var(--pm-gold); font-size:1.15rem; background:rgba(0,0,0,.2); }
        .pm-item.late .pm-emo{ border-color:rgba(232,160,187,.55); color:var(--pm-rose); }
        .pm-item .pm-main{ flex:1; min-width:0; }
        .pm-item .pm-name{ font-weight:700; font-size:.92rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:var(--pm-text); }
        .pm-item .pm-sub{ font-size:.75rem; color:var(--pm-muted); }
        .pm-prog{ height:5px; background:rgba(255,255,255,.08); border-radius:999px; overflow:hidden; margin-top:.45rem; }
        .pm-prog > span{ display:block; height:100%; width:0; border-radius:999px; background:var(--pm-gold); animation:pmGrow 1s ease forwards; }
        .pm-prog > span.bad{ background:var(--pm-rose-d); }
        .pm-tag{ font-size:.72rem; font-weight:600; padding:.3rem .75rem; border-radius:999px; white-space:nowrap; border:1px solid transparent; }
        .pm-tag.ok{ background:rgba(227,192,108,.14); color:var(--pm-gold); border-color:rgba(227,192,108,.3); }
        .pm-tag.warn{ background:transparent; color:#f0d58a; border-color:rgba(227,192,108,.6); }
        .pm-tag.bad{ background:rgba(232,160,187,.18); color:#f6b9cf; border-color:rgba(232,160,187,.45); }
        .pm-tag.done{ background:transparent; color:#a6dcc3; border-color:rgba(80,180,130,.4); }

        .pm-donut-wrap{ display:flex; align-items:center; gap:1.4rem; flex-wrap:wrap; }
        .pm-donut{ width:140px; height:140px; border-radius:50%; position:relative; flex-shrink:0; }
        .pm-donut::after{ content:attr(data-total); position:absolute; inset:22px; background:var(--pm-card); border-radius:50%;
            display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:800; color:var(--pm-gold); }
        .pm-legend{ list-style:none; margin:0; padding:0; font-size:.85rem; flex:1; min-width:170px; }
        .pm-legend li{ display:flex; align-items:center; gap:.55rem; padding:.28rem 0; border-bottom:1px dashed var(--pm-line); color:var(--pm-text); }
        .pm-legend .pm-sw{ width:10px; height:10px; border-radius:3px; display:inline-block; flex-shrink:0; }
        .pm-legend .bi{ color:var(--pm-muted); }
        .pm-legend b{ margin-left:auto; color:var(--pm-gold); }

        .pm-cols{ display:flex; align-items:flex-end; gap:.7rem; height:130px; margin-top:1.4rem; border-bottom:1px solid var(--pm-line); }
        .pm-col{ flex:1; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; height:100%; font-size:.72rem; }
        .pm-col .pm-b{ width:100%; max-width:36px; height:var(--h); border-radius:.45rem .45rem 0 0; background:linear-gradient(180deg,var(--pm-gold),var(--pm-rose-d)); }
        .pm-col span{ margin-bottom:.25rem; font-weight:700; color:var(--pm-text); }
        .pm-lbls{ display:flex; gap:.7rem; font-size:.72rem; color:var(--pm-muted); text-align:center; margin-top:.35rem; }
        .pm-lbls div{ flex:1; }

        .pm-podium{ display:flex; align-items:flex-end; justify-content:center; gap:.7rem; margin-bottom:1.2rem; }
        .pm-pod{ flex:1; max-width:130px; text-align:center; }
        .pm-pod .pm-pe{ width:44px; height:44px; border-radius:50%; margin:0 auto .3rem; display:flex; align-items:center; justify-content:center;
            border:1px solid rgba(227,192,108,.55); color:var(--pm-gold); font-size:1.2rem; background:rgba(0,0,0,.2); }
        .pm-pod .pm-pn{ font-size:.78rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:var(--pm-text); }
        .pm-pod .pm-pb{ margin-top:.35rem; border-radius:.7rem .7rem 0 0; color:var(--pm-ink); font-weight:800; padding-top:.3rem; height:var(--h); }
        .pm-pod.p1 .pm-pb{ background:var(--pm-gold); --h:90px; }
        .pm-pod.p2 .pm-pb{ background:#a99ea4; --h:65px; }
        .pm-pod.p3 .pm-pb{ background:var(--pm-rose); --h:48px; }
        .pm-bar-row{ margin-bottom:.8rem; }
        .pm-bar-head{ display:flex; justify-content:space-between; font-size:.82rem; font-weight:600; margin-bottom:.3rem; color:var(--pm-text); }
        .pm-bar-head span:last-child{ color:var(--pm-muted); font-weight:500; }
        .pm-bar{ height:8px; background:rgba(255,255,255,.08); border-radius:999px; overflow:hidden; }
        .pm-bar > span{ display:block; height:100%; width:0; border-radius:999px; background:linear-gradient(90deg,var(--pm-rose-d),var(--pm-gold)); animation:pmGrow 1s ease forwards; }

        .pm-empty{ text-align:center; color:var(--pm-muted); padding:1.6rem 0; font-size:.88rem; list-style:none; }
        .pm-empty .pm-big{ width:56px; height:56px; border-radius:50%; margin:0 auto .6rem; display:flex; align-items:center; justify-content:center;
            border:1px solid rgba(227,192,108,.4); color:var(--pm-gold); font-size:1.4rem; }

        .pm-table{ width:100%; border-collapse:separate; border-spacing:0 .4rem; font-size:.85rem; color:var(--pm-text); }
        .pm-table th{ font-size:.7rem; text-transform:uppercase; letter-spacing:.07em; color:var(--pm-gold); padding:.3rem .8rem; font-weight:600; }
        .pm-table td{ background:rgba(232,160,187,.08); padding:.65rem .8rem; }
        .pm-table td:first-child{ border-radius:.7rem 0 0 .7rem; }
        .pm-table td:last-child{ border-radius:0 .7rem .7rem 0; }

        @@keyframes pmFade{ from{opacity:0;} to{opacity:1;} }
        @@keyframes pmGrow{ to{ width:var(--w); } }
        @@media (prefers-reduced-motion: reduce){ .pm-wrap *{ animation:none !important; } .pm-prog > span, .pm-bar > span{ width:var(--w) !important; } }
        @@media print{
            .pm-noprint{ display:none !important; }
            .pm-card, .pm-hero{ box-shadow:none !important; }
            .pm-page{ --pm-card:#fff; --pm-card-2:#f3f3f5; --pm-line:#ddd; --pm-text:#222; --pm-muted:#666; --pm-gold:#8a6a25; }
            .pm-hero{ background:#fff; }
        }
    </style>

    <div class="pm-wrap">

        <!-- Banner -->
        <div class="pm-hero">
            <div>
                <h4>Pusat Catatan &amp; Laporan Peminjaman</h4>
                <p>Catatan peminjaman, pengembalian, jatuh tempo, rekap riwayat, alat terpopuler, dan keterlambatan.</p>
                <button type="button" class="pm-btn pm-noprint" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak Laporan</button>
            </div>
            <div class="pm-fl"><span><i class="bi bi-laptop"></i></span><span><i class="bi bi-router"></i></span><span><i class="bi bi-ethernet"></i></span></div>
        </div>

        <!-- Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3"><div class="pm-card pm-stat"><div class="pm-ico"><i class="bi bi-journal-text"></i></div><div><div class="pm-num">{{ count($pmItems) }}</div><div class="pm-lbl">Total Riwayat</div></div></div></div>
            <div class="col-6 col-lg-3"><div class="pm-card pm-stat gold"><div class="pm-ico"><i class="bi bi-tools"></i></div><div><div class="pm-num">{{ count($pmAktif) }}</div><div class="pm-lbl">Catatan Aktif</div></div></div></div>
            <div class="col-6 col-lg-3"><div class="pm-card pm-stat"><div class="pm-ico"><i class="bi bi-alarm"></i></div><div><div class="pm-num">{{ count($pmTelatSemua) }}</div><div class="pm-lbl">Keterlambatan</div></div></div></div>
            <div class="col-6 col-lg-3"><div class="pm-card pm-stat gray"><div class="pm-ico"><i class="bi bi-cash-stack"></i></div><div><div class="pm-num" style="font-size:1.15rem;">Rp {{ number_format($pmTotalDenda, 0, ',', '.') }}</div><div class="pm-lbl">Total Denda</div></div></div></div>
        </div>

        <!-- Tab catatan (tanpa JavaScript) -->
        <div class="pm-card mb-4" style="height:auto;">
            <input type="radio" name="pmtab" id="pmt1" class="pm-radio" checked>
            <input type="radio" name="pmtab" id="pmt2" class="pm-radio">
            <input type="radio" name="pmtab" id="pmt3" class="pm-radio">

            <div class="pm-tabbar">
                <label for="pmt1"><i class="bi bi-journal-check"></i> Catatan Peminjaman &amp; Jatuh Tempo</label>
                <label for="pmt2"><i class="bi bi-check2-circle"></i> Catatan Pengembalian</label>
                <label for="pmt3"><i class="bi bi-alarm"></i> Keterlambatan</label>
            </div>

            <div class="pm-panels">
                <!-- Panel 1 -->
                <div class="pm-panel pm-p1">
                    <input type="text" id="pmSearch" class="pm-search pm-noprint" placeholder="Cari peminjam atau alat...">
                    <ul class="pm-list">
                        @forelse($pmAktif as $i => $p)
                            @php $inf = $pmDueInfo($p); $nm = $pmNama($p); @endphp
                            <li class="pm-item {{ $inf['cls'] == 'bad' ? 'late' : '' }}" style="animation-delay: {{ $i * 0.07 }}s">
                                <span class="pm-emo"><i class="bi {{ $pmIcon($nm) }}"></i></span>
                                <div class="pm-main">
                                    <div class="pm-name">{{ $nm }} &times; {{ $p->jumlah }}</div>
                                    <div class="pm-sub">{{ $pmUser($p) }} &bull; Pinjam {{ $p->tanggal_pinjam }} &bull; Tempo {{ $p->tanggal_kembali ?? '-' }} &bull; {{ $p->status }}</div>
                                    <div class="pm-prog"><span class="{{ $inf['cls'] == 'bad' ? 'bad' : '' }}" style="--w: {{ $inf['pct'] }}%;"></span></div>
                                </div>
                                <span class="pm-tag {{ $inf['cls'] }}">{{ $inf['txt'] }}</span>
                            </li>
                        @empty
                            <li class="pm-empty"><span class="pm-big"><i class="bi bi-journal-x"></i></span>Belum ada catatan peminjaman aktif.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Panel 2 -->
                <div class="pm-panel pm-p2">
                    <ul class="pm-list">
                        @forelse($pmKembali as $i => $p)
                            @php $nm = $pmNama($p); @endphp
                            <li class="pm-item" style="animation-delay: {{ $i * 0.07 }}s">
                                <span class="pm-emo"><i class="bi {{ $pmIcon($nm) }}"></i></span>
                                <div class="pm-main">
                                    <div class="pm-name">{{ $nm }} &times; {{ $p->jumlah }}</div>
                                    <div class="pm-sub">{{ $pmUser($p) }} &bull; Pinjam {{ $p->tanggal_pinjam }} &bull; Kembali {{ $p->tanggal_kembali ?? '-' }}</div>
                                </div>
                                @if(($p->denda ?? 0) > 0)
                                    <span class="pm-tag bad">Denda Rp {{ number_format($p->denda, 0, ',', '.') }}</span>
                                @else
                                    <span class="pm-tag done"><i class="bi bi-check2"></i> Tepat waktu</span>
                                @endif
                            </li>
                        @empty
                            <li class="pm-empty"><span class="pm-big"><i class="bi bi-inbox"></i></span>Belum ada catatan pengembalian.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Panel 3 -->
                <div class="pm-panel pm-p3">
                    @if(count($pmTelatSemua))
                        <div class="table-responsive">
                            <table class="pm-table">
                                <thead><tr><th>Alat</th><th>Peminjam</th><th>Jatuh Tempo</th><th>Keterangan</th><th>Denda</th></tr></thead>
                                <tbody>
                                    @foreach($pmTelatSemua as $i => $p)
                                        @php $nm = $pmNama($p); $d = $pmTs($p->tanggal_kembali); @endphp
                                        <tr class="pm-row" style="animation-delay: {{ $i * 0.07 }}s">
                                            <td><i class="bi {{ $pmIcon($nm) }} me-1" style="color:var(--pm-gold);"></i> <strong>{{ $nm }}</strong> &times; {{ $p->jumlah }}</td>
                                            <td>{{ $pmUser($p) }}</td>
                                            <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                                            <td>
                                                @if($p->status == 'Dipinjam' && $d)
                                                    <span class="pm-tag bad">Telat {{ (int) round(($pmToday - $d) / 86400) }} hari (belum kembali)</span>
                                                @else
                                                    <span class="pm-tag warn">Dikembalikan terlambat</span>
                                                @endif
                                            </td>
                                            <td><strong>Rp {{ number_format($p->denda ?? 0, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="pm-empty"><span class="pm-big"><i class="bi bi-patch-check"></i></span>Tidak ada keterlambatan. Semua alat aman.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Laporan rekap -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="pm-card">
                    <div class="pm-title">Rekap Riwayat Peminjaman</div>
                    <div class="pm-donut-wrap">
                        <div class="pm-donut" data-total="{{ count($pmItems) }}" style="background: conic-gradient({{ $pmConic }});"></div>
                        <ul class="pm-legend">
                            @foreach($pmStatusList as $k => $meta)
                                <li><span class="pm-sw" style="background: {{ $meta[1] }};"></span><i class="bi {{ $meta[0] }}"></i>{{ $k }}<b>{{ isset($pmCount[$k]) ? $pmCount[$k] : 0 }}</b></li>
                            @endforeach
                        </ul>
                    </div>

                    @if(count($pmBulan))
                        <div class="pm-cols">
                            @foreach($pmBulan as $k => $v)
                                <div class="pm-col"><span>{{ $v }}</span><div class="pm-b" style="--h: {{ max(8, round($v / $pmBulanMax * 100)) }}px;"></div></div>
                            @endforeach
                        </div>
                        <div class="pm-lbls">
                            @foreach($pmBulan as $k => $v)
                                <div>{{ $pmNamaBulan[(int) substr($k, 5, 2) - 1] }} {{ substr($k, 2, 2) }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="pm-card">
                    <div class="pm-title">Alat Paling Sering Dipakai</div>
                    @if(count($pmTopList))
                        <div class="pm-podium">
                            @foreach([1, 0, 2] as $ix)
                                @if(isset($pmTopList[$ix]))
                                    <div class="pm-pod p{{ $ix + 1 }}">
                                        <span class="pm-pe"><i class="bi {{ $pmIcon($pmTopList[$ix]['nama']) }}"></i></span>
                                        <div class="pm-pn">{{ $pmTopList[$ix]['nama'] }}</div>
                                        <div class="pm-pb">{{ $ix + 1 }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @foreach($pmTopList as $ix => $t)
                            <div class="pm-bar-row">
                                <div class="pm-bar-head"><span>{{ $ix + 1 }}. {{ $t['nama'] }}</span><span>{{ $t['kali'] }}x &bull; {{ $t['unit'] }} unit</span></div>
                                <div class="pm-bar"><span style="--w: {{ round($t['kali'] / $pmTopMax * 100) }}%; animation-delay: {{ $ix * 0.1 }}s;"></span></div>
                            </div>
                        @endforeach
                    @else
                        <div class="pm-empty"><span class="pm-big"><i class="bi bi-tools"></i></span>Belum ada data pemakaian alat.</div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var q = document.getElementById('pmSearch');
            if (!q) return;
            q.addEventListener('input', function () {
                var v = q.value.toLowerCase();
                document.querySelectorAll('.pm-p1 .pm-item').forEach(function (el) {
                    el.style.display = el.textContent.toLowerCase().indexOf(v) > -1 ? '' : 'none';
                });
            });
        });
    </script>
    @endif

</div>
@endsection