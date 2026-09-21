@extends('layouts.app')

@section('title', config('peminjaman alat jaringan & komputer') . ' peminjaman alat jaringan & komputer')

@section('content')

    {{-- Hero --}}
    <section class="text-center py-4 py-lg-5">
        <span class="badge rounded-pill badge-brand px-3 py-2 mb-3">Siska.nih</span>

        <h1 class="display-5 fw-bold mb-3">
            Welcome To sistem <br class="d-none d-md-inline">
            <span class="text-brand">Peminjaman Alat Siscas!</span>
        </h1>

        <p class="lead text-secondary mx-auto mb-4" style="max-width: 620px;">
            Pinjam Alat Lebih Mudah, Cepat, dan Terintegrasi.
            Temukan peralatan yang kamu butuhkan, ajukan peminjaman,
            dan jaga keamanan inventaris bersama.
        </p>

        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a class="btn btn-brand btn-lg px-4" href="#langkah">JELAJAHI KATALOG</a>
            <a class="btn btn-outline-brand btn-lg px-4" href="https://github.com/indrabsus/sakuci-framework" target="_blank">Alat</a>
        </div>

        <p class="text-secondary small mt-3 mb-0">
            mulai cari yang kalian butuhkan lalu pinjam!
    </section>
@endsection
