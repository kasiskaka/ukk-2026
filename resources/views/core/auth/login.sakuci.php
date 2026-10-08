@extends('layouts.app')

@section('title', 'Login')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<div class="login-page">
    <div class="login-card">

        <div class="login-header">
            <div class="login-mark" aria-hidden="true">NP</div>
            <h1 class="login-title">Masuk</h1>
            <p class="login-subtitle">Peminjaman alat jaringan &amp; komputer</p>
        </div>

        <form method="POST" action="{{ route('login.attempt') }}" novalidate>
            @csrf

            <div class="login-field">
                <label class="login-label" for="username">Username</label>
                <input type="text"
                       id="username"
                       name="username"
                       value="{{ old('username') }}"
                       class="form-control login-input {{ errors()->has('username') ? 'is-invalid' : '' }}"
                       placeholder="Masukkan username"
                       autocomplete="username"
                       autofocus>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="login-field">
                <label class="login-label" for="password">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control login-input {{ errors()->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Masukkan password"
                       autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="login-btn">Masuk</button>
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
            <p class="login-foot">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar di sini</a>
            </p>
        @endif

    </div>
</div>

@endsection