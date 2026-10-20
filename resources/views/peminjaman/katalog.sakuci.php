@extends('layouts.app')

@section('title', 'Katalog Alat')

@section('content')

{{-- ============================================================
     1. DATA: icon & kategori dibaca dari nama alat
        Urutan penting: yang lebih spesifik taruh di atas.
        Alat baru? Cukup tambah satu baris di $peta.
     ============================================================ --}}
@php
    $peta = [
        // kata kunci                              icon    kategori
        ['kw' => ['mouse'],                       'ico' => '🖱️', 'kat' => 'Perangkat input'],
        ['kw' => ['keyboard'],                    'ico' => '⌨️', 'kat' => 'Perangkat input'],

        ['kw' => ['cas', 'charger', 'adaptor'],   'ico' => '🔌', 'kat' => 'Komputer'],
        ['kw' => ['laptop'],                      'ico' => '💻', 'kat' => 'Komputer'],
        ['kw' => ['monitor', 'pc', 'cpu'],        'ico' => '🖥️', 'kat' => 'Komputer'],
        ['kw' => ['flashdisk', 'hdd', 'ssd'],     'ico' => '💾', 'kat' => 'Komputer'],

        ['kw' => ['headphone', 'headset'],        'ico' => '🎧', 'kat' => 'Audio & visual'],
        ['kw' => ['proyektor', 'projector'],      'ico' => '📽️', 'kat' => 'Audio & visual'],

        ['kw' => ['router', 'access point'],      'ico' => '📡', 'kat' => 'Jaringan'],
        ['kw' => ['switch', 'hub'],               'ico' => '🔀', 'kat' => 'Jaringan'],
        ['kw' => ['crimping', 'tang', 'tester'],  'ico' => '🛠️', 'kat' => 'Jaringan'],
        ['kw' => ['utp', 'kabel', 'lan'],         'ico' => '🌐', 'kat' => 'Jaringan'],
    ];

    $kategori = ['Semua', 'Komputer', 'Perangkat input', 'Jaringan', 'Audio & visual', 'Lainnya'];

    // Mengembalikan icon & kategori yang cocok dengan nama alat
    $cocok = function ($nama) use ($peta) {
        $nama = strtolower($nama);

        foreach ($peta as $p) {
            foreach ($p['kw'] as $kata) {
                if (strpos($nama, $kata) !== false) {
                    return $p;
                }
            }
        }

        return ['ico' => '🧰', 'kat' => 'Lainnya'];
    };
@endphp


{{-- ============================================================
     2. CSS (prefix "kt-" supaya tidak bentrok dengan style lain)
     ============================================================ --}}
<style>
    /* ---- Warna & ukuran: ubah di sini untuk ganti tema ---- */
    .kt {
        --kt-ink:       #3a1540;
        --kt-muted:     #7d6584;
        --kt-accent:    #d93b78;
        --kt-line:      #f1d9e5;
        --kt-tint:      #fde7f0;
        --kt-hero-text: #e8cfe9;
        --kt-radius:    18px;
    }

    /* ---- Banner ---- */
    .kt-hero {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 26px 28px;
        overflow: hidden;
        color: #fff;
        background: var(--kt-ink);
        border-radius: 22px;
    }

    .kt-hero h1 {
        max-width: 16ch;
        margin: 0 0 6px;
        font-size: clamp(22px, 3vw, 32px);
        font-weight: 800;
        line-height: 1.15;
    }

    .kt-hero p {
        margin: 0;
        color: var(--kt-hero-text);
    }

    .kt-hero__deco {
        position: absolute;
        top: -20px;
        right: -8px;
        font-size: 110px;
        opacity: .12;
        transform: rotate(12deg);
        pointer-events: none;
    }

    .kt-stat {
        min-width: 96px;
        padding: 10px 18px;
        background: rgba(255, 255, 255, .12);
        border-radius: 14px;
    }

    .kt-stat b {
        display: block;
        font-size: 24px;
    }

    .kt-stat small {
        color: var(--kt-hero-text);
    }

    /* ---- Filter kategori ---- */
    .kt-chip {
        padding: 7px 14px;
        font-size: 14px;
        font-weight: 600;
        color: var(--kt-ink);
        background: #fff;
        border: 1.5px solid var(--kt-line);
        border-radius: 99px;
    }

    .kt-chip[aria-pressed="true"] {
        color: #fff;
        background: var(--kt-accent);
        border-color: var(--kt-accent);
    }

    /* ---- Daftar alat ---- */
    .kt-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 14px;
    }

    .kt-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 14px;
        background: #fff;
        border: 1px solid var(--kt-line);
        border-radius: var(--kt-radius);
    }

    .kt-item__icon {
        flex: none;
        display: grid;
        place-items: center;
        width: 64px;
        height: 64px;
        font-size: 34px;
        background: var(--kt-tint);
        border-radius: 16px;
    }

    .kt-item--pakai .kt-item__icon {
        filter: grayscale(1);
        opacity: .5;
    }

    .kt-item__info {
        flex: 1;
        min-width: 0;
    }

    .kt-item__name {
        margin: 0 0 4px;
        font-size: 16px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .kt-item__meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--kt-muted);
    }

    .kt-item__action {
        flex: none;
    }

    /* ---- Kosong ---- */
    .kt-empty {
        grid-column: 1 / -1;
        padding: 36px;
        text-align: center;
        color: var(--kt-muted);
    }

    .kt-empty span {
        display: block;
        font-size: 40px;
    }

    /* ---- HP ---- */
    @media (max-width: 480px) {
        .kt-grid {
            grid-template-columns: 1fr;
        }
    }
</style>


{{-- ============================================================
     3. TAMPILAN
     ============================================================ --}}
<div class="kt">

    {{-- Banner + ringkasan --}}
    <section class="kt-hero mb-4">
        <div>
            <h1>Alat apa yang kamu butuhkan hari ini?</h1>
            <p>Pilih alat yang tersedia, lalu klik <strong>Pinjam</strong>.</p>
        </div>

        <div class="d-flex gap-2">
            <div class="kt-stat"><b id="ktOk">0</b><small>Tersedia</small></div>
            <div class="kt-stat"><b id="ktPakai">0</b><small>Sedang dipinjam</small></div>
        </div>

        <div class="kt-hero__deco">🖥️</div>
    </section>

    {{-- Pencarian --}}
    <form method="GET" action="{{ route('peminjaman.katalog') }}" class="d-flex gap-2 mb-3">
        <input type="text" name="q" value="{{ $q }}"
               class="form-control form-control-lg"
               placeholder="🔍  Cari nama atau kode alat...">
        <button type="submit" class="btn btn-gold px-4">Cari</button>
    </form>

    {{-- Filter kategori --}}
    <div class="d-flex flex-wrap gap-2 mb-3" id="ktChips">
        @foreach ($kategori as $k)
            <button type="button" class="kt-chip"
                    aria-pressed="{{ $k === 'Semua' ? 'true' : 'false' }}">{{ $k }}</button>
        @endforeach
    </div>

    {{-- Daftar alat --}}
    <div class="kt-grid" id="ktGrid">
        @forelse ($daftar as $a)
            @php $m = $cocok($a->nama_alat); @endphp

            <article class="kt-item {{ $a->tersedia ? '' : 'kt-item--pakai' }}"
                     data-kat="{{ $m['kat'] }}"
                     data-pakai="{{ $a->tersedia ? '0' : '1' }}">

                <div class="kt-item__icon" aria-hidden="true">{{ $m['ico'] }}</div>

                <div class="kt-item__info">
                    <h3 class="kt-item__name">{{ $a->nama_alat }}</h3>
                    <div class="kt-item__meta">
                        <span>Kode {{ $a->kode_alat }}</span>
                        @if ($a->tersedia)
                            <span class="badge text-bg-success">Tersedia</span>
                        @else
                            <span class="badge text-bg-secondary">Sedang dipinjam</span>
                        @endif
                    </div>
                </div>

                <div class="kt-item__action">
                    @if ($a->tersedia)
                        <a href="{{ route('peminjaman.ajukan') }}?id_alat={{ $a->id_alat }}"
                           class="btn btn-gold btn-sm">Pinjam</a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>Penuh</button>
                    @endif
                </div>
            </article>
        @empty
            <div class="kt-empty"><span>🔎</span>Alat tidak ditemukan. Coba kata kunci lain.</div>
        @endforelse
    </div>

</div>


{{-- ============================================================
     4. JAVASCRIPT: hitung ringkasan & filter kategori
     ============================================================ --}}
<script>
    (function () {
        'use strict';

        var items = document.querySelectorAll('#ktGrid .kt-item');
        var chips = document.querySelectorAll('#ktChips .kt-chip');

        // Isi angka "Tersedia" dan "Sedang dipinjam" di banner
        function tampilkanRingkasan() {
            var pakai = 0;

            items.forEach(function (el) {
                if (el.dataset.pakai === '1') pakai++;
            });

            document.getElementById('ktOk').textContent    = items.length - pakai;
            document.getElementById('ktPakai').textContent = pakai;
        }

        // Tampilkan hanya alat dari kategori yang dipilih
        function filterKategori(pilihan) {
            items.forEach(function (el) {
                var cocok = pilihan === 'Semua' || el.dataset.kat === pilihan;
                el.style.display = cocok ? '' : 'none';
            });
        }

        // Tandai tombol kategori yang aktif
        function tandaiChip(aktif) {
            chips.forEach(function (c) {
                c.setAttribute('aria-pressed', c === aktif ? 'true' : 'false');
            });
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                tandaiChip(chip);
                filterKategori(chip.textContent.trim());
            });
        });

        tampilkanRingkasan();
    })();
</script>

@endsection