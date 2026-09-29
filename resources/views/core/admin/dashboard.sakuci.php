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

{{-- ================= TAMBAHAN CSS (pink soft + gold + abu, alat bergerak, fitur baru) ================= --}}
<style>
    .dash {
        --d-gray: #8b8d94;
        --d-gray-t:  color-mix(in srgb, var(--d-gray) 14%, var(--bs-body-bg));
        --d-gray-t2: color-mix(in srgb, var(--d-gray) 26%, var(--bs-body-bg));
        --d-ease: cubic-bezier(.22, .8, .3, 1);
    }
    [data-bs-theme="dark"] .dash { --d-gray: #a3a5ad; }
    @media (prefers-color-scheme: dark) {
        :root:not([data-bs-theme="light"]) .dash { --d-gray: #a3a5ad; }
    }

    /* sentuhan abu di hero */
    .dash-hero {
        background:
            radial-gradient(circle at 80% 20%, var(--d-tint-2), transparent 55%),
            linear-gradient(120deg, var(--d-tint), var(--d-gray-t));
        min-height: 14rem;
    }
    .dash-hero::after {
        content: "";
        position: absolute; inset: 0;
        background-image: radial-gradient(color-mix(in srgb, var(--d-gray) 35%, transparent) 1px, transparent 1px);
        background-size: 18px 18px;
        opacity: .35;
        pointer-events: none;
        -webkit-mask-image: linear-gradient(90deg, transparent 30%, #000);
        mask-image: linear-gradient(90deg, transparent 30%, #000);
    }

    /* jam live */
    .dash-clock {
        display: inline-flex; align-items: center; gap: .5rem;
        margin-top: 1rem;
        font-size: .82rem;
        color: var(--bs-secondary-color);
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 999px;
        padding: .3rem .8rem;
    }
    .dash-clock .dot { width: .5rem; height: .5rem; border-radius: 50%; background: #3fb37f; animation: dash-pulse 1.8s ease-in-out infinite; }
    .dash-clock b { font-variant-numeric: tabular-nums; color: var(--bs-emphasis-color); }
    @keyframes dash-pulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(63,179,127,.5); } 50% { box-shadow: 0 0 0 6px rgba(63,179,127,0); } }

    /* alat komputer & jaringan (SVG) di hero, di sebelah kiri emoji */
    .dash-devices { position: absolute; right: 16rem; top: 0; bottom: 0; width: 20rem; pointer-events: none; z-index: 1; display: none; }
    @media (min-width: 1200px) { .dash-devices { display: block; } }
    .dash-devices .dv { position: absolute; will-change: transform; }
    .dash-devices .dv > svg { display: block; width: 100%; height: auto; overflow: visible; filter: drop-shadow(0 8px 10px rgba(0,0,0,.18)); animation: dash-float 5s ease-in-out infinite; }
    .dv-laptop { top: 16%; right: 5.5rem; width: 11rem; }
    .dv-router { top: 6%;  right: 0;      width: 5.6rem; }
    .dv-router > svg { animation-delay: -1.5s; --r: 3deg; }
    .dv-switch { bottom: 10%; right: 0;   width: 8.4rem; }
    .dv-switch > svg { animation-delay: -3s; --r: -2deg; }
    .dv-tower  { bottom: 9%; right: 10.5rem; width: 3.2rem; }
    .dv-tower > svg { animation-delay: -4s; --r: 4deg; }
    .dv-laptop > svg { --r: -2deg; }

    .sv-body   { fill: var(--d-gray-t2); stroke: var(--d-gray); stroke-width: 2; }
    .sv-dark   { fill: color-mix(in srgb, var(--d-gray) 70%, #1b1b1f); }
    .sv-screen { fill: var(--d-tint-2); stroke: var(--d-gold); stroke-width: 1.5; }
    .sv-gold   { fill: var(--d-gold); }
    .sv-pink   { fill: var(--d-pink); }
    .sv-line   { stroke: var(--d-gold); stroke-width: 2; stroke-linecap: round; fill: none; }
    .sv-ant    { stroke: var(--d-gray); stroke-width: 4; stroke-linecap: round; fill: none; }
    .sv-led    { animation: dash-blink 1.6s steps(2, jump-none) infinite; }
    .sv-led.l2 { animation-delay: -.5s; }
    .sv-led.l3 { animation-delay: -1s; }
    .sv-code   { stroke-dasharray: 60; animation: dash-type 4s ease-in-out infinite; }
    .sv-code.c2 { animation-delay: .5s; }
    .sv-code.c3 { animation-delay: 1s; }
    .sv-wave   { fill: none; stroke: var(--d-gold); stroke-width: 2; stroke-linecap: round; opacity: 0; animation: dash-wifi 2.4s ease-out infinite; }
    .sv-wave.w2 { animation-delay: .5s; }
    .sv-wave.w3 { animation-delay: 1s; }
    @keyframes dash-blink { 0% { opacity: 1; } 100% { opacity: .2; } }
    @keyframes dash-type  { 0%, 100% { stroke-dashoffset: 60; } 45%, 80% { stroke-dashoffset: 0; } }
    @keyframes dash-wifi  { 0% { opacity: 0; } 30% { opacity: .9; } 100% { opacity: 0; } }

    /* muncul pelan saat scroll (kelas ditambah oleh JS, jadi aman kalau JS mati) */
    .dash .dash-rv { opacity: 0; transform: translateY(18px); transition: opacity .6s var(--d-ease), transform .6s var(--d-ease); }
    .dash .dash-rv.is-in { opacity: 1; transform: none; }

    /* cahaya pink yang ikut kursor di kartu */
    .dash-card { position: relative; overflow: hidden; }
    .dash-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(260px circle at var(--gx, 50%) var(--gy, 0%), color-mix(in srgb, var(--d-pink) 30%, transparent), transparent 70%);
        opacity: 0; transition: opacity .3s ease;
    }
    .dash-card:hover::before { opacity: 1; }
    .dash-card > .p-4 { position: relative; }
    .dash-ico.gray { background: var(--d-gray-t2); }
    .dash-tag {
        position: absolute; top: .9rem; right: .9rem; z-index: 1;
        font-size: .68rem; font-weight: 600; letter-spacing: .06em;
        color: var(--bs-secondary-color);
        background: var(--d-gray-t);
        border: 1px solid var(--bs-border-color);
        border-radius: 999px; padding: .1rem .55rem;
    }

    /* strip info */
    .dash-stat {
        background: var(--d-gray-t);
        border: 1px solid var(--bs-border-color);
        border-radius: .8rem;
        padding: .9rem 1.1rem;
        height: 100%;
        transition: transform .25s var(--d-ease), border-color .25s ease;
    }
    .dash-stat:hover { transform: translateY(-3px); border-color: var(--d-gold); }
    .dash-stat small { display: block; color: var(--bs-secondary-color); font-size: .74rem; letter-spacing: .05em; text-transform: uppercase; }
    .dash-stat strong { font-size: 1.25rem; font-variant-numeric: tabular-nums; }
    .dash-stat .num { color: var(--d-gold); }

    /* judul bagian */
    .dash-head { display: flex; align-items: center; gap: .7rem; margin: 2.2rem 0 1rem; }
    .dash-head::before { content: ""; width: 5px; height: 1.5rem; border-radius: 3px; background: var(--d-gold); }
    .dash-head h3 { font-size: 1.15rem; font-weight: 700; margin: 0; }
    .dash-head::after { content: ""; flex: 1; height: 1px; background: linear-gradient(90deg, var(--bs-border-color), transparent); }

    /* katalog alat */
    .dash-filter { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1rem; }
    .dash-filter button {
        border: 1px solid var(--bs-border-color);
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
        border-radius: 999px;
        padding: .3rem .95rem;
        font-size: .82rem;
        transition: all .25s var(--d-ease);
    }
    .dash-filter button:hover { border-color: var(--d-gold); transform: translateY(-1px); }
    .dash-filter button.on { background: var(--d-gold); border-color: var(--d-gold); color: #fff; }
    .dash-tool {
        display: flex; align-items: center; gap: .8rem;
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: .8rem;
        padding: .75rem .9rem;
        transition: opacity .3s ease, transform .3s var(--d-ease), border-color .25s ease;
    }
    .dash-tool:hover { border-color: var(--d-pink); transform: translateY(-2px); }
    .dash-tool .em {
        display: grid; place-items: center; flex: none;
        width: 2.6rem; height: 2.6rem; font-size: 1.3rem;
        border-radius: .6rem; background: var(--d-tint-2);
    }
    .dash-tool[data-cat="jaringan"] .em { background: var(--d-gray-t2); }
    .dash-tool[data-cat="perkakas"] .em { background: color-mix(in srgb, var(--d-gold) 22%, var(--bs-body-bg)); }
    .dash-tool b { display: block; font-size: .92rem; }
    .dash-tool small { color: var(--bs-secondary-color); }
    .dash-tool.is-hide { opacity: 0; transform: scale(.94); pointer-events: none; }

    /* catatan */
    .dash-note { background: var(--d-gray-t); border: 1px dashed var(--d-gray); border-radius: .9rem; padding: 1.2rem 1.4rem; }
    .dash-note ul { margin: 0; padding-left: 1.1rem; }
    .dash-note li { margin-bottom: .35rem; color: var(--bs-secondary-color); font-size: .9rem; }
    .dash-note li::marker { color: var(--d-gold); }
</style>

<div class="dash">

    <div class="card dash-hero shadow-sm mb-4" id="dashHero">
        <div class="dash-gear" aria-hidden="true">
            <span>💻</span>
            <span>🖱️</span>
            <span>⌨️</span>
            <span>🎧</span>
        </div>

        {{-- TAMBAHAN: ilustrasi alat komputer dan jaringan yang bergerak --}}
        <div class="dash-devices" aria-hidden="true">
            <div class="dv dv-laptop" data-depth="18">
                <svg viewBox="0 0 200 130" xmlns="http://www.w3.org/2000/svg">
                    <rect class="sv-body" x="30" y="8" width="140" height="92" rx="8"/>
                    <rect class="sv-screen" x="38" y="16" width="124" height="76" rx="4"/>
                    <path class="sv-line sv-code" d="M50 34h50"/>
                    <path class="sv-line sv-code c2" d="M50 48h74"/>
                    <path class="sv-line sv-code c3" d="M50 62h38"/>
                    <circle class="sv-pink" cx="140" cy="70" r="9"/>
                    <path class="sv-body" d="M8 104h184l-10 12a6 6 0 0 1-5 3H23a6 6 0 0 1-5-3z"/>
                    <rect class="sv-dark" x="80" y="106" width="40" height="4" rx="2"/>
                </svg>
            </div>
            <div class="dv dv-router" data-depth="30">
                <svg viewBox="0 0 160 110" xmlns="http://www.w3.org/2000/svg">
                    <path class="sv-ant" d="M34 58L22 12M126 58l12-46"/>
                    <path class="sv-wave" d="M64 26a22 22 0 0 1 32 0"/>
                    <path class="sv-wave w2" d="M54 16a36 36 0 0 1 52 0"/>
                    <path class="sv-wave w3" d="M44 6a50 50 0 0 1 72 0"/>
                    <rect class="sv-body" x="8" y="58" width="144" height="42" rx="10"/>
                    <circle class="sv-led sv-gold" cx="34" cy="79" r="4"/>
                    <circle class="sv-led l2 sv-pink" cx="52" cy="79" r="4"/>
                    <circle class="sv-led l3 sv-gold" cx="70" cy="79" r="4"/>
                    <rect class="sv-dark" x="100" y="72" width="10" height="14" rx="2"/>
                    <rect class="sv-dark" x="116" y="72" width="10" height="14" rx="2"/>
                    <rect class="sv-dark" x="132" y="72" width="10" height="14" rx="2"/>
                </svg>
            </div>
            <div class="dv dv-switch" data-depth="24">
                <svg viewBox="0 0 180 50" xmlns="http://www.w3.org/2000/svg">
                    <rect class="sv-body" x="2" y="4" width="176" height="42" rx="6"/>
                    <g class="sv-dark">
                        <rect x="16" y="20" width="14" height="14" rx="2"/>
                        <rect x="36" y="20" width="14" height="14" rx="2"/>
                        <rect x="56" y="20" width="14" height="14" rx="2"/>
                        <rect x="76" y="20" width="14" height="14" rx="2"/>
                        <rect x="96" y="20" width="14" height="14" rx="2"/>
                        <rect x="116" y="20" width="14" height="14" rx="2"/>
                        <rect x="136" y="20" width="14" height="14" rx="2"/>
                    </g>
                    <circle class="sv-led sv-gold" cx="23" cy="13" r="2.5"/>
                    <circle class="sv-led l2 sv-pink" cx="43" cy="13" r="2.5"/>
                    <circle class="sv-led l3 sv-gold" cx="63" cy="13" r="2.5"/>
                    <circle class="sv-led sv-pink" cx="83" cy="13" r="2.5"/>
                    <circle class="sv-led l2 sv-gold" cx="103" cy="13" r="2.5"/>
                    <circle class="sv-led l3 sv-pink" cx="123" cy="13" r="2.5"/>
                    <circle class="sv-led sv-gold" cx="143" cy="13" r="2.5"/>
                </svg>
            </div>
            <div class="dv dv-tower" data-depth="14">
                <svg viewBox="0 0 70 120" xmlns="http://www.w3.org/2000/svg">
                    <rect class="sv-body" x="4" y="4" width="62" height="112" rx="8"/>
                    <rect class="sv-dark" x="14" y="16" width="42" height="7" rx="2"/>
                    <rect class="sv-dark" x="14" y="29" width="42" height="7" rx="2"/>
                    <circle class="sv-pink" cx="35" cy="66" r="11"/>
                    <circle class="sv-led sv-gold" cx="35" cy="66" r="4"/>
                    <path class="sv-line" d="M18 98h34"/>
                </svg>
            </div>
        </div>

        <div class="card-body">
            <span class="dash-pill">HALLO ADMIN</span>
            <h1 class="h3">Halo, {{ $user->username }} <span class="wave">👋</span></h1>
            <p class="text-secondary mb-0">
                Halaman ini hanya bisa diakses role <code>admin</code> (middleware <code>admin</code>).
            </p>
            {{-- TAMBAHAN: jam live --}}
            <div class="dash-clock">
                <span class="dot"></span>
                <span id="dashDate">-</span> · <b id="dashTime">--:--:--</b>
            </div>
        </div>
    </div>

    {{-- TAMBAHAN: strip info --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="dash-stat"><small>Akses</small><strong>Admin</strong></div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dash-stat"><small>Menu tersedia</small><strong class="num" data-count="6">6</strong></div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dash-stat"><small>Kategori alat</small><strong class="num" data-count="3">3</strong></div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dash-stat"><small>Status sistem</small><strong>Aktif</strong></div>
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

    {{-- ================= TAMBAHAN: menu peminjaman alat =================
         Link-nya sengaja "#" supaya tidak error. Kalau route-nya sudah dibuat,
         tinggal ganti href="#" dengan href="{{ route('nama.route') }}". --}}
    <div class="dash-head"><h3>Peminjaman Alat</h3></div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <a href="#" class="dash-card">
                <span class="dash-tag">BARU</span>
                <div class="p-4">
                    <div class="dash-ico gray">🖥️</div>
                    <h2>Data Alat</h2>
                    <p>Kelola daftar laptop, PC, router, switch, dan alat jaringan lainnya.</p>
                    <span class="dash-more">Buka</span>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="#" class="dash-card">
                <span class="dash-tag">BARU</span>
                <div class="p-4">
                    <div class="dash-ico gray">📋</div>
                    <h2>Peminjaman</h2>
                    <p>Catat peminjaman dan pengembalian alat beserta tanggal jatuh temponya.</p>
                    <span class="dash-more">Buka</span>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="#" class="dash-card">
                <span class="dash-tag">BARU</span>
                <div class="p-4">
                    <div class="dash-ico gray">📊</div>
                    <h2>Laporan</h2>
                    <p>Rekap riwayat peminjaman, alat paling sering dipakai, dan keterlambatan.</p>
                    <span class="dash-more">Buka</span>
                </div>
            </a>
        </div>
    </div>

    {{-- ================= TAMBAHAN: katalog alat ================= --}}
    <div class="dash-head"><h3>Katalog Alat</h3></div>

    <div class="dash-filter" id="dashFilter">
        <button type="button" class="on" data-f="all">Semua</button>
        <button type="button" data-f="komputer">Komputer</button>
        <button type="button" data-f="jaringan">Jaringan</button>
        <button type="button" data-f="perkakas">Perkakas</button>
    </div>

    <div class="row g-3">
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="komputer"><span class="em">💻</span><div><b>Laptop</b><small>Komputer</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="komputer"><span class="em">🖥️</span><div><b>PC Desktop</b><small>Komputer</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="komputer"><span class="em">🖱️</span><div><b>Mouse dan Keyboard</b><small>Komputer</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="komputer"><span class="em">📽️</span><div><b>Proyektor</b><small>Komputer</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="jaringan"><span class="em">📡</span><div><b>Router Mikrotik</b><small>Jaringan</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="jaringan"><span class="em">🔀</span><div><b>Switch 8/24 Port</b><small>Jaringan</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="jaringan"><span class="em">📶</span><div><b>Access Point</b><small>Jaringan</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="jaringan"><span class="em">🧵</span><div><b>Kabel UTP</b><small>Jaringan</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="perkakas"><span class="em">🗜️</span><div><b>Crimping Tool</b><small>Perkakas</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="perkakas"><span class="em">🔌</span><div><b>LAN Tester</b><small>Perkakas</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="perkakas"><span class="em">🪛</span><div><b>Obeng Set</b><small>Perkakas</small></div></div></div>
        <div class="col-6 col-lg-3 dash-tool-col"><div class="dash-tool" data-cat="perkakas"><span class="em">✂️</span><div><b>Tang Potong Kabel</b><small>Perkakas</small></div></div></div>
    </div>

    {{-- ================= TAMBAHAN: catatan admin ================= --}}
    <div class="dash-head"><h3>Catatan Admin</h3></div>
    <div class="dash-note mb-4">
        <ul>
            <li>Buat role dulu di Manage Role, baru tambah user di Manage User.</li>
            <li>Rutin unduh database lewat Download Database sebagai cadangan.</li>
            <li>Cek alat yang jatuh tempo setiap hari supaya stok tidak menumpuk di peminjam.</li>
            <li>Kalau sudah punya foto asli alat lab, ganti gambar SVG di bagian atas dengan tag img.</li>
        </ul>
    </div>

</div>

{{-- TAMBAHAN: JavaScript (jam, animasi muncul, parallax, glow kartu, filter katalog) --}}
<script>
(function () {
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* jam dan tanggal live */
    var elT = document.getElementById('dashTime');
    var elD = document.getElementById('dashDate');
    function tick() {
        var n = new Date();
        if (elT) elT.textContent = n.toLocaleTimeString('id-ID', { hour12: false });
        if (elD) elD.textContent = n.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }
    tick();
    setInterval(tick, 1000);

    /* muncul pelan saat scroll + angka menghitung naik */
    function countUp(el) {
        var to = parseInt(el.getAttribute('data-count'), 10) || 0, t0 = null;
        function step(ts) {
            if (!t0) t0 = ts;
            var p = Math.min((ts - t0) / 900, 1);
            el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
    var targets = document.querySelectorAll('.dash .dash-card, .dash .dash-stat, .dash .dash-tool-col, .dash .dash-head, .dash .dash-note, .dash .dash-filter');
    if ('IntersectionObserver' in window && !reduce) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                e.target.classList.add('is-in');
                var nums = e.target.querySelectorAll('[data-count]');
                for (var i = 0; i < nums.length; i++) countUp(nums[i]);
                io.unobserve(e.target);
            });
        }, { threshold: 0.1 });
        targets.forEach(function (el, i) {
            el.classList.add('dash-rv');
            el.style.transitionDelay = ((i % 3) * 70) + 'ms';
            io.observe(el);
        });
    }

    /* parallax halus untuk alat di hero */
    var hero = document.getElementById('dashHero');
    var items = hero ? hero.querySelectorAll('.dv') : [];
    if (hero && items.length && !reduce) {
        var tx = 0, ty = 0, cx = 0, cy = 0, running = false;
        var loop = function () {
            cx += (tx - cx) * 0.08;
            cy += (ty - cy) * 0.08;
            for (var i = 0; i < items.length; i++) {
                var d = parseFloat(items[i].getAttribute('data-depth')) || 20;
                items[i].style.transform = 'translate3d(' + (cx * -d).toFixed(2) + 'px,' + (cy * -d).toFixed(2) + 'px,0)';
            }
            if (Math.abs(tx - cx) > 0.001 || Math.abs(ty - cy) > 0.001) requestAnimationFrame(loop);
            else running = false;
        };
        hero.addEventListener('mousemove', function (e) {
            var r = hero.getBoundingClientRect();
            tx = (e.clientX - r.left) / r.width - 0.5;
            ty = (e.clientY - r.top) / r.height - 0.5;
            if (!running) { running = true; requestAnimationFrame(loop); }
        });
        hero.addEventListener('mouseleave', function () {
            tx = 0; ty = 0;
            if (!running) { running = true; requestAnimationFrame(loop); }
        });
    }

    /* cahaya pink yang mengikuti kursor di kartu */
    document.querySelectorAll('.dash .dash-card').forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            var r = card.getBoundingClientRect();
            card.style.setProperty('--gx', (((e.clientX - r.left) / r.width) * 100) + '%');
            card.style.setProperty('--gy', (((e.clientY - r.top) / r.height) * 100) + '%');
        });
    });

    /* filter katalog alat */
    var fWrap = document.getElementById('dashFilter');
    if (fWrap) {
        fWrap.addEventListener('click', function (e) {
            var b = e.target.closest ? e.target.closest('button') : null;
            if (!b) return;
            var btns = fWrap.querySelectorAll('button');
            for (var i = 0; i < btns.length; i++) btns[i].classList.toggle('on', btns[i] === b);
            var f = b.getAttribute('data-f');
            document.querySelectorAll('.dash-tool-col').forEach(function (col) {
                var tool = col.querySelector('.dash-tool');
                var ok = f === 'all' || tool.getAttribute('data-cat') === f;
                if (ok) {
                    col.style.display = '';
                    requestAnimationFrame(function () { tool.classList.remove('is-hide'); });
                } else {
                    tool.classList.add('is-hide');
                    setTimeout(function () { if (tool.classList.contains('is-hide')) col.style.display = 'none'; }, 300);
                }
            });
        });
    }
})();
</script>
@endsection