@extends('layouts.app')

@section('content')
<style>
    /*
      CSS saja, isi HTML/Blade di bawah tidak diubah.
      Warna dasar ikut variabel Bootstrap, jadi otomatis gelap/terang mengikuti layout.
      Semua aturan dibatasi ke .container yang langsung berisi tabel kategori.
    */
    :root {
        --k-pink: #f2a7c3;
        --k-gold: #b8912f;
    }
    [data-bs-theme="dark"] { --k-pink: #e58fb0; --k-gold: #e2c064; }
    @media (prefers-color-scheme: dark) {
        :root:not([data-bs-theme="light"]) { --k-pink: #e58fb0; --k-gold: #e2c064; }
    }

    /* ---------- susunan atas: judul + tombol tambah sebaris ---------- */
    .container:has(> table.table-hover) {
        --k-tint:   color-mix(in srgb, var(--k-pink) 14%, var(--bs-body-bg));
        --k-tint-2: color-mix(in srgb, var(--k-pink) 26%, var(--bs-body-bg));
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        column-gap: 1rem;
        padding-top: 1.5rem;
        padding-bottom: 2rem;
    }

    /* judul jadi kartu pink dengan garis gold */
    .container:has(> table.table-hover) > h1 {
        flex: 1 1 16rem;
        display: flex; align-items: center; gap: .7rem;
        margin: 0 0 1rem;
        padding: 1.1rem 1.4rem;
        font-size: 1.5rem;
        font-weight: 700;
        background: var(--k-tint);
        border: 1px solid var(--bs-border-color);
        border-left: 5px solid var(--k-gold);
        border-radius: .9rem;
    }
    .container:has(> table.table-hover) > h1::before {
        content: "🗂️";
        display: inline-block;
        animation: kat-float 4.5s ease-in-out infinite;
    }
    @keyframes kat-float {
        0%, 100% { transform: translateY(0) rotate(-5deg); }
        50%      { transform: translateY(-5px) rotate(5deg); }
    }

    /* tombol tambah: pil hitam (putih di mode gelap) dengan tanda + gold */
    .container:has(> table.table-hover) > a.btn-primary {
        display: inline-flex; align-items: center; gap: .5rem;
        font-weight: 600; font-size: .9rem;
        color: var(--bs-body-bg);
        background: var(--bs-emphasis-color);
        border: 1px solid var(--bs-emphasis-color);
        border-radius: 999px;
        padding: .55rem 1.2rem;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease, border-color .2s ease;
    }
    .container:has(> table.table-hover) > a.btn-primary::before {
        content: "+";
        color: var(--k-gold);
        font-size: 1.2rem; line-height: 1;
        transition: transform .25s ease, color .2s ease;
    }
    .container:has(> table.table-hover) > a.btn-primary:hover {
        color: #17161a;
        background: var(--k-gold);
        border-color: var(--k-gold);
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
    }
    .container:has(> table.table-hover) > a.btn-primary:hover::before { color: #17161a; transform: rotate(90deg); }
    .container:has(> table.table-hover) > a.btn-primary:focus-visible { outline: 2px solid var(--k-gold); outline-offset: 2px; }

    /* ---------- tabel ---------- */
    .container > table.table-hover {
        --bs-table-bg: transparent;
        --bs-table-striped-bg: color-mix(in srgb, var(--k-pink) 6%, var(--bs-body-bg));
        --bs-table-hover-bg: color-mix(in srgb, var(--k-pink) 18%, var(--bs-body-bg));
        --bs-table-hover-color: var(--bs-body-color);
        flex: 0 0 100%;
        width: 100%;
        margin: 0;
        vertical-align: middle;
        border-collapse: separate;
        border-spacing: 0;
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: .9rem;
        overflow: hidden;
    }
    .container > table.table-hover thead th {
        background: color-mix(in srgb, var(--k-pink) 26%, var(--bs-body-bg));
        color: var(--bs-emphasis-color);
        font-size: .82rem;
        font-weight: 600;
        letter-spacing: .04em;
        padding: .9rem 1rem;
        border-bottom: 2px solid var(--k-gold);
        white-space: nowrap;
    }
    .container > table.table-hover tbody td {
        padding: .95rem 1rem;
        border-color: var(--bs-border-color);
        vertical-align: middle;
    }
    .container > table.table-hover tbody tr:last-child td { border-bottom: 0; }

    /* isi kolom */
    .container > table.table-hover tbody td:nth-child(1) { color: var(--bs-secondary-color); font-weight: 600; width: 4rem; }
    .container > table.table-hover tbody td:nth-child(2) {
        font-family: var(--bs-font-monospace);
        font-size: .85rem;
        color: var(--k-gold);
        font-weight: 600;
    }
    .container > table.table-hover tbody td:nth-child(3) { font-weight: 600; color: var(--bs-emphasis-color); }
    .container > table.table-hover tbody td:nth-child(4) { color: var(--bs-secondary-color); font-size: .9rem; }

    /* baris masuk bergantian, sekali saat halaman dibuka */
    .container > table.table-hover tbody tr { animation: kat-in .4s ease both; }
    .container > table.table-hover tbody tr:nth-child(2)  { animation-delay: .04s; }
    .container > table.table-hover tbody tr:nth-child(3)  { animation-delay: .08s; }
    .container > table.table-hover tbody tr:nth-child(4)  { animation-delay: .12s; }
    .container > table.table-hover tbody tr:nth-child(5)  { animation-delay: .16s; }
    .container > table.table-hover tbody tr:nth-child(6)  { animation-delay: .20s; }
    .container > table.table-hover tbody tr:nth-child(7)  { animation-delay: .24s; }
    .container > table.table-hover tbody tr:nth-child(8)  { animation-delay: .28s; }
    .container > table.table-hover tbody tr:nth-child(n+9) { animation-delay: .32s; }
    @keyframes kat-in { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

    /* ---------- tombol aksi ---------- */
    .container > table.table-hover .btn-sm {
        font-size: .8rem; font-weight: 600;
        border-radius: 999px;
        padding: .3rem .9rem;
        background: transparent;
        transition: background .2s ease, color .2s ease, transform .15s ease;
    }
    .container > table.table-hover .btn-sm:hover { transform: translateY(-1px); }
    .container > table.table-hover .btn-primary.btn-sm { color: var(--k-gold); border: 1px solid var(--k-gold); }
    .container > table.table-hover .btn-primary.btn-sm:hover { background: var(--k-gold); color: #17161a; border-color: var(--k-gold); }
    .container > table.table-hover .btn-danger.btn-sm  { color: var(--bs-danger); border: 1px solid var(--bs-danger); }
    .container > table.table-hover .btn-danger.btn-sm:hover  { background: var(--bs-danger); color: #fff; border-color: var(--bs-danger); }
    .container > table.table-hover .btn-sm:focus-visible { outline: 2px solid var(--k-gold); outline-offset: 2px; box-shadow: none; }

    /* ---------- pagination ---------- */
    .container:has(> table.table-hover) > nav { flex: 0 0 100%; margin-top: 1.25rem; }
    .container:has(> table.table-hover) .pagination {
        --bs-pagination-color: var(--bs-body-color);
        --bs-pagination-hover-color: var(--bs-emphasis-color);
        --bs-pagination-hover-bg: color-mix(in srgb, var(--k-pink) 18%, var(--bs-body-bg));
        --bs-pagination-active-bg: var(--k-gold);
        --bs-pagination-active-border-color: var(--k-gold);
        --bs-pagination-active-color: #17161a;
        --bs-pagination-focus-box-shadow: 0 0 0 .2rem color-mix(in srgb, var(--k-gold) 30%, transparent);
        justify-content: center;
    }

    @media (max-width: 767.98px) {
        .container:has(> table.table-hover) { overflow-x: auto; }
        .container > table.table-hover thead th,
        .container > table.table-hover tbody td { padding: .75rem .7rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .container *, .container *::before, .container *::after { animation: none !important; transition: none !important; }
    }
</style>

<div class="container">
    <h1>Daftar Kategori</h1>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Kategori</th>
            <th>Nama Kategori</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($data as $item)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item->kode_kategori }}</td>
            <td>{{ $item->nama_kategori }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>
                <a href="{{ route('kategori.edit', ['id_kategori' => $item->id_kategori]) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('kategori.destroy', ['id_kategori' => $item->id_kategori]) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
{!! $data->links() !!}
@endsection