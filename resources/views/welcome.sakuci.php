@extends('layouts.app')

@section('title', 'Peminjaman Alat Jaringan & Komputer')

@section('content')

<section class="welcome">

    <h1 class="welcome-title">Peminjaman alat Lab NET &amp; PC</h1>

    <p class="welcome-desc">
        Cek alat yang tersedia, ajukan peminjaman, dan pantau statusnya
        dari satu tempat.
    </p>

    <div class="welcome-actions">
        <a href="{{ route('login') }}" class="welcome-btn welcome-btn-primary">Masuk</a>
        <a href="{{ route('register') }}" class="welcome-btn">Daftar</a>
    </div>


    <div class="welcome-tools">
        <h2>Alat yang bisa dipinjam</h2>

        <div class="welcome-tools-grid">

            <div class="welcome-tool">
                <strong>Laptop</strong>
                <p>Untuk kebutuhan praktik dan pengerjaan tugas.</p>
            </div>

            <div class="welcome-tool">
                <strong>Mouse dan keyboard</strong>
                <p>Perangkat pendukung untuk komputer.</p>
            </div>

            <div class="welcome-tool">
                <strong>Monitor</strong>
                <p>Layar tambahan untuk kebutuhan praktik.</p>
            </div>

            <div class="welcome-tool">
                <strong>Perangkat jaringan</strong>
                <p>Router dan perangkat jaringan lain untuk praktik.</p>
            </div>

        </div>
    </div>

</section>

@endsection