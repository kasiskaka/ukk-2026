@extends('layouts.app')

@section('title', 'Login')

@section('content')

<style>
    /* Halaman login: layar penuh, tema soft pink + soft blue + semi gold */
    .login-page {
        --pk-50: #fff6fa;
        --pk-100: #ffe6f0;
        --pk-200: #fccfe0;
        --pk-400: #f59abb;
        --pk-500: #ec6a9a;
        --plum: #6d2b52;
        --plum-2: #a14577;
        --plum-soft: #98677f;
        --blue-100: #e0f0fd;
        --blue-200: #c6e2fa;
        --blue-400: #8fc4ee;
        --gold: #d9a441;
        --gold-2: #f3d58a;

        position: fixed;
        inset: 0;
        z-index: 99999;
        overflow-x: hidden;
        overflow-y: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
        color: var(--plum);
        background:
            radial-gradient(520px 320px at 100% 0%, rgba(243, 213, 138, .38), transparent 65%),
            radial-gradient(640px 380px at 0% 100%, rgba(143, 196, 238, .45), transparent 65%),
            linear-gradient(160deg, var(--pk-50), var(--pk-100) 55%, var(--pk-200));
    }

    .login-page * { box-sizing: border-box; }

    .login-page .login-card {
        display: grid;
        grid-template-columns: .95fr 1.05fr;
        width: 100%;
        max-width: 920px;
        overflow: hidden;
        border-radius: 28px;
        background: #ffffff;
        box-shadow: 0 30px 70px rgba(161, 69, 119, .22);
    }

    /* ---------- Sisi kiri: ilustrasi ---------- */
    .login-page .login-side {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 28px 0;
        text-align: center;
        background: linear-gradient(160deg, var(--pk-100), var(--pk-200) 60%, var(--blue-200));
    }

    .login-page .login-side h2 {
        margin: 0;
        color: var(--plum);
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .login-page .login-side p {
        margin: 8px 0 0;
        color: var(--plum-soft);
        font-size: .95rem;
        line-height: 1.6;
    }

    .login-page .login-art {
        display: block;
        width: 100%;
        max-width: 320px;
        height: auto;
        margin-top: auto;
    }

    .login-page .lg-wave {
        transform-box: fill-box;
        transform-origin: 0% 100%;
        animation: lg-wave 1.8s ease-in-out infinite;
    }

    @keyframes lg-wave {
        0%, 100% { transform: rotate(0deg); }
        50% { transform: rotate(-14deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        .login-page .lg-wave { animation: none; }
    }

    /* ---------- Sisi kanan: form ---------- */
    .login-page .login-main {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 44px 40px;
    }

    .login-page .login-header { margin-bottom: 26px; }

    .login-page .login-mark {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        margin-bottom: 16px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--gold-2), var(--gold));
        box-shadow: 0 10px 22px rgba(217, 164, 65, .35);
        color: var(--plum);
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: .02em;
    }

    .login-page .login-title {
        margin: 0;
        color: var(--plum);
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .login-page .login-subtitle {
        margin: 8px 0 0;
        color: var(--plum-soft);
        font-size: .98rem;
    }

    .login-page .login-field { margin-bottom: 16px; }

    .login-page .login-label {
        display: block;
        margin-bottom: 6px;
        color: var(--plum);
        font-size: 13.5px;
        font-weight: 700;
    }

    .login-page .login-input {
        display: block;
        width: 100%;
        padding: 13px 16px;
        border: 2px solid var(--pk-200);
        border-radius: 14px;
        background: #fff8fb;
        color: var(--plum);
        font-size: 15px;
        transition: border-color .15s, box-shadow .15s, background-color .15s;
    }

    .login-page .login-input::placeholder { color: #c09aae; }

    .login-page .login-input:focus {
        outline: none;
        border-color: var(--pk-500);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(236, 106, 154, .18);
    }

    .login-page .login-input.is-invalid {
        border-color: #d9534f;
        background-image: none;
    }

    .login-page .invalid-feedback {
        display: block;
        margin-top: 6px;
        color: #c0392b;
        font-size: 13px;
    }

    .login-page .login-btn {
        display: block;
        width: 100%;
        margin-top: 8px;
        padding: 14px;
        border: 0;
        border-radius: 999px;
        background: linear-gradient(120deg, var(--pk-500), var(--plum-2));
        box-shadow: 0 10px 24px rgba(236, 106, 154, .38);
        color: #ffffff;
        font-size: 15px;
        font-weight: 800;
        cursor: pointer;
        transition: transform .15s, box-shadow .15s;
    }

    .login-page .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(236, 106, 154, .45);
    }

    .login-page .login-foot {
        margin: 22px 0 0;
        color: var(--plum-soft);
        font-size: 14px;
        text-align: center;
    }

    .login-page .login-foot a,
    .login-page .login-back a {
        color: var(--plum-2);
        font-weight: 700;
        text-decoration: none;
    }

    .login-page .login-foot a:hover,
    .login-page .login-back a:hover { color: var(--pk-500); text-decoration: underline; }

    .login-page .login-back {
        margin: 12px 0 0;
        font-size: 13.5px;
        text-align: center;
    }

    /* ---------- Responsif ---------- */
    @media (max-width: 820px) {
        .login-page .login-card { grid-template-columns: 1fr; max-width: 460px; }
        .login-page .login-side { display: none; }
        .login-page .login-main { padding: 34px 24px; }
    }
</style>

<div class="login-page">
    <div class="login-card">

        {{-- Sisi kiri: sapaan + ilustrasi --}}
        <aside class="login-side" aria-hidden="true">
            <h2>Halo, selamat datang!</h2>
            <p>Masuk untuk meminjam alat Lab NET &amp; PC dengan rapi dan terstruktur.</p>

            <svg class="login-art" viewBox="0 0 300 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="lgBlazer" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#f58fb5"/>
                        <stop offset="1" stop-color="#d6558a"/>
                    </linearGradient>
                </defs>

                {{-- Latar lengkung --}}
                <path d="M30 250V120a120 120 0 0 1 240 0v130z" fill="#ffffff" fill-opacity=".6"/>
                <circle cx="150" cy="118" r="86" fill="none" stroke="#d9a441" stroke-width="2" stroke-dasharray="3 8" stroke-linecap="round"/>

                {{-- Dekorasi --}}
                <path d="M40 90c0-6 9-8 9 0 0-8 9-6 9 0 0 7-9 13-9 13s-9-6-9-13z" fill="#f59abb"/>
                <path d="M248 150c0-5 7-6 7 0 0-6 7-5 7 0 0 5-7 10-7 10s-7-5-7-10z" fill="#8fc4ee"/>
                <path d="M70 40l2.5 6 6 2.5-6 2.5-2.5 6-2.5-6-6-2.5 6-2.5z" fill="#f3d58a"/>
                <circle cx="262" cy="104" r="4" fill="#d9a441"/>

                {{-- Perempuan --}}
                <g transform="translate(-30 8)">
                    <path d="M136 124c-8 44 0 84 22 96l12-90z" fill="#4a2a3c"/>
                    <path d="M224 124c8 44 0 84-22 96l-12-90z" fill="#4a2a3c"/>

                    <path d="M126 254c0-52 20-80 54-80s54 28 54 80z" fill="url(#lgBlazer)"/>
                    <path d="M160 176l20 34 20-34" fill="#ffffff"/>
                    <path d="M180 210v44" stroke="#6d2b52" stroke-opacity=".3" stroke-width="2"/>
                    <circle cx="180" cy="228" r="2.5" fill="#f3d58a"/>
                    <circle cx="180" cy="242" r="2.5" fill="#f3d58a"/>
                    <path d="M168 182q12 12 24 0" stroke="#d9a441" stroke-width="2" stroke-linecap="round"/>

                    <rect x="170" y="156" width="20" height="24" rx="8" fill="#f1c9a8"/>
                    <circle cx="180" cy="122" r="35" fill="#f6d3b8"/>
                    <path d="M144 124c-4-38 28-52 46-46 22-2 44 14 38 46-6-18-20-28-38-28-16 0-40 4-46 28z" fill="#4a2a3c"/>

                    <circle cx="168" cy="126" r="2.8" fill="#4a2a3c"/>
                    <circle cx="192" cy="126" r="2.8" fill="#4a2a3c"/>
                    <path d="M163 120l-3-2M173 119l2-2.5M187 119l-2-2.5M197 120l3-2" stroke="#4a2a3c" stroke-width="1.6" stroke-linecap="round"/>
                    <path d="M170 138q10 9 20 0" stroke="#d6558a" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="158" cy="136" r="5" fill="#f58fb5" fill-opacity=".6"/>
                    <circle cx="202" cy="136" r="5" fill="#f58fb5" fill-opacity=".6"/>

                    {{-- Pita --}}
                    <path d="M214 88l-17-9v18z" fill="#f59abb"/>
                    <path d="M214 88l17-9v18z" fill="#f59abb"/>
                    <circle cx="214" cy="88" r="5" fill="#d9a441"/>

                    {{-- Headset --}}
                    <path d="M145 122c-2-34 20-48 36-48s38 14 36 48" stroke="#d9a441" stroke-width="5" stroke-linecap="round"/>
                    <rect x="139" y="116" width="10" height="22" rx="5" fill="#d6558a"/>
                    <rect x="211" y="116" width="10" height="22" rx="5" fill="#d6558a"/>
                    <path d="M144 136c0 14 14 20 26 18" stroke="#6d2b52" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="172" cy="154" r="3.5" fill="#d9a441"/>

                    {{-- Tangan --}}
                    <path d="M138 190c-10 18-12 36-10 52" stroke="#e66a9b" stroke-width="16" stroke-linecap="round"/>
                    <g class="lg-wave">
                        <path d="M222 190c22 0 36-20 40-52" stroke="#e66a9b" stroke-width="16" stroke-linecap="round"/>
                        <circle cx="263" cy="126" r="11" fill="#f6d3b8"/>
                        <path d="M257 118v-8M263 116v-10M269 118v-8" stroke="#f6d3b8" stroke-width="4" stroke-linecap="round"/>
                    </g>
                </g>

                {{-- Balon Hi! --}}
                <rect x="196" y="30" width="80" height="40" rx="20" fill="#f3d58a"/>
                <path d="M214 68l-6 16 18-10z" fill="#f3d58a"/>
                <text x="236" y="58" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="22" font-weight="800" fill="#6d2b52">Hi!</text>
            </svg>
        </aside>

        {{-- Sisi kanan: form login --}}
        <div class="login-main">

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

            <p class="login-back"><a href="/">&larr; Kembali ke beranda</a></p>

        </div>

    </div>
</div>

<script>
    (function () {
        if (!document.querySelector('.login-page')) return;
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
    })();
</script>

@endsection