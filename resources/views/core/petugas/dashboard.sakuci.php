@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')

<div class="container-fluid">

    <h1 class="page-title h3 mb-1">Selamat datang, {{ $user->username }}</h1>
    <p class="text-secondary mb-4">Halaman petugas untuk mengelola alat dan peminjaman.</p>


    <!-- Ringkasan -->
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-secondary small">Peminjaman pending</div>
                    <div class="fs-2 fw-semibold">{{ $totalPending }}</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-secondary small">Peminjaman disetujui</div>
                    <div class="fs-2 fw-semibold">{{ $totalDisetujui }}</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-secondary small">Total data alat</div>
                    <div class="fs-2 fw-semibold">{{ $totalAlat }}</div>
                </div>
            </div>
        </div>

    </div>


    <!-- Menu tugas -->
    <h2 class="page-title h5 mb-3">Menu tugas</h2>

    <div class="list-group">
        <a class="list-group-item list-group-item-action" href="{{ route('peminjaman.index') }}">Persetujuan peminjaman</a>
        <a class="list-group-item list-group-item-action" href="{{ route('pengembalian.index') }}">Kelola pengembalian</a>
        <a class="list-group-item list-group-item-action" href="{{ route('alat.index') }}">Data alat</a>
    </div>

</div>

@endsection