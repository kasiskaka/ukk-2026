@extends('layouts.app')

@section('title', 'Admin')

@section('content')
<style>
    /*
      Warna dasar (background, teks, border) diambil dari variabel Bootstrap,
      jadi ikut gelap kalau layout gelap dan ikut terang kalau layout terang.
      Hanya pink & gold yang ditambah di sini.
    */
    .dash {
        --d-pink: #f2a7c3;
        --d-gold: #b8912f;
        --d-tint: color-mix(in srgb, var(--d-pink) 16%, var(--bs-body-bg));
        --d-tint-2: color-mix(in srgb, var(--d-pink) 28%, var(--bs-body-bg));
    }
    [data-bs-theme="dark"] .dash { --d-gold: #e2c064; --d-pink: #e58fb0; }
    @media (prefers-color-scheme: dark) {
        :root:not([data-bs-theme="light"]) .dash { --d-gold: #e2c064; --d-pink: #e58fb0; }
    }

    /* ---------- hero ---------- */
    .dash-hero {
        position: relative;
        overflow: hidden;
        background: var(--d-tint);
        border: 1px solid var(--bs-border-color);
        border-left: 5px solid var(--d-gold);
        border-radius: .9rem;
    }
    .dash-hero .card-body { padding: 2rem 2rem 2rem 2.2rem; position: relative; z-index: 1; }

    .dash-pill {
        display: inline-block;
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .08em;
        color: var(--d-gold);
        border: 1px solid var(--d-gold);
        border-radius: 999px;
        padding: .25rem .85rem;
        margin-bottom: .9rem;
    }
    .dash-hero h1 { font-weight: 700; margin-bottom: .4rem; }
    .dash-hero h1 .wave { display: inline-block; transform-origin: 70% 70%; animation: dash-wave 2.6s ease-in-out 1s 2; }
    .dash-hero p { max-width: 32rem; }
    .dash-hero code {
        background: var(--bs-body-bg);
        color: var(--d-gold);
        border: 1px solid var(--bs-border-color);
        border-radius: .35rem;
        padding: .05rem .4rem;
    }

    /* emoji peralatan, agak miring-miring biar ga kaku */
    .dash-gear { position: absolute; right: 1.5rem; top: 0; bottom: 0; width: 15rem; pointer-events: none; }
    .dash-gear span {
        position: absolute;
        display: grid; place-items: center;
        width: 3.6rem; height: 3.6rem;
        font-size: 1.8rem;
        border-radius: 50%;
        background: var(--bs-body-bg);
        border: 1px solid var(--d-gold);
        box-shadow: 0 6px 14px rgba(0,0,0,.14);
        animation: dash-float 5s ease-in-out infinite;
    }
    .dash-gear span:nth-child(1) { top: 14%; right: 4.5rem; width: 4.6rem; height: 4.6rem; font-size: 2.3rem; --r: -6deg; }
    .dash-gear span:nth-child(2) { top: 52%; right: 8.5rem; animation-delay: -1.5s; --r: 8deg; }
    .dash-gear span:nth-child(3) { top: 22%; right: 0;      animation-delay: -3s;   --r: 5deg; width: 3rem; height: 3rem; font-size: 1.4rem; }
    .dash-gear span:nth-child(4) { top: 62%; right: 1rem;   animation-delay: -4s;   --r: -8deg; }

    @keyframes dash-float {
        0%, 100% { transform: translateY(0) rotate(var(--r, 0deg)); }
        50%      { transform: translateY(-9px) rotate(calc(var(--r, 0deg) * -1)); }
    }
    @keyframes dash-wave {
        0%, 60%, 100% { transform: rotate(0); }
        10%, 30% { transform: rotate(14deg); }
        20%, 40% { transform: rotate(-8deg); }
        50% { transform: rotate(10deg); }
    }

    /* ---------- kartu menu ---------- */
    .dash-card {
        display: block;
        height: 100%;
        color: inherit;
        text-decoration: none;
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: .9rem;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .dash-card:hover {
        color: inherit;
        transform: translateY(-4px);
        border-color: var(--d-gold);
        box-shadow: 0 .6rem 1.4rem rgba(0,0,0,.12);
    }
    .dash-card:focus-visible { outline: 2px solid var(--d-gold); outline-offset: 2px; }

    .dash-ico {
        display: grid; place-items: center;
        width: 3.2rem; height: 3.2rem;
        font-size: 1.6rem;
        background: var(--d-tint-2);
        border-radius: .7rem;
        margin-bottom: 1rem;
        transition: transform .25s ease;
    }
    .dash-card:hover .dash-ico { transform: rotate(-8deg) scale(1.08); }

    .dash-card h2 { font-size: 1.05rem; font-weight: 600; margin-bottom: .25rem; }
    .dash-card p  { color: var(--bs-secondary-color); font-size: .88rem; margin-bottom: 1rem; }

    .dash-more {
        font-size: .82rem;
        font-weight: 600;
        color: var(--bs-emphasis-color);
        background-image: linear-gradient(var(--d-gold), var(--d-gold));
        background-repeat: no-repeat;
        background-position: 0 100%;
        background-size: 30% 2px;
        padding-bottom: 2px;
        transition: background-size .3s ease;
    }
    .dash-card:hover .dash-more { background-size: 100% 2px; }

    .dash-bob { display: inline-block; animation: dash-bob 2.2s ease-in-out infinite; }
    @keyframes dash-bob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }

    @media (max-width: 767.98px) {
        .dash-gear { opacity: .25; }
        .dash-hero .card-body { padding: 1.5rem 1.3rem 1.5rem 1.5rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .dash *, .dash *::before, .dash *::after { animation: none !important; transition: none !important; }
    }
</style>

<div class="dash">

    <div class="card dash-hero shadow-sm mb-4">
        <div class="dash-gear" aria-hidden="true">
            <span>💻</span>
            <span>🖱️</span>
            <span>⌨️</span>
            <span>🎧</span>
        </div>
        <div class="card-body">
            <span class="dash-pill">HALLO ADMIN</span>
            <h1 class="h3">Halo, {{ $user->username }} <span class="wave">👋</span></h1>
            <p class="text-secondary mb-0">
                Halaman ini hanya bisa diakses role <code>admin</code> (middleware <code>admin</code>).
            </p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.roles.index') }}" class="dash-card">
                <div class="p-4">
                    <div class="dash-ico">⌨️</div>
                    <h2>Manage Role</h2>
                    <p>Tambah role baru untuk dipakai saat membuat user.</p>
                    <span class="dash-more">Buka</span>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.users.index') }}" class="dash-card">
                <div class="p-4">
                    <div class="dash-ico">🖱️</div>
                    <h2>Manage User</h2>
                    <p>Tambah user baru dan tentukan role-nya.</p>
                    <span class="dash-more">Buka</span>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.database.export') }}" class="dash-card">
                <div class="p-4">
                    <div class="dash-ico"><span class="dash-bob">💾</span></div>
                    <h2>Download Database</h2>
                    <p>Unduh seluruh isi database jadi satu file .sql, siap diimpor di server.</p>
                    <span class="dash-more">Unduh</span>
                </div>
            </a>
        </div>
    </div>

</div>
@endsection