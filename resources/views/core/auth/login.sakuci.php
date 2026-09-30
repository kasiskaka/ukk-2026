@extends('layouts.app')

@section('title', 'Login')

@section('content')

<style>
    /* Penyesuaian tema halaman login agar senada dengan sidebar pink & gold */
    .login-card {
        background-color: #fff5f8;
        border: 1px solid #f3d7e5 !important;
        border-radius: 12px;
    }
    
    [data-bs-theme="dark"] .login-card {
        background-color: #1f1a1d;
        border-color: #382d33 !important;
    }

    .btn-gold {
        background-color: #d4af37;
        border-color: #d4af37;
        color: #ffffff;
        transition: background-color 0.2s;
    }

    .btn-gold:hover {
        background-color: #c59b27;
        border-color: #c59b27;
        color: #ffffff;
    }

    .text-gold {
        color: #d4af37 !important;
    }
</style>

<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-5">
        <!-- Kartu Login dengan Nuansa Pink Soft & Gold -->
        <div class="card login-card shadow-sm p-2">
            <div class="card-body p-4">
                
                <!-- Header dengan Emoji Alat Komputer & Jaringan -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white shadow-sm border border-warning rounded-circle mb-2" style="width: 55px; height: 55px; font-size: 24px;">
                        🖥️
                    </div>
                    <h1 class="h4 mb-1 fw-bold text-gold">Masuk Sistem</h1>
                    <p class="text-muted small mb-0">Peminjaman Alat Jaringan & Komputer </p>
                </div>

                <div class="alert alert-light border small text-secondary py-2 mb-3 shadow-2">
                    💡 <strong>Akun Demo:</strong> <code>admin</code> &mdash; Password: <code class="text-dark">rahasia123</code>
                </div>

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" for="username">👤 Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control form-control-sm {{ errors()->has('username') ? 'is-invalid' : '' }}" autofocus placeholder="Masukkan username...">
                        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" for="password">🔑 Password</label>
                        <input type="password" id="password" name="password" class="form-control form-control-sm {{ errors()->has('password') ? 'is-invalid' : '' }}" placeholder="••••••••">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button class="btn btn-gold btn-sm w-100 py-2 fw-semibold shadow-sm rounded-pill mt-2" type="submit">
                        🚀 Masuk ke Dashboard
                    </button>
                </form>

                @php
                    $canRegister = false;
                    try {
                        $canRegister = \App\Models\Role::where('can_register', 1)->exists();
                    } catch (\Throwable $e) {
                        $canRegister = false;
                    }
                @endphp
                @if ($canRegister)
                    <p class="text-secondary small text-center mt-3 mb-0">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-gold fw-semibold text-decoration-none">Daftar di sini 📝</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection