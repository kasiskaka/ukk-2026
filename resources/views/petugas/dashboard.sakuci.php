@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Dashboard Petugas</h3>
            <p class="text-muted mb-0">Selamat datang kembali, <?= htmlspecialchars($user->username ?? 'Petugas') ?>!</p>
        </div>
        <span class="badge bg-primary px-3 py-2 rounded-pill">Role: Petugas</span>
    </div>

    <!-- Cards Ringkasan Ringkas -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-warning text-dark">
                <div class="card-body p-3">
                    <h6 class="card-title text-uppercase fw-bold text-muted mb-1" style="font-size: 0.8rem;">Peminjaman Pending</h6>
                    <h2 class="fw-bold mb-0"><?= $totalPending ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-success text-white">
                <div class="card-body p-3">
                    <h6 class="card-title text-uppercase fw-bold text-white-50 mb-1" style="font-size: 0.8rem;">Disetujui</h6>
                    <h2 class="fw-bold mb-0"><?= $totalDisetujui ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-info text-white">
                <div class="card-body p-3">
                    <h6 class="card-title text-uppercase fw-bold text-white-50 mb-1" style="font-size: 0.8rem;">Total Alat</h6>
                    <h2 class="fw-bold mb-0"><?= $totalAlat ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Akses Cepat Menu Petugas -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold m-0 text-dark">Menu Kelola Petugas</h5>
        </div>
        <div class="card-body">
            <div class="d-flex gap-2 wrap">
                <a href="/admin/peminjaman" class="btn btn-primary px-4">Kelola Peminjaman</a>
                <a href="/admin/pengembalian" class="btn btn-outline-secondary px-4">Kelola Pengembalian</a>
                <a href="/admin/alat" class="btn btn-outline-info px-4">Daftar Alat</a>
            </div>
        </div>
    </div>
</div>
@endsection