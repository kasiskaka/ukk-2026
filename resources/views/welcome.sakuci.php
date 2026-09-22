```php
@extends('layouts.app')

@section('title', 'Peminjaman Alat Jaringan & Komputer')

@section('content')

<style>
    /* =====================================================
       HOME
    ===================================================== */

    .home-page {
        position: relative;
        overflow: hidden;
        min-height: calc(100vh - 70px);
    }

    /* Dekorasi lembut */
    .home-page::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: rgba(237, 143, 188, .07);
        top: -130px;
        left: -100px;
        pointer-events: none;
    }

    .home-page::after {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(214, 177, 92, .07);
        bottom: -160px;
        right: -100px;
        pointer-events: none;
    }


    /* =====================================================
       HERO
    ===================================================== */

    .hero-wrapper {
        position: relative;
        z-index: 2;

        max-width: 1050px;
        margin: auto;

        padding: 65px 25px 35px;
    }


    /* =====================================================
       EMOJI ALAT
    ===================================================== */

    .equipment-row {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 18px;

        margin-bottom: 25px;

        font-size: 30px;
    }

    .equipment-item {
        width: 52px;
        height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: rgba(255, 255, 255, .65);

        border: 1px solid rgba(214, 177, 92, .35);

        box-shadow: 0 5px 15px rgba(0, 0, 0, .05);

        transition: .25s ease;
    }

    .equipment-item:hover {
        transform: translateY(-5px) rotate(-2deg);

        border-color: #ed8fbc;

        box-shadow:
            0 9px 20px rgba(237, 143, 188, .15);
    }


    /* =====================================================
       BADGE
    ===================================================== */

    .modern-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 8px 18px;

        border-radius: 50px;

        color: #b28a32;

        background: rgba(214, 177, 92, .10);

        border: 1px solid rgba(214, 177, 92, .35);

        font-size: 12px;
        font-weight: 700;

        letter-spacing: 1.5px;

        margin-bottom: 23px;
    }

    .badge-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: #ed8fbc;

        box-shadow:
            0 0 0 4px rgba(237, 143, 188, .10);
    }


    /* =====================================================
       TITLE
    ===================================================== */

    .modern-title {
        margin: 0;

        font-size: clamp(2.6rem, 6vw, 4.8rem);

        line-height: 1.08;

        font-weight: 800;

        letter-spacing: -2px;
    }

    .modern-title .highlight {
        color: #ed8fbc;

        position: relative;

        display: inline-block;
    }

    .modern-title .highlight::after {
        content: "";

        position: absolute;

        left: 3px;
        bottom: -8px;

        width: 65%;

        height: 3px;

        border-radius: 10px;

        background: #d6b15c;
    }


    /* =====================================================
       DESCRIPTION
    ===================================================== */

    .modern-description {
        max-width: 650px;

        margin: 30px auto 0;

        font-size: 1.05rem;

        line-height: 1.8;

        opacity: .72;
    }


    /* =====================================================
       BUTTON
    ===================================================== */

    .home-buttons {
        display: flex;

        justify-content: center;

        flex-wrap: wrap;

        gap: 12px;

        margin-top: 30px;
    }

    .btn-gold-modern {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 195px;

        padding: 13px 25px;

        border-radius: 10px;

        color: #ffffff !important;

        background: #d6b15c;

        border: 1px solid #d6b15c;

        font-size: 13px;

        font-weight: 700;

        letter-spacing: .5px;

        text-decoration: none;

        transition: .25s ease;
    }

    .btn-gold-modern:hover {
        color: #ffffff !important;

        background: #c39e48;

        border-color: #c39e48;

        transform: translateY(-3px);

        box-shadow:
            0 10px 22px rgba(214, 177, 92, .22);
    }


    .btn-pink-modern {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 160px;

        padding: 13px 25px;

        border-radius: 10px;

        color: #ed8fbc;

        background: transparent;

        border: 1px solid rgba(237, 143, 188, .55);

        font-size: 13px;

        font-weight: 700;

        letter-spacing: .5px;

        text-decoration: none;

        transition: .25s ease;
    }

    .btn-pink-modern:hover {
        color: #ffffff;

        background: #ed8fbc;

        border-color: #ed8fbc;

        transform: translateY(-3px);

        box-shadow:
            0 10px 22px rgba(237, 143, 188, .20);
    }


    /* =====================================================
       NOTE
    ===================================================== */

    .home-note {
        margin-top: 18px;

        font-size: 13px;

        opacity: .55;
    }

    .home-note span {
        color: #d6b15c;

        font-weight: 600;
    }


    /* =====================================================
       FEATURE
    ===================================================== */

    .feature-section {
        position: relative;

        z-index: 2;

        max-width: 1050px;

        margin: 25px auto 0;

        padding: 0 25px 60px;
    }

    .feature-grid {
        display: grid;

        grid-template-columns:
            repeat(4, 1fr);

        gap: 14px;
    }

    .feature-card {
        position: relative;

        padding: 24px 20px;

        background: transparent;

        border: 1px solid rgba(128, 128, 128, .18);

        border-radius: 14px;

        transition: .3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);

        border-color: rgba(214, 177, 92, .55);

        box-shadow:
            0 15px 30px rgba(0, 0, 0, .06);
    }


    /* Emoji feature */
    .feature-icon {
        font-size: 28px;

        margin-bottom: 12px;

        display: inline-block;

        transition: .25s ease;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.08);
    }


    .feature-number {
        color: #d6b15c;

        font-size: 11px;

        font-weight: 700;

        letter-spacing: 1px;

        margin-bottom: 8px;
    }

    .feature-title {
        color: #ed8fbc;

        font-size: 16px;

        font-weight: 700;

        margin-bottom: 7px;
    }

    .feature-text {
        margin: 0;

        font-size: 13px;

        line-height: 1.6;

        opacity: .58;
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 900px) {

        .feature-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .modern-title {
            font-size: 3.5rem;
        }
    }


    @media (max-width: 600px) {

        .hero-wrapper {
            padding: 50px 20px 30px;
        }

        .equipment-row {
            gap: 9px;
        }

        .equipment-item {
            width: 45px;
            height: 45px;

            font-size: 25px;
        }

        .modern-title {
            font-size: 2.4rem;

            letter-spacing: -1px;
        }

        .modern-description {
            font-size: .95rem;
        }

        .home-buttons {
            flex-direction: column;
        }

        .btn-gold-modern,
        .btn-pink-modern {
            width: 100%;
        }

        .feature-section {
            padding-left: 20px;
            padding-right: 20px;
        }

        .feature-grid {
            grid-template-columns: 1fr;
        }
    }
</style>


<section class="home-page">

    <!-- =================================================
         HERO
    ================================================== -->

    <div class="hero-wrapper text-center">


        <!-- Ikon Peralatan -->
        <div class="equipment-row">

            <div class="equipment-item" title="Laptop">
                💻
            </div>

            <div class="equipment-item" title="Mouse">
                🖱️
            </div>

            <div class="equipment-item" title="Keyboard">
                ⌨️
            </div>

            <div class="equipment-item" title="Monitor">
                🖥️
            </div>

            <div class="equipment-item" title="Router">
                📡
            </div>

        </div>


        <!-- Badge -->
        <div class="modern-badge">

            <span class="badge-dot"></span>

            SISKA.NIH

        </div>


        <!-- Judul -->
        <h1 class="modern-title">

            Welcome To Sistem

            <br>

            <span class="highlight">
                Peminjaman Alat Siscas!
            </span>

        </h1>


        <!-- Deskripsi -->
        <p class="modern-description">

            Pinjam alat lebih mudah, cepat, dan terintegrasi.
            Temukan peralatan yang kamu butuhkan, ajukan peminjaman,
            dan bantu menjaga keamanan inventaris bersama.

        </p>


        <!-- Button -->
        <div class="home-buttons">

            <a
                href="{{ route('kategori.index') }}"
                class="btn-gold-modern"
            >
                JELAJAHI KATALOG
            </a>


            <a
                href="{{ route('alat.index') }}"
                class="btn-pink-modern"
            >
                LIHAT ALAT
            </a>

        </div>


        <!-- Note -->
        <p class="home-note">

            Mulai cari alat yang kamu butuhkan
            <span>dan pinjam dengan mudah.</span>

        </p>

    </div>


    <!-- =================================================
         FEATURE
    ================================================== -->

    <div class="feature-section">

        <div class="feature-grid">


            <!-- Laptop -->
            <div class="feature-card">

                <div class="feature-icon">
                    💻
                </div>

                <div class="feature-number">
                    01
                </div>

                <div class="feature-title">
                    Laptop
                </div>

                <p class="feature-text">
                    Perangkat komputer untuk kebutuhan
                    pembelajaran dan pekerjaan.
                </p>

            </div>


            <!-- Mouse -->
            <div class="feature-card">

                <div class="feature-icon">
                    🖱️
                </div>

                <div class="feature-number">
                    02
                </div>

                <div class="feature-title">
                    Perangkat Pendukung
                </div>

                <p class="feature-text">
                    Mouse, keyboard, dan berbagai
                    perangkat pendukung lainnya.
                </p>

            </div>


            <!-- Monitor -->
            <div class="feature-card">

                <div class="feature-icon">
                    🖥️
                </div>

                <div class="feature-number">
                    03
                </div>

                <div class="feature-title">
                    Perangkat Komputer
                </div>

                <p class="feature-text">
                    Monitor dan perangkat komputer
                    tersedia dalam sistem.
                </p>

            </div>


            <!-- Network -->
            <div class="feature-card">

                <div class="feature-icon">
                    📡
                </div>

                <div class="feature-number">
                    04
                </div>

                <div class="feature-title">
                    Perangkat Jaringan
                </div>

                <p class="feature-text">
                    Router dan perangkat jaringan
                    untuk kebutuhan praktik.
                </p>

            </div>

        </div>

    </div>

</section>

@endsection
```
