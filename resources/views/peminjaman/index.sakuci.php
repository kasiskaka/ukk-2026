@extends('layouts.app')

@section('title', config('app.name') . ' -- Daftar Peminjaman Alat')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root{
        --pm-pink:#fbe4ec; --pm-pink-2:#f4b9cd; --pm-pink-3:#e58aab;
        --pm-gray:#eeeef0; --pm-gray-2:#9a9aa3; --pm-gray-3:#4a4a53;
        --pm-white:#ffffff; --pm-gold:#c9a45c; --pm-gold-2:#f3e6c4;
    }
    /* ===== Tampilan halaman atas (murni CSS, tanpa mengubah fungsi) ===== */
    .pm-page{ max-width:1500px; margin:0 auto; }
    .pm-page > .d-flex:first-child{
        background:linear-gradient(120deg,var(--pm-white) 45%,var(--pm-pink) 100%);
        border:1px solid var(--pm-gold-2); border-left:6px solid var(--pm-gold);
        border-radius:1.2rem; padding:1.4rem 1.8rem; box-shadow:0 6px 20px rgba(201,164,92,.12);
    }
    .pm-page h3{ font-weight:800; color:var(--pm-gray-3) !important; letter-spacing:-.01em; }
    .pm-page p.text-body-secondary{ color:var(--pm-gray-2) !important; }
    .pm-page .badge.bg-primary-subtle{ background:var(--pm-gold-2) !important; color:#8a6a25 !important; padding:.4rem .85rem; }
    .pm-page .btn-primary{ background:var(--pm-gold); border-color:var(--pm-gold); color:#fff; transition:background .2s, transform .2s; }
    .pm-page .btn-primary:hover{ background:var(--pm-pink-3); border-color:var(--pm-pink-3); transform:translateY(-1px); }

    .pm-page .card.bg-body-tertiary{ background:var(--pm-white) !important; border:1px solid var(--pm-gray) !important; box-shadow:0 6px 22px rgba(74,74,83,.07) !important; }
    .pm-page .card-header{ background:linear-gradient(90deg,var(--pm-pink),var(--pm-white)) !important; border-bottom:1px solid var(--pm-gold-2) !important; }
    .pm-page .card-header .text-primary{ color:var(--pm-gold) !important; }
    .pm-page .card-header .badge{ background:var(--pm-white) !important; border-color:var(--pm-gold-2) !important; }

    .pm-page .table{ --bs-table-hover-bg:#fdf3f7; --bs-table-hover-color:inherit; }
    .pm-page .table thead{ background:var(--pm-gray) !important; }
    .pm-page .table thead th{ background:var(--pm-gray) !important; font-size:.72rem; letter-spacing:.06em; color:var(--pm-gray-3) !important; white-space:nowrap; border-bottom:2px solid var(--pm-gold-2); }
    .pm-page .table tbody td{ font-size:.88rem; padding-top:.9rem; padding-bottom:.9rem; white-space:nowrap; border-color:var(--pm-gray); }
    .pm-page .table tbody tr:nth-child(even) > *{ background-color:#fcfcfd; }
    .pm-page .fs-7{ font-size:.88rem !important; }
    .pm-page .fs-8{ font-size:.72rem !important; }
    .pm-page .badge{ padding:.4rem .75rem; white-space:nowrap; font-weight:600; }
    .pm-page .rounded-circle.bg-primary-subtle{ background:var(--pm-pink) !important; color:var(--pm-pink-3) !important; }
    .pm-page .btn-sm{ padding:.35rem .75rem; font-size:.8rem; border-radius:.6rem; }
    .pm-page .gap-1\.5{ gap:.4rem; }
    .pm-page .page-link{ color:var(--pm-gray-3); border-color:var(--pm-gray); }
    .pm-page .page-item.active .page-link{ background:var(--pm-gold); border-color:var(--pm-gold); color:#fff; }
</style>

<div class="container-fluid py-4 px-4 pm-page">
    
    <!-- Header Halaman & Tombol Tambah -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary px-2.5 py-1 rounded-pill fw-semibold small">
                    <i class="bi bi-box-seam me-1"></i> Transaksi Peminjaman
                </span>
            </div>
            <h3 class="fw-bold mb-1 text-body">Daftar Peminjaman Alat</h3>
            <p class="text-body-secondary small mb-0">Kelola riwayat permohonan, status persetujuan, dan jadwal peminjaman alat.</p>
        </div>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary fw-semibold px-3 py-2 rounded-3 shadow-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Peminjaman Baru</span>
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

    <!-- Card Pembungkus Tabel -->
    <div class="card border-0 bg-body-tertiary shadow-sm rounded-4 overflow-hidden mb-4">
        
        <!-- Header Card -->
        <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-list-check text-primary fs-5"></i>
                <h6 class="fw-bold mb-0 text-body">Data Peminjaman Aktif</h6>
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

    {{-- =====================================================================
         TAMBAHAN BARU v2 (kode asli di atas tidak diubah)
         Warna: pink soft, abu, putih, semi gold
         ===================================================================== --}}

    @php
        // Murni PHP biasa (tanpa collect / Carbon). Dibungkus try/catch: kalau gagal,
        // bagian tambahan tidak tampil, tabel asli tetap normal.
        $pmOk = true;
        try {
            $pmToday = strtotime(date('Y-m-d'));
            $pmItems = [];
            foreach ($datap as $pmX) { $pmItems[] = $pmX; }

            $pmEmoji = function ($nama) {
                $n = strtolower((string) $nama);
                $map = ['bor'=>'🪛','obeng'=>'🪛','palu'=>'🔨','gergaji'=>'🪚','tang'=>'🔧','kunci'=>'🔧',
                        'laptop'=>'💻','komputer'=>'🖥️','pc'=>'🖥️','kamera'=>'📷','proyektor'=>'📽️','mic'=>'🎤',
                        'speaker'=>'🔊','kabel'=>'🔌','lan'=>'🔌','router'=>'🌐','switch'=>'🔀','access'=>'📡',
                        'tangga'=>'🪜','solder'=>'🔥','meter'=>'📏','printer'=>'🖨️','lampu'=>'💡','gunting'=>'✂️',
                        'keyboard'=>'⌨️','mouse'=>'🖱️','harddisk'=>'💾'];
                foreach ($map as $kw => $emo) { if (strpos($n, $kw) !== false) return $emo; }
                return '🧰';
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

            // Rekap status + donut
            $pmStatusList = [
                'Pending'      => ['🌸', '#ecd9a4'],
                'Disetujui'    => ['✨', '#f4b9cd'],
                'Dipinjam'     => ['🧰', '#c9a45c'],
                'Dikembalikan' => ['✅', '#b8b8c0'],
                'Ditolak'      => ['🚫', '#e58aab'],
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
            $pmConic = count($pmParts) ? implode(', ', $pmParts) : '#eeeef0 0% 100%';

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
        /* ===== Bagian tambahan (gerakan dibuat minim) ===== */
        .pm-wrap{ color:var(--pm-gray-3); margin-top:.5rem; }
        .pm-sec{ display:flex; align-items:center; gap:.6rem; font-weight:800; font-size:1.15rem; margin:0 0 1rem; }
        .pm-sec::before{ content:""; width:6px; height:24px; border-radius:4px; background:var(--pm-gold); }

        .pm-hero{ border-radius:1.2rem; padding:1.4rem 1.8rem; margin-bottom:1.3rem; background:var(--pm-white);
            border:1px solid var(--pm-gray); border-left:6px solid var(--pm-pink-3); box-shadow:0 6px 20px rgba(74,74,83,.06);
            display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; animation:pmFade .5s ease both; }
        .pm-hero h4{ font-weight:800; margin:0 0 .25rem; }
        .pm-hero p{ margin:0; color:var(--pm-gray-2); font-size:.9rem; }
        .pm-hero .pm-fl{ font-size:2rem; letter-spacing:.3rem; }
        .pm-btn{ margin-top:.7rem; border:0; background:var(--pm-gold); color:#fff; font-weight:700; font-size:.85rem; padding:.5rem 1.1rem; border-radius:999px; transition:background .2s; }
        .pm-btn:hover{ background:var(--pm-pink-3); }

        .pm-card{ background:var(--pm-white); border:1px solid var(--pm-gray); border-radius:1.1rem; padding:1.25rem; height:100%;
            box-shadow:0 4px 16px rgba(74,74,83,.06); animation:pmFade .5s ease both; transition:box-shadow .2s, transform .2s; }
        .pm-card:hover{ transform:translateY(-2px); box-shadow:0 8px 22px rgba(201,164,92,.18); }
        .pm-title{ font-weight:800; display:flex; align-items:center; gap:.5rem; margin-bottom:1rem; }
        .pm-title .pm-dot{ width:8px; height:8px; border-radius:50%; background:var(--pm-gold); }

        .pm-stat{ display:flex; align-items:center; gap:.9rem; border-top:4px solid var(--pm-gold-2); }
        .pm-stat .pm-ico{ width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; background:var(--pm-pink); }
        .pm-stat.gold .pm-ico{ background:var(--pm-gold-2); } .pm-stat.gray .pm-ico{ background:var(--pm-gray); }
        .pm-stat .pm-num{ font-size:1.55rem; font-weight:800; line-height:1; } .pm-stat .pm-lbl{ font-size:.78rem; color:var(--pm-gray-2); }

        /* Tab tanpa JavaScript */
        .pm-radio{ position:absolute; opacity:0; pointer-events:none; }
        .pm-tabbar{ display:flex; flex-wrap:wrap; gap:.5rem; margin-bottom:1rem; padding-bottom:.9rem; border-bottom:1px solid var(--pm-gray); }
        .pm-tabbar label{ cursor:pointer; padding:.5rem 1.1rem; border-radius:999px; background:var(--pm-gray); font-weight:700; font-size:.86rem; color:var(--pm-gray-3); transition:background .2s, color .2s; }
        .pm-tabbar label:hover{ background:var(--pm-pink); }
        #pmt1:checked ~ .pm-tabbar label[for="pmt1"],
        #pmt2:checked ~ .pm-tabbar label[for="pmt2"],
        #pmt3:checked ~ .pm-tabbar label[for="pmt3"]{ background:var(--pm-gold); color:#fff; }
        .pm-panel{ display:none; }
        #pmt1:checked ~ .pm-panels .pm-p1,
        #pmt2:checked ~ .pm-panels .pm-p2,
        #pmt3:checked ~ .pm-panels .pm-p3{ display:block; }

        .pm-search{ width:100%; border:1px solid var(--pm-gray); border-radius:999px; padding:.55rem 1rem; margin-bottom:.9rem; font-size:.88rem; outline:none; background:var(--pm-white); color:var(--pm-gray-3); }
        .pm-search:focus{ border-color:var(--pm-gold); box-shadow:0 0 0 4px var(--pm-gold-2); }

        .pm-list{ list-style:none; margin:0; padding:0; }
        .pm-item{ display:flex; align-items:center; gap:.8rem; padding:.75rem .9rem; margin-bottom:.55rem; border-radius:.9rem; background:#fafafb;
            border:1px solid var(--pm-gray); border-left:5px solid var(--pm-gold); }
        .pm-item.late{ border-left-color:var(--pm-pink-3); background:var(--pm-pink); }
        .pm-item .pm-emo{ font-size:1.6rem; }
        .pm-item .pm-main{ flex:1; min-width:0; }
        .pm-item .pm-name{ font-weight:800; font-size:.92rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .pm-item .pm-sub{ font-size:.75rem; color:var(--pm-gray-2); }
        .pm-prog{ height:6px; background:var(--pm-gray); border-radius:999px; overflow:hidden; margin-top:.4rem; }
        .pm-prog > span{ display:block; height:100%; width:0; border-radius:999px; background:var(--pm-gold); animation:pmGrow 1s ease forwards; }
        .pm-prog > span.bad{ background:var(--pm-pink-3); }
        .pm-tag{ font-size:.7rem; font-weight:700; padding:.3rem .7rem; border-radius:999px; white-space:nowrap; }
        .pm-tag.ok{ background:var(--pm-gold-2); color:#8a6a25; }
        .pm-tag.warn{ background:var(--pm-white); color:#8a6a25; border:1px solid var(--pm-gold); }
        .pm-tag.bad{ background:var(--pm-pink-3); color:#fff; }
        .pm-tag.done{ background:var(--pm-white); color:var(--pm-gray-3); border:1px solid var(--pm-gray-2); }

        .pm-donut-wrap{ display:flex; align-items:center; gap:1.4rem; flex-wrap:wrap; }
        .pm-donut{ width:140px; height:140px; border-radius:50%; position:relative; flex-shrink:0; }
        .pm-donut::after{ content:attr(data-total); position:absolute; inset:24px; background:var(--pm-white); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:900; }
        .pm-legend{ list-style:none; margin:0; padding:0; font-size:.85rem; flex:1; min-width:150px; }
        .pm-legend li{ display:flex; align-items:center; gap:.5rem; padding:.22rem 0; border-bottom:1px dashed var(--pm-gray); }
        .pm-legend i{ width:12px; height:12px; border-radius:4px; display:inline-block; }
        .pm-legend b{ margin-left:auto; }

        .pm-cols{ display:flex; align-items:flex-end; gap:.7rem; height:130px; margin-top:1.2rem; border-bottom:2px solid var(--pm-gray); }
        .pm-col{ flex:1; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; height:100%; font-size:.72rem; }
        .pm-col .pm-b{ width:100%; max-width:38px; height:var(--h); border-radius:.5rem .5rem 0 0; background:linear-gradient(180deg,var(--pm-gold),var(--pm-pink-2)); }
        .pm-col span{ margin-bottom:.2rem; font-weight:700; }
        .pm-lbls{ display:flex; gap:.7rem; font-size:.72rem; color:var(--pm-gray-2); text-align:center; margin-top:.3rem; }
        .pm-lbls div{ flex:1; }

        .pm-podium{ display:flex; align-items:flex-end; justify-content:center; gap:.7rem; margin-bottom:1.1rem; }
        .pm-pod{ flex:1; max-width:130px; text-align:center; }
        .pm-pod .pm-pe{ font-size:2rem; display:block; }
        .pm-pod .pm-pn{ font-size:.78rem; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .pm-pod .pm-pb{ margin-top:.3rem; border-radius:.8rem .8rem 0 0; color:#fff; font-weight:900; padding-top:.3rem; height:var(--h); }
        .pm-pod.p1 .pm-pb{ background:var(--pm-gold); --h:90px; } .pm-pod.p2 .pm-pb{ background:var(--pm-gray-2); --h:65px; } .pm-pod.p3 .pm-pb{ background:var(--pm-pink-3); --h:48px; }
        .pm-bar-row{ margin-bottom:.8rem; } .pm-bar-head{ display:flex; justify-content:space-between; font-size:.82rem; font-weight:600; margin-bottom:.3rem; }
        .pm-bar{ height:10px; background:var(--pm-gray); border-radius:999px; overflow:hidden; }
        .pm-bar > span{ display:block; height:100%; width:0; border-radius:999px; background:linear-gradient(90deg,var(--pm-gold-2),var(--pm-gold)); animation:pmGrow 1s ease forwards; }

        .pm-empty{ text-align:center; color:var(--pm-gray-2); padding:1.4rem 0; font-size:.88rem; }
        .pm-empty .pm-big{ font-size:2.2rem; display:block; }

        .pm-table{ width:100%; border-collapse:separate; border-spacing:0 .4rem; font-size:.85rem; }
        .pm-table th{ font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:var(--pm-gray-2); padding:.3rem .8rem; }
        .pm-table td{ background:var(--pm-pink); padding:.6rem .8rem; }
        .pm-table td:first-child{ border-radius:.7rem 0 0 .7rem; } .pm-table td:last-child{ border-radius:0 .7rem .7rem 0; }

        @@keyframes pmFade{ from{opacity:0;} to{opacity:1;} }
        @@keyframes pmGrow{ to{ width:var(--w); } }
        @@media (prefers-reduced-motion: reduce){ .pm-wrap *{ animation:none !important; } .pm-prog > span, .pm-bar > span{ width:var(--w) !important; } }
        @@media print{ .pm-noprint{ display:none !important; } .pm-card, .pm-hero{ box-shadow:none !important; } }
    </style>

    <div class="pm-wrap">

        <!-- Banner -->
        <div class="pm-hero">
            <h4>📒 Pusat Catatan &amp; Laporan Peminjaman</h4>
            <p>Catatan peminjaman, pengembalian, jatuh tempo, rekap riwayat, alat terpopuler, dan keterlambatan.</p>
            <button type="button" class="pm-btn pm-noprint" onclick="window.print()">🖨️ Cetak Laporan</button>
            <div class="pm-fl"><span>💻</span><span>🌐</span><span>🔌</span></div>
        </div>

        <!-- Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3"><div class="pm-card pm-stat"><div class="pm-ico">📋</div><div><div class="pm-num">{{ count($pmItems) }}</div><div class="pm-lbl">Total Riwayat</div></div></div></div>
            <div class="col-6 col-lg-3"><div class="pm-card pm-stat gold"><div class="pm-ico">🧰</div><div><div class="pm-num">{{ count($pmAktif) }}</div><div class="pm-lbl">Catatan Aktif</div></div></div></div>
            <div class="col-6 col-lg-3"><div class="pm-card pm-stat"><div class="pm-ico">⏰</div><div><div class="pm-num">{{ count($pmTelatSemua) }}</div><div class="pm-lbl">Keterlambatan</div></div></div></div>
            <div class="col-6 col-lg-3"><div class="pm-card pm-stat gray"><div class="pm-ico">💰</div><div><div class="pm-num" style="font-size:1.15rem;">Rp {{ number_format($pmTotalDenda, 0, ',', '.') }}</div><div class="pm-lbl">Total Denda</div></div></div></div>
        </div>

        <!-- Tab catatan (tanpa JavaScript) -->
        <div class="pm-card mb-4" style="height:auto;">
            <input type="radio" name="pmtab" id="pmt1" class="pm-radio" checked>
            <input type="radio" name="pmtab" id="pmt2" class="pm-radio">
            <input type="radio" name="pmtab" id="pmt3" class="pm-radio">

            <div class="pm-tabbar">
                <label for="pmt1">📝 Catatan Peminjaman &amp; Jatuh Tempo</label>
                <label for="pmt2">✅ Catatan Pengembalian</label>
                <label for="pmt3">⏰ Keterlambatan</label>
            </div>

            <div class="pm-panels">
                <!-- Panel 1 -->
                <div class="pm-panel pm-p1">
                    <input type="text" id="pmSearch" class="pm-search pm-noprint" placeholder="🔍 Cari peminjam atau alat...">
                    <ul class="pm-list">
                        @forelse($pmAktif as $i => $p)
                            @php $inf = $pmDueInfo($p); $nm = $pmNama($p); @endphp
                            <li class="pm-item {{ $inf['cls'] == 'bad' ? 'late' : '' }}" style="animation-delay: {{ $i * 0.07 }}s">
                                <span class="pm-emo">{{ $pmEmoji($nm) }}</span>
                                <div class="pm-main">
                                    <div class="pm-name">{{ $nm }} &times; {{ $p->jumlah }}</div>
                                    <div class="pm-sub">{{ $pmUser($p) }} &bull; Pinjam {{ $p->tanggal_pinjam }} &bull; Tempo {{ $p->tanggal_kembali ?? '-' }} &bull; {{ $p->status }}</div>
                                    <div class="pm-prog"><span class="{{ $inf['cls'] == 'bad' ? 'bad' : '' }}" style="--w: {{ $inf['pct'] }}%;"></span></div>
                                </div>
                                <span class="pm-tag {{ $inf['cls'] }}">{{ $inf['txt'] }}</span>
                            </li>
                        @empty
                            <li class="pm-empty"><span class="pm-big">🌸</span>Belum ada catatan peminjaman aktif.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Panel 2 -->
                <div class="pm-panel pm-p2">
                    <ul class="pm-list">
                        @forelse($pmKembali as $i => $p)
                            @php $nm = $pmNama($p); @endphp
                            <li class="pm-item" style="animation-delay: {{ $i * 0.07 }}s">
                                <span class="pm-emo">{{ $pmEmoji($nm) }}</span>
                                <div class="pm-main">
                                    <div class="pm-name">{{ $nm }} &times; {{ $p->jumlah }}</div>
                                    <div class="pm-sub">{{ $pmUser($p) }} &bull; Pinjam {{ $p->tanggal_pinjam }} &bull; Kembali {{ $p->tanggal_kembali ?? '-' }}</div>
                                </div>
                                @if(($p->denda ?? 0) > 0)
                                    <span class="pm-tag bad">Denda Rp {{ number_format($p->denda, 0, ',', '.') }}</span>
                                @else
                                    <span class="pm-tag done">Tepat waktu ✔</span>
                                @endif
                            </li>
                        @empty
                            <li class="pm-empty"><span class="pm-big">📦</span>Belum ada catatan pengembalian.</li>
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
                                            <td>{{ $pmEmoji($nm) }} <strong>{{ $nm }}</strong> &times; {{ $p->jumlah }}</td>
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
                        <div class="pm-empty"><span class="pm-big">🎉</span>Tidak ada keterlambatan. Semua alat aman!</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Laporan rekap -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="pm-card">
                    <div class="pm-title"><span class="pm-dot"></span> 📊 Rekap Riwayat Peminjaman</div>
                    <div class="pm-donut-wrap">
                        <div class="pm-donut" data-total="{{ count($pmItems) }}" style="background: conic-gradient({{ $pmConic }});"></div>
                        <ul class="pm-legend">
                            @foreach($pmStatusList as $k => $meta)
                                <li><i style="background: {{ $meta[1] }};"></i>{{ $meta[0] }} {{ $k }}<b>{{ isset($pmCount[$k]) ? $pmCount[$k] : 0 }}</b></li>
                            @endforeach
                        </ul>
                    </div>

                    @if(count($pmBulan))
                        <div class="pm-cols">
                            @foreach($pmBulan as $k => $v)
                                <div class="pm-col"><span>{{ $v }}</span><div class="pm-b" style="--h: {{ max(8, round($v / $pmBulanMax * 100)) }}px; animation-delay: {{ array_search($k, array_keys($pmBulan)) * 0.1 }}s;"></div></div>
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
                    <div class="pm-title"><span class="pm-dot"></span> 🏆 Alat Paling Sering Dipakai</div>
                    @if(count($pmTopList))
                        <div class="pm-podium">
                            @foreach([1, 0, 2] as $ix)
                                @if(isset($pmTopList[$ix]))
                                    <div class="pm-pod p{{ $ix + 1 }}">
                                        <span class="pm-pe">{{ $pmEmoji($pmTopList[$ix]['nama']) }}</span>
                                        <div class="pm-pn">{{ $pmTopList[$ix]['nama'] }}</div>
                                        <div class="pm-pb">{{ $ix + 1 }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @foreach($pmTopList as $ix => $t)
                            <div class="pm-bar-row">
                                <div class="pm-bar-head"><span>{{ $ix + 1 }}. {{ $pmEmoji($t['nama']) }} {{ $t['nama'] }}</span><span>{{ $t['kali'] }}x &bull; {{ $t['unit'] }} unit</span></div>
                                <div class="pm-bar"><span style="--w: {{ round($t['kali'] / $pmTopMax * 100) }}%; animation-delay: {{ $ix * 0.1 }}s;"></span></div>
                            </div>
                        @endforeach
                    @else
                        <div class="pm-empty"><span class="pm-big">🧰</span>Belum ada data pemakaian alat.</div>
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