@extends('layouts.app')

@section('title', 'Peminjaman Alat Jaringan & Komputer')

@section('content')

<style>
    /* ===== Override layout: navbar & background halaman jadi soft pink ===== */
    html, body {
        background: linear-gradient(180deg, #fff3f8 0%, #ffe3ee 55%, #e6f2fd 100%) fixed !important;
        color: #6d2b52;
    }

    /* Sembunyikan navbar bawaan layout di halaman ini
       (halaman home sudah punya bar sendiri di dalam .lp) */
    body > nav,
    body > header,
    body > .navbar {
        display: none !important;
    }

    .lp {
        /* Soft pink */
        --sky-50: #fff6fa;
        --sky-100: #ffe6f0;
        --sky-200: #fccfe0;
        --sky-400: #f59abb;
        --sky-500: #ec6a9a;
        /* Soft blue */
        --blue-50: #f1f8ff;
        --blue-100: #e0f0fd;
        --blue-200: #c6e2fa;
        --blue-400: #8fc4ee;
        /* Plum (pengganti navy) */
        --navy: #6d2b52;
        --navy-2: #a14577;
        --navy-soft: #98677f;
        /* Semi gold */
        --gold: #d9a441;
        --gold-2: #f3d58a;
        --white: #ffffff;
        --line: rgba(109, 43, 82, .14);

        /* Layar penuh: menutupi seluruh layout di belakangnya */
        position: fixed;
        inset: 0;
        z-index: 99999;
        overflow-x: hidden;
        overflow-y: auto;
        margin: 0;
        padding: 0;
        color: var(--navy);
        background: linear-gradient(160deg, var(--sky-50), var(--sky-100) 55%, var(--sky-200));
    }

    .lp * { box-sizing: border-box; }

    .lp .lp-shell {
        position: relative;
        overflow: hidden;
        min-height: 100%;
        border-radius: 0;
        background:
            radial-gradient(520px 320px at 100% 0%, rgba(243, 213, 138, .38), transparent 65%),
            radial-gradient(640px 380px at 0% 100%, rgba(143, 196, 238, .45), transparent 65%),
            linear-gradient(160deg, var(--sky-50), var(--sky-100) 55%, var(--sky-200));
        box-shadow: none;
    }

    /* ---------- Top bar ---------- */
    .lp .lp-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px max(24px, calc((100% - 1200px) / 2));
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(120deg, var(--navy), var(--navy-2));
        color: var(--white);
    }

    .lp .lp-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        letter-spacing: .01em;
    }

    .lp .lp-brand-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--gold-2), var(--gold));
        color: var(--navy);
    }

    .lp .lp-brand-mark svg { width: 22px; height: 22px; }

    .lp .lp-brand small {
        display: block;
        color: var(--sky-200);
        font-size: 11.5px;
        font-weight: 500;
        letter-spacing: .04em;
    }

    .lp .lp-bar-links { display: flex; gap: 8px; }

    .lp .lp-bar-links a {
        padding: 8px 18px;
        border-radius: 999px;
        color: var(--white);
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background-color .15s;
    }

    .lp .lp-bar-links a:hover { background: rgba(255, 255, 255, .16); color: var(--white); }

    .lp .lp-bar-links a.gold { background: var(--gold-2); color: var(--navy); }
    .lp .lp-bar-links a.gold:hover { background: var(--white); color: var(--navy); }

    /* ---------- Hero ---------- */
    .lp .lp-hero {
        display: grid;
        grid-template-columns: 1.05fr .95fr;
        align-items: center;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 56px 48px 40px;
    }

    .lp .lp-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 16px;
        border-radius: 999px;
        background: linear-gradient(120deg, var(--sky-500), var(--navy-2));
        color: var(--white);
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .03em;
    }

    .lp .lp-pill::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--gold-2);
        box-shadow: 0 0 0 4px rgba(243, 213, 138, .45);
    }

    .lp .lp-pill.light {
        background: var(--white);
        color: var(--navy-2);
        border: 1px solid var(--sky-200);
    }

    .lp .lp-title {
        margin: 20px 0 0;
        color: var(--navy);
        font-size: clamp(2rem, 4.4vw, 3.1rem);
        font-weight: 800;
        line-height: 1.12;
        letter-spacing: -.01em;
    }

    /* Nama kelas "hl" (bukan "mark") supaya tidak bentrok dengan Bootstrap .mark */
    .lp .lp-title .hl {
        color: var(--navy);
        padding: 0 6px;
        background: linear-gradient(transparent 58%, var(--gold-2) 58%, var(--gold-2) 92%, transparent 92%);
    }

    .lp .lp-title .grad {
        background: linear-gradient(90deg, var(--sky-500), var(--blue-400));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .lp .lp-desc {
        max-width: 500px;
        margin: 18px 0 0;
        color: var(--navy-soft);
        font-size: 1.04rem;
        line-height: 1.75;
    }

    .lp .lp-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .lp .lp-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 24px;
        border: 2px solid var(--sky-500);
        border-radius: 999px;
        background: transparent;
        color: var(--navy-2);
        font-weight: 700;
        text-decoration: none;
        transition: transform .15s, box-shadow .15s, background-color .15s;
    }

    .lp .lp-btn svg { width: 17px; height: 17px; }

    .lp .lp-btn:hover {
        transform: translateY(-2px);
        background: rgba(236, 106, 154, .1);
        color: var(--navy-2);
    }

    .lp .lp-btn-navy {
        border-color: transparent;
        background: linear-gradient(120deg, var(--sky-500), var(--navy-2));
        color: var(--white);
        box-shadow: 0 10px 24px rgba(236, 106, 154, .38);
    }

    .lp .lp-btn-navy:hover {
        background: linear-gradient(120deg, #f07aa6, #b34f84);
        color: var(--white);
    }

    .lp .lp-btn-gold {
        border-color: var(--gold);
        background: linear-gradient(120deg, var(--gold-2), var(--gold));
        color: var(--navy);
        box-shadow: 0 10px 24px rgba(217, 164, 65, .35);
    }

    .lp .lp-btn-gold:hover { background: var(--gold-2); color: var(--navy); }

    /* Statistik ringkas */
    .lp .lp-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0;
        margin-top: 34px;
    }

    .lp .lp-stat { padding-right: 24px; margin-right: 24px; border-right: 1px solid var(--line); }
    .lp .lp-stat:last-child { border-right: 0; margin-right: 0; padding-right: 0; }

    .lp .lp-stat b {
        display: block;
        color: var(--navy);
        font-size: 1.7rem;
        font-weight: 800;
        line-height: 1;
    }

    .lp .lp-stat b span { color: var(--gold); }

    .lp .lp-stat small {
        display: block;
        margin-top: 6px;
        color: var(--navy-soft);
        font-size: 12.5px;
        font-weight: 600;
    }

    /* ---------- Panggung ilustrasi ---------- */
    .lp .lp-stage {
        position: relative;
        width: 100%;
        max-width: 430px;
        margin: 0 auto;
    }

    .lp .lp-art { display: block; width: 100%; height: auto; filter: drop-shadow(0 22px 30px rgba(161, 69, 119, .25)); }

    .lp .lp-float {
        position: absolute;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 16px 9px 10px;
        border-radius: 999px;
        background: var(--white);
        box-shadow: 0 12px 28px rgba(161, 69, 119, .2);
        color: var(--navy);
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        animation: lp-bob 4s ease-in-out infinite;
    }

    .lp .lp-float i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--sky-400), var(--sky-500));
        color: var(--white);
        font-size: 12px;
        font-style: normal;
        font-weight: 800;
    }

    .lp .lp-float.f2 i { background: linear-gradient(135deg, var(--blue-400), #6fb0e6); }
    .lp .lp-float.f3 i { background: linear-gradient(135deg, var(--gold-2), var(--gold)); color: var(--navy); }

    .lp .lp-float.f1 { top: 14%; left: -8%; }
    .lp .lp-float.f2 { top: 46%; left: -12%; animation-delay: -1.3s; }
    .lp .lp-float.f3 { top: 62%; right: -8%; animation-delay: -2.6s; }

    @keyframes lp-bob {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    .lp .wave {
        transform-box: fill-box;
        transform-origin: 0% 100%;
        animation: lp-wave 1.8s ease-in-out infinite;
    }

    @keyframes lp-wave {
        0%, 100% { transform: rotate(0deg); }
        50% { transform: rotate(-14deg); }
    }

    @media (prefers-reduced-motion: reduce) {
        .lp .wave, .lp .lp-float { animation: none; }
    }

    /* ---------- Seksi umum ---------- */
    .lp .lp-section { max-width: 1200px; margin: 0 auto; padding: 28px 48px 8px; }

    .lp .lp-head { text-align: center; margin-bottom: 28px; }

    .lp .lp-head h2 {
        margin: 14px 0 0;
        color: var(--navy);
        font-size: clamp(1.5rem, 3vw, 2.1rem);
        font-weight: 800;
    }

    .lp .lp-head p { margin: 8px 0 0; color: var(--navy-soft); }

    /* ---------- Daftar alat: dua panel ---------- */
    .lp .lp-panels {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .lp .lp-panel {
        overflow: hidden;
        border-radius: 24px;
        background: var(--white);
        box-shadow: 0 16px 40px rgba(161, 69, 119, .14);
    }

    /* Panel Komputer: soft blue */
    .lp .lp-panel-head {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 22px;
        background: linear-gradient(120deg, var(--blue-200), var(--blue-100));
        color: var(--navy);
    }

    /* Panel Jaringan: soft pink + gold */
    .lp .lp-panel.net .lp-panel-head {
        background: linear-gradient(120deg, var(--sky-200), var(--gold-2));
        color: var(--navy);
    }

    .lp .lp-panel-head svg { width: 26px; height: 26px; }

    .lp .lp-panel-head strong { font-size: 1.1rem; font-weight: 800; }
    .lp .lp-panel-head small { display: block; font-size: 12px; opacity: .8; }

    .lp .lp-list { margin: 0; padding: 8px 14px 14px; list-style: none; }

    .lp .lp-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 10px;
        border-radius: 16px;
        transition: background-color .15s;
    }

    .lp .lp-item + .lp-item { border-top: 1px dashed var(--line); }
    .lp .lp-item:hover { background: var(--sky-50); }

    .lp .lp-item-ic {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: var(--blue-100);
        color: #3f7fb5;
    }

    .lp .lp-panel.net .lp-item-ic { background: var(--sky-100); color: var(--navy-2); }

    .lp .lp-item-ic svg { width: 23px; height: 23px; }

    .lp .lp-item strong { display: block; font-size: 14.5px; color: var(--navy); }
    .lp .lp-item span { display: block; margin-top: 2px; color: var(--navy-soft); font-size: 13px; line-height: 1.5; }

    /* ---------- Langkah: timeline ---------- */
    .lp .lp-timeline {
        position: relative;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
        margin-top: 8px;
    }

    .lp .lp-timeline::before {
        content: "";
        position: absolute;
        top: 29px;
        left: 16%;
        right: 16%;
        height: 3px;
        border-radius: 3px;
        background: repeating-linear-gradient(90deg, var(--sky-500) 0 10px, transparent 10px 18px);
        opacity: .4;
    }

    .lp .lp-step { position: relative; text-align: center; }

    .lp .lp-step-num {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border: 4px solid var(--sky-50);
        border-radius: 50%;
        background: linear-gradient(135deg, var(--sky-400), var(--sky-500));
        color: var(--white);
        font-size: 1.3rem;
        font-weight: 800;
        box-shadow: 0 10px 24px rgba(236, 106, 154, .35);
    }

    .lp .lp-step:nth-child(2) .lp-step-num {
        background: linear-gradient(135deg, var(--gold-2), var(--gold));
        color: var(--navy);
        box-shadow: 0 10px 24px rgba(217, 164, 65, .35);
    }

    .lp .lp-step:nth-child(3) .lp-step-num {
        background: linear-gradient(135deg, var(--blue-400), #6fb0e6);
        color: var(--white);
        box-shadow: 0 10px 24px rgba(111, 176, 230, .4);
    }

    .lp .lp-step-body {
        margin-top: 16px;
        padding: 18px 18px 20px;
        border-radius: 22px;
        background: var(--white);
        box-shadow: 0 12px 30px rgba(161, 69, 119, .12);
    }

    .lp .lp-step strong { display: block; color: var(--navy); font-size: 1.02rem; }
    .lp .lp-step p { margin: 6px 0 0; color: var(--navy-soft); font-size: 13.5px; line-height: 1.65; }

    /* ---------- CTA bawah ---------- */
    .lp .lp-cta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        max-width: 1104px;
        width: calc(100% - 96px);
        margin: 44px auto 48px;
        padding: 30px 34px;
        border-radius: 24px;
        background:
            radial-gradient(300px 160px at 100% 0%, rgba(243, 213, 138, .4), transparent 70%),
            radial-gradient(300px 160px at 0% 100%, rgba(143, 196, 238, .35), transparent 70%),
            linear-gradient(120deg, var(--navy-2), var(--sky-500));
        color: var(--white);
    }

    .lp .lp-cta h3 { margin: 0; font-size: 1.4rem; font-weight: 800; }
    .lp .lp-cta p { margin: 6px 0 0; color: #ffe6f0; font-size: .95rem; }
    .lp .lp-cta .lp-actions { margin-top: 0; }
    .lp .lp-cta .lp-btn-navy { background: var(--white); color: var(--navy-2); box-shadow: none; border-color: var(--white); }
    .lp .lp-cta .lp-btn-navy:hover { background: var(--blue-100); color: var(--navy-2); }

    /* ---------- Responsif ---------- */
    @media (max-width: 900px) {
        .lp .lp-hero { grid-template-columns: 1fr; padding: 36px 24px 24px; }
        .lp .lp-section { padding: 24px 24px 8px; }
        .lp .lp-bar { padding: 14px 20px; }
        .lp .lp-panels { grid-template-columns: 1fr; }
        .lp .lp-timeline { grid-template-columns: 1fr; }
        .lp .lp-timeline::before { display: none; }
        .lp .lp-cta { width: auto; max-width: none; margin: 36px 24px 32px; }

        .lp .lp-stage { padding-bottom: 6px; }
        .lp .lp-float { position: static; display: inline-flex; margin: 4px 4px 0; animation: none; }
        .lp .lp-floats { display: flex; flex-wrap: wrap; justify-content: center; margin-top: 10px; }
    }

    @media (max-width: 560px) {
        .lp { padding: 12px 8px 40px; }
        .lp .lp-shell { border-radius: 20px; }
        .lp .lp-bar-links a:not(.gold) { display: none; }
        .lp .lp-btn { flex: 1 1 100%; }
        .lp .lp-stat { padding-right: 16px; margin-right: 16px; }
    }
</style>

<section class="lp">
    <div class="lp-shell">

        {{-- Bar atas --}}
        <header class="lp-bar">
            <div class="lp-brand">
                <span class="lp-brand-mark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="12" rx="1.5"/>
                        <path d="M9 20h6M12 16v4"/>
                    </svg>
                </span>
                <span>
                    Lab NET &amp; PC
                    <small>Sistem Peminjaman Alat</small>
                </span>
            </div>
            <nav class="lp-bar-links">
                <a href="#alat">Daftar Alat</a>
                <a href="#panduan">Panduan</a>
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}" class="gold">Daftar</a>
            </nav>
        </header>

        {{-- Hero --}}
        <div class="lp-hero">

            <div class="lp-hero-text">
                <span class="lp-pill">Sistem Peminjaman Lab NET &amp; PC</span>

                <h1 class="lp-title">
                    Pinjam <span class="hl">Alat Jaringan &amp; Komputer</span>
                    jadi lebih <span class="grad">rapi &amp; terstruktur.</span>
                </h1>

                <p class="lp-desc">
                    Cek alat yang tersedia, ajukan peminjaman, dan pantau statusnya
                    dari satu tempat.
                </p>

                <div class="lp-actions">
                    <a href="{{ route('login') }}" class="lp-btn lp-btn-navy">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <path d="M10 17l5-5-5-5M15 12H3"/>
                        </svg>
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="lp-btn lp-btn-gold">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="8" r="3.5"/>
                            <path d="M2.5 20a6.5 6.5 0 0 1 13 0M19 8v6M16 11h6"/>
                        </svg>
                        Daftar
                    </a>
                    <a href="#panduan" class="lp-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 4h7a3 3 0 0 1 3 3v13a2 2 0 0 0-2-2H2zM22 4h-7a3 3 0 0 0-3 3v13a2 2 0 0 1 2-2h8z"/>
                        </svg>
                        Cara Penggunaan
                    </a>
                </div>

                <div class="lp-stats">
                    <div class="lp-stat"><b>8<span>+</span></b><small>Jenis alat</small></div>
                    <div class="lp-stat"><b>3</b><small>Langkah mudah</small></div>
                    <div class="lp-stat"><b>1</b><small>Tempat untuk semuanya</small></div>
                </div>
            </div>

            {{-- Panggung: perempuan menyapa "Hi!" --}}
            <div class="lp-stage" aria-hidden="true">
                <svg class="lp-art" viewBox="0 0 400 440" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="lpArch" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#ffeaf3"/>
                            <stop offset="0.55" stop-color="#fcd3e4"/>
                            <stop offset="1" stop-color="#bfe0f8"/>
                        </linearGradient>
                        <linearGradient id="lpBlazer" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#f58fb5"/>
                            <stop offset="1" stop-color="#d6558a"/>
                        </linearGradient>
                    </defs>

                    {{-- Lengkungan latar --}}
                    <path d="M56 424V196a144 144 0 0 1 288 0v228z" fill="url(#lpArch)"/>
                    <path d="M56 424V196a144 144 0 0 1 288 0v228" stroke="#ffffff" stroke-width="5" stroke-linecap="round"/>
                    <circle cx="200" cy="170" r="104" fill="none" stroke="#d9a441" stroke-width="2.5" stroke-dasharray="3 9" stroke-linecap="round"/>

                    {{-- Dekorasi: bintang kecil & hati --}}
                    <circle cx="86" cy="96" r="7" fill="#f3d58a"/>
                    <circle cx="326" cy="70" r="5" fill="#ffffff"/>
                    <circle cx="354" cy="118" r="4" fill="#d9a441"/>
                    <path d="M70 160c0-6 9-8 9 0 0-8 9-6 9 0 0 7-9 13-9 13s-9-6-9-13z" fill="#f59abb"/>
                    <path d="M340 220c0-5 7-6 7 0 0-6 7-5 7 0 0 5-7 10-7 10s-7-5-7-10z" fill="#8fc4ee"/>
                    <path d="M110 50l3 8 8 3-8 3-3 8-3-8-8-3 8-3z" fill="#f3d58a"/>
                    <path d="M300 30l2.5 6 6 2.5-6 2.5-2.5 6-2.5-6-6-2.5 6-2.5z" fill="#ffffff"/>

                    {{-- Monitor --}}
                    <rect x="22" y="326" width="104" height="70" rx="10" fill="#ffffff" stroke="#6d2b52" stroke-width="3"/>
                    <rect x="30" y="334" width="88" height="54" rx="6" fill="#6d2b52"/>
                    <path d="M40 348h40M40 360h58M40 372h30" stroke="#8fc4ee" stroke-width="3.5" stroke-linecap="round"/>
                    <path d="M40 382h22" stroke="#f3d58a" stroke-width="3.5" stroke-linecap="round"/>
                    <path d="M74 396v14" stroke="#6d2b52" stroke-width="4"/>

                    {{-- Router --}}
                    <rect x="284" y="388" width="92" height="22" rx="9" fill="#ffffff" stroke="#6d2b52" stroke-width="3"/>
                    <circle cx="298" cy="399" r="3" fill="#d9a441"/>
                    <circle cx="310" cy="399" r="3" fill="#8fc4ee"/>
                    <circle cx="322" cy="399" r="3" fill="#f59abb"/>
                    <path d="M298 388v-24M362 388v-24M330 388v-12" stroke="#6d2b52" stroke-width="4" stroke-linecap="round"/>
                    <path d="M316 346a20 20 0 0 1 28 0M321 353a12 12 0 0 1 18 0" stroke="#d9a441" stroke-width="3.5" stroke-linecap="round"/>
                    <circle cx="330" cy="360" r="3" fill="#d9a441"/>

                    {{-- Perempuan --}}
                    <g transform="translate(-43 77) scale(1.35)">
                        {{-- Rambut belakang (panjang) --}}
                        <path d="M136 124c-8 44 0 84 22 96l12-90z" fill="#4a2a3c"/>
                        <path d="M224 124c8 44 0 84-22 96l-12-90z" fill="#4a2a3c"/>

                        {{-- Blazer pink --}}
                        <path d="M126 254c0-52 20-80 54-80s54 28 54 80z" fill="url(#lpBlazer)"/>
                        <path d="M160 176l20 34 20-34" fill="#ffffff"/>
                        <path d="M180 210v44" stroke="#6d2b52" stroke-opacity=".3" stroke-width="2"/>
                        <circle cx="180" cy="228" r="2.5" fill="#f3d58a"/>
                        <circle cx="180" cy="242" r="2.5" fill="#f3d58a"/>

                        {{-- Kalung --}}
                        <path d="M168 182q12 12 24 0" stroke="#d9a441" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="180" cy="190" r="2.5" fill="#f3d58a"/>

                        <rect x="170" y="156" width="20" height="24" rx="8" fill="#f1c9a8"/>
                        <circle cx="180" cy="122" r="35" fill="#f6d3b8"/>
                        <path d="M144 124c-4-38 28-52 46-46 22-2 44 14 38 46-6-18-20-28-38-28-16 0-40 4-46 28z" fill="#4a2a3c"/>

                        {{-- Wajah --}}
                        <circle cx="168" cy="126" r="2.8" fill="#4a2a3c"/>
                        <circle cx="192" cy="126" r="2.8" fill="#4a2a3c"/>
                        <path d="M163 120l-3-2M173 119l2-2.5M187 119l-2-2.5M197 120l3-2" stroke="#4a2a3c" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M170 138q10 9 20 0" stroke="#d6558a" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="158" cy="136" r="5" fill="#f58fb5" fill-opacity=".6"/>
                        <circle cx="202" cy="136" r="5" fill="#f58fb5" fill-opacity=".6"/>

                        {{-- Pita di rambut --}}
                        <path d="M214 88l-17-9v18z" fill="#f59abb"/>
                        <path d="M214 88l17-9v18z" fill="#f59abb"/>
                        <circle cx="214" cy="88" r="5" fill="#d9a441"/>

                        {{-- Headset --}}
                        <path d="M145 122c-2-34 20-48 36-48s38 14 36 48" stroke="#d9a441" stroke-width="5" stroke-linecap="round"/>
                        <rect x="139" y="116" width="10" height="22" rx="5" fill="#d6558a"/>
                        <rect x="211" y="116" width="10" height="22" rx="5" fill="#d6558a"/>
                        <path d="M144 136c0 14 14 20 26 18" stroke="#6d2b52" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="172" cy="154" r="3.5" fill="#d9a441"/>

                        {{-- Tangan kiri --}}
                        <path d="M138 190c-10 18-12 36-10 52" stroke="#e66a9b" stroke-width="16" stroke-linecap="round"/>

                        {{-- Tangan kanan melambai --}}
                        <g class="wave">
                            <path d="M222 190c22 0 36-20 40-52" stroke="#e66a9b" stroke-width="16" stroke-linecap="round"/>
                            <circle cx="263" cy="126" r="11" fill="#f6d3b8"/>
                            <path d="M257 118v-8M263 116v-10M269 118v-8" stroke="#f6d3b8" stroke-width="4" stroke-linecap="round"/>
                            <path d="M276 108q8 6 6 16M282 100q12 10 9 26" stroke="#d9a441" stroke-width="2.5" stroke-linecap="round"/>
                        </g>
                    </g>

                    {{-- Balon Hi! --}}
                    <rect x="272" y="104" width="92" height="46" rx="23" fill="#f3d58a"/>
                    <path d="M292 148l-8 20 24-14z" fill="#f3d58a"/>
                    <text x="318" y="136" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="26" font-weight="800" fill="#6d2b52">Hi!</text>

                    {{-- Meja --}}
                    <rect x="14" y="408" width="372" height="14" rx="7" fill="#6d2b52"/>
                </svg>

                <div class="lp-floats">
                    <div class="lp-float f1"><i>1</i> Cek alat tersedia</div>
                    <div class="lp-float f2"><i>2</i> Ajukan peminjaman</div>
                    <div class="lp-float f3"><i>3</i> Pantau status</div>
                </div>
            </div>

        </div>


        {{-- Daftar alat --}}
        <div class="lp-section" id="alat">
            <div class="lp-head">
                <span class="lp-pill light">Daftar Alat</span>
                <h2>Alat yang bisa dipinjam</h2>
                <p>Pilih sesuai kebutuhan praktik kamu.</p>
            </div>

            <div class="lp-panels">

                {{-- Komputer --}}
                <div class="lp-panel">
                    <div class="lp-panel-head">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="12" rx="1.5"/>
                            <path d="M9 20h6M12 16v4"/>
                        </svg>
                        <div>
                            <strong>Komputer</strong>
                            <small>Perangkat &amp; pendukung</small>
                        </div>
                    </div>

                    <ul class="lp-list">
                        <li class="lp-item">
                            <span class="lp-item-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="5" width="16" height="11" rx="1.5"/>
                                    <path d="M2 19h20"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Laptop</strong>
                                <span>Untuk kebutuhan praktik dan pengerjaan tugas.</span>
                            </div>
                        </li>

                        <li class="lp-item">
                            <span class="lp-item-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="7" y="3" width="10" height="18" rx="1.5"/>
                                    <path d="M10 7h4"/>
                                    <circle cx="12" cy="16.5" r="1"/>
                                </svg>
                            </span>
                            <div>
                                <strong>PC desktop</strong>
                                <span>Unit komputer untuk praktik perakitan dan instalasi.</span>
                            </div>
                        </li>

                        <li class="lp-item">
                            <span class="lp-item-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="12" rx="1.5"/>
                                    <path d="M9 20h6M12 16v4"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Monitor</strong>
                                <span>Layar tambahan untuk kebutuhan praktik.</span>
                            </div>
                        </li>

                        <li class="lp-item">
                            <span class="lp-item-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="11" width="13" height="8" rx="1.5"/>
                                    <path d="M5 14.5h1M8 14.5h1M11 14.5h1M5 16.5h7"/>
                                    <rect x="17.5" y="9" width="4.5" height="11" rx="2.25"/>
                                    <path d="M19.75 9v4"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Mouse dan keyboard</strong>
                                <span>Perangkat pendukung untuk komputer.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Jaringan --}}
                <div class="lp-panel net">
                    <div class="lp-panel-head">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 10a10 10 0 0 1 14 0"/>
                            <path d="M8 13a6 6 0 0 1 8 0"/>
                            <circle cx="12" cy="16.5" r="1" fill="currentColor"/>
                            <path d="M9 21h6"/>
                        </svg>
                        <div>
                            <strong>Jaringan</strong>
                            <small>Perangkat praktik jaringan</small>
                        </div>
                    </div>

                    <ul class="lp-list">
                        <li class="lp-item">
                            <span class="lp-item-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="14" width="18" height="6" rx="1.5"/>
                                    <path d="M7 17h.01M11 17h.01"/>
                                    <path d="M8 14V8M16 14V8"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Perangkat jaringan</strong>
                                <span>Router dan perangkat jaringan lain untuk praktik.</span>
                            </div>
                        </li>

                        <li class="lp-item">
                            <span class="lp-item-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="8" width="20" height="8" rx="1.5"/>
                                    <path d="M5 11.5h2v3H5zM9 11.5h2v3H9zM13 11.5h2v3h-2zM17 11.5h2v3h-2z"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Switch</strong>
                                <span>Untuk praktik konfigurasi jaringan lokal.</span>
                            </div>
                        </li>

                        <li class="lp-item">
                            <span class="lp-item-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 10a10 10 0 0 1 14 0"/>
                                    <path d="M8 13a6 6 0 0 1 8 0"/>
                                    <circle cx="12" cy="16.5" r="1" fill="currentColor"/>
                                    <path d="M9 21h6"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Access point</strong>
                                <span>Untuk praktik jaringan nirkabel.</span>
                            </div>
                        </li>

                        <li class="lp-item">
                            <span class="lp-item-ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 3h8v8l2 3v4a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-4l2-3z"/>
                                    <path d="M10 7v3M12 7v3M14 7v3"/>
                                </svg>
                            </span>
                            <div>
                                <strong>Kabel LAN dan crimping tool</strong>
                                <span>Untuk praktik membuat dan menguji kabel jaringan.</span>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>


        {{-- Langkah penggunaan --}}
        <div class="lp-section" id="panduan" style="padding-top: 44px;">
            <div class="lp-head">
                <span class="lp-pill light">Alur Kerja Praktis</span>
                <h2>Langkah Mudah Penggunaan Sistem</h2>
                <p>Tiga tahap sederhana untuk mengajukan peminjaman alat.</p>
            </div>

            <div class="lp-timeline">
                <div class="lp-step">
                    <span class="lp-step-num">1</span>
                    <div class="lp-step-body">
                        <strong>Masuk atau Daftar</strong>
                        <p>Login dengan akun kamu, atau buat akun baru jika belum punya.</p>
                    </div>
                </div>

                <div class="lp-step">
                    <span class="lp-step-num">2</span>
                    <div class="lp-step-body">
                        <strong>Pilih Alat &amp; Ajukan</strong>
                        <p>Cek alat yang tersedia, lalu ajukan peminjaman sesuai kebutuhan praktik.</p>
                    </div>
                </div>

                <div class="lp-step">
                    <span class="lp-step-num">3</span>
                    <div class="lp-step-body">
                        <strong>Pantau Status</strong>
                        <p>Lihat perkembangan pengajuan dan kembalikan alat setelah selesai dipakai.</p>
                    </div>
                </div>
            </div>
        </div>


        {{-- Ajakan akhir --}}
        <div class="lp-cta">
            <div>
                <h3>Siap meminjam alat lab?</h3>
                <p>Masuk atau buat akun, lalu ajukan peminjaman dalam beberapa langkah.</p>
            </div>
            <div class="lp-actions">
                <a href="{{ route('login') }}" class="lp-btn lp-btn-navy">Masuk</a>
                <a href="{{ route('register') }}" class="lp-btn lp-btn-gold">Daftar Sekarang</a>
            </div>
        </div>

    </div>
</section>

{{-- Sembunyikan semua elemen layout (navbar, teks NET & PC, Masuk/Daftar) yang berada di atas halaman ini --}}
<script>
    (function () {
        var el = document.querySelector('.lp');
        if (!el) return;
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        var node = el;
        while (node && node !== document.body) {
            var prev = node.previousElementSibling;
            while (prev) {
                if (!/^(SCRIPT|STYLE|LINK|META)$/.test(prev.tagName)) {
                    prev.style.display = 'none';
                }
                prev = prev.previousElementSibling;
            }
            node = node.parentElement;
        }
    })();
</script>

@endsection
