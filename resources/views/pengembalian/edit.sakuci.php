@extends('layouts.app')

@section('title', config('app.name') . ' -- Edit Data Pengembalian')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

<style>
    .pm-form-page{
        --pm-card:#2a2428; --pm-card-2:#352c32; --pm-line:rgba(255,255,255,.08);
        --pm-text:#f4eef1; --pm-muted:#a99ea4;
        --pm-gold:#e3c06c; --pm-gold-d:#c9a45c; --pm-rose:#e8a0bb; --pm-rose-d:#d97ea1; --pm-ink:#2a1f24;
        max-width:900px; margin:0 auto; color:var(--pm-text);
    }
    .pm-form-page .pm-head{
        background:linear-gradient(120deg,#46323b 0%,#2d2429 65%);
        border:1px solid var(--pm-line); border-left:4px solid var(--pm-gold);
        border-radius:1rem; padding:1.4rem 1.8rem; box-shadow:0 10px 30px rgba(0,0,0,.25);
    }
    .pm-form-page .pm-chip{ display:inline-flex; align-items:center; gap:.4rem; border:1px solid rgba(227,192,108,.55); color:var(--pm-gold);
        border-radius:999px; padding:.3rem .85rem; font-size:.74rem; font-weight:600; letter-spacing:.06em; }
    .pm-form-page .pm-h1{ font-family:'Playfair Display',Georgia,serif; font-weight:700; font-size:1.7rem; margin:.5rem 0 .3rem; color:var(--pm-text); }
    .pm-form-page .pm-sub{ color:var(--pm-muted); font-size:.9rem; margin:0; }
    .pm-form-page .pm-panelx{ background:var(--pm-card); border:1px solid var(--pm-line); border-radius:1rem; padding:1.6rem 1.8rem; box-shadow:0 10px 30px rgba(0,0,0,.22); }
    .pm-form-page label{ display:block; margin-bottom:.35rem; font-size:.74rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase; color:var(--pm-gold); }
    .pm-form-page .form-control, .pm-form-page .form-select{
        background:var(--pm-card-2); border:1px solid var(--pm-line); color:var(--pm-text); border-radius:.65rem; padding:.6rem .8rem;
    }
    .pm-form-page .form-control:focus, .pm-form-page .form-select:focus{
        background:var(--pm-card-2); color:var(--pm-text); border-color:var(--pm-gold); box-shadow:0 0 0 .2rem rgba(227,192,108,.18);
    }
    .pm-form-page .form-text{ color:var(--pm-muted); font-size:.76rem; }
    .pm-form-page input[type="date"]{ color-scheme:dark; }
    .pm-form-page .btn-simpan{ background:var(--pm-gold); border:1px solid var(--pm-gold); color:var(--pm-ink); font-weight:700; border-radius:.65rem; padding:.55rem 1.4rem; }
    .pm-form-page .btn-simpan:hover{ background:var(--pm-gold-d); border-color:var(--pm-gold-d); }
    .pm-form-page .btn-batal{ background:transparent; border:1px solid rgba(232,160,187,.5); color:var(--pm-rose); border-radius:.65rem; padding:.55rem 1.4rem; text-decoration:none; }
    .pm-form-page .btn-batal:hover{ background:var(--pm-rose-d); color:var(--pm-ink); }
    .pm-form-page .pm-alert-err{ background:rgba(220,80,100,.12); border:1px solid rgba(220,80,100,.35); color:#ffb3c0; border-radius:.9rem; padding:.8rem 1rem; }
</style>

<div class="container-fluid py-4 px-4 pm-form-page">

    <div class="pm-head mb-4">
        <span class="pm-chip"><i class="bi bi-box-seam"></i> Transaksi Pengembalian</span>
        <h3 class="pm-h1">Edit Data Pengembalian</h3>
        <p class="pm-sub">Ubah data pengembalian alat.</p>
    </div>

    @if(session('error'))
        <div class="alert pm-alert-err mb-4" role="alert">{{ session('error') }}</div>
    @endif

    <div class="pm-panelx">
        <form action="/admin/pengembalian/{{ $datap->id_peminjaman }}/update" method="POST">
            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label for="id_user">Peminjam</label>
                    <select name="id_user" id="id_user" class="form-select" required>
                        <option value="">-- Pilih peminjam --</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" {{ $u->id == $datap->id_user ? 'selected' : '' }}>{{ $u->username ?? $u->name ?? ('#' . $u->id) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="id_alat">Nama alat</label>
                    <select name="id_alat" id="id_alat" class="form-select" required>
                        <option value="">-- Pilih alat --</option>
                        @foreach ($alat as $a)
                            <option value="{{ $a->id_alat }}" {{ $a->id_alat == $datap->id_alat ? 'selected' : '' }}>{{ $a->nama_alat ?? ('#' . $a->id_alat) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="jumlah">Jumlah (unit)</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" value="{{ $datap->jumlah }}" required>
                </div>

                <div class="col-md-4">
                    <label for="tanggal_pinjam">Tanggal pinjam</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" value="{{ substr((string) $datap->tanggal_pinjam, 0, 10) }}" required>
                </div>

                <div class="col-md-4">
                    <label for="tanggal_kembali">Tanggal kembali</label>
                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="{{ substr((string) $datap->tanggal_kembali, 0, 10) }}">
                    <div class="form-text">Boleh kosong. Kalau status Dikembalikan dan dikosongkan, diisi hari ini.</div>
                </div>

                <div class="col-md-6">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="Pending" {{ $datap->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Disetujui" {{ $datap->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Dipinjam" {{ $datap->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ $datap->status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="Ditolak" {{ $datap->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Denda</label>
                    <div class="form-text pt-2">Dihitung otomatis: telat 2 hari atau lebih dari batas pinjam 3 hari kena Rp 50.000.</div>
                </div>

            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-simpan"><i class="bi bi-check2-circle me-1"></i> Simpan</button>
                <a href="/admin/pengembalian" class="btn-batal">Batal</a>
            </div>
        </form>
    </div>

</div>
@endsection