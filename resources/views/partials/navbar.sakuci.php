{{-- Sidebar + layout area konten. Semua gaya sidebar ada di file ini (tema soft pink). --}}

@php
    $currentUser = \App\Models\User::current();
    $isAdmin = $currentUser && $currentUser->role === 'admin';
    // admin dan petugas sama-sama pengelola (boleh kelola peminjaman)
    $isPengelola = $currentUser && in_array($currentUser->role, ['admin', 'petugas']);

    // Hitung pengajuan Pending untuk notifikasi admin
    $pendingCount = 0;

    if ($isPengelola) {
        foreach (\App\Models\Peminjaman::all() as $pn) {
            if (strtolower($pn->status) === 'pending') {
                $pendingCount++;
            }
        }
    }
@endphp

<style>
    /* ===== Sidebar & layout (soft pink + soft blue + semi gold) ===== */
    body .app-wrapper {
        --sb-w: 264px;
        --plum: #6d2b52;
        --plum-2: #a14577;
        --plum-soft: #98677f;
        --pk-500: #ec6a9a;
        --pk-200: #fccfe0;
        --gold: #d9a441;
        --gold-2: #f3d58a;

        display: flex;
        align-items: stretch;
        min-height: 100vh;
    }

    /* ---------- Sidebar ---------- */
    body .app-wrapper .app-sidebar {
        position: sticky;
        top: 0;
        flex: 0 0 var(--sb-w);
        width: var(--sb-w);
        height: 100vh;
        margin-left: 0;
        overflow-x: hidden;
        overflow-y: auto;
        z-index: 1040;
        background: linear-gradient(180deg, #fff6fa 0%, #ffe6f0 60%, #e0f0fd 100%);
        border-right: 1px solid rgba(109, 43, 82, .12);
        box-shadow: 6px 0 24px rgba(161, 69, 119, .08);
        transition: margin-left .25s ease, transform .25s ease, visibility 0s;
    }

    body .app-wrapper.sidebar-closed .app-sidebar {
        margin-left: calc(var(--sb-w) * -1);
        visibility: hidden;
        transition: margin-left .25s ease, transform .25s ease, visibility 0s .25s;
    }

    .app-sidebar .sidebar-brand {
        color: var(--plum);
        font-size: 1.3rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .app-sidebar .sidebar-brand-sub {
        color: var(--plum-soft);
        font-size: 12px;
        font-weight: 600;
    }

    .app-sidebar .border-bottom,
    .app-sidebar .border-top {
        border-color: rgba(109, 43, 82, .14) !important;
    }

    .app-sidebar .nav-link {
        padding: 10px 14px;
        border-radius: 14px;
        color: var(--plum);
        font-size: 14.5px;
        font-weight: 600;
        transition: background-color .15s, color .15s, transform .15s;
    }

    .app-sidebar .nav-link:hover {
        background: rgba(236, 106, 154, .13);
        color: var(--plum-2);
        transform: translateX(2px);
    }

    .app-sidebar .nav-pills .nav-link.active,
    .app-sidebar .nav-link.active {
        background: linear-gradient(120deg, var(--pk-500), var(--plum-2));
        color: #ffffff;
        box-shadow: 0 8px 18px rgba(236, 106, 154, .35);
        transform: none;
    }

    .app-sidebar .sidebar-label {
        display: block;
        padding: 0 6px;
        color: var(--plum-soft);
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .app-sidebar .badge-notif {
        font-size: 11px;
        vertical-align: middle;
    }

    .app-sidebar .nav-link.active .badge-notif {
        background: #ffffff !important;
        color: var(--plum-2);
    }

    /* Info user */
    .app-sidebar .sidebar-user {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 16px;
        background: rgba(255, 255, 255, .7);
    }

    .app-sidebar .sidebar-avatar {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--gold-2), var(--gold));
        color: var(--plum);
        font-weight: 800;
    }

    .app-sidebar .sidebar-username { color: var(--plum); font-size: 14px; font-weight: 700; }
    .app-sidebar .sidebar-role { color: var(--plum-soft); font-size: 12px; }

    .app-sidebar .sidebar-btn { border-radius: 999px; font-weight: 700; }

    .app-sidebar .btn-accent {
        border: 0;
        background: linear-gradient(120deg, var(--pk-500), var(--plum-2));
        color: #ffffff;
    }

    .app-sidebar .btn-accent:hover { color: #ffffff; filter: brightness(1.05); }

    .app-sidebar .btn-accent-outline {
        border: 2px solid var(--pk-500);
        background: transparent;
        color: var(--plum-2);
    }

    .app-sidebar .btn-accent-outline:hover { background: rgba(236, 106, 154, .12); color: var(--plum-2); }

    /* ---------- Area konten ---------- */
    body .app-wrapper .app-main {
        flex: 1 1 auto;
        min-width: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    body .app-wrapper .app-main > main { flex: 1 1 auto; }

    .app-main > header.navbar {
        z-index: 1030;
    }

    .app-main .topbar-title {
        margin-left: 12px;
        color: var(--plum);
        font-weight: 800;
        letter-spacing: .01em;
    }

    .app-main header .btn-outline-secondary {
        border: 2px solid var(--pk-500);
        border-radius: 12px;
        background: #ffffff;
        color: var(--plum-2);
        line-height: 1;
    }

    .app-main header .btn-outline-secondary:hover {
        background: var(--pk-500);
        border-color: var(--pk-500);
        color: #ffffff;
    }

    .sidebar-backdrop { display: none; }

    /* ---------- Layar kecil: sidebar menggeser dari kiri ---------- */
    @media (max-width: 991.98px) {
        body .app-wrapper .app-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: min(280px, 86vw);
            height: 100%;
            margin-left: 0;
            transform: translateX(0);
        }

        body .app-wrapper.sidebar-closed .app-sidebar {
            margin-left: 0;
            transform: translateX(-105%);
            box-shadow: none;
        }

        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1035;
            background: rgba(109, 43, 82, .35);
        }

        .app-wrapper:not(.sidebar-closed) .sidebar-backdrop { display: block; }
    }

    @media (prefers-reduced-motion: reduce) {
        body .app-wrapper .app-sidebar { transition: none; }
        .app-sidebar .nav-link { transition: none; }
    }
</style>


<div class="app-wrapper">

    <aside id="appSidebar"
           class="app-sidebar d-flex flex-column justify-content-between p-2 pt-3">

        <div>

            <!-- Header sidebar -->
            <div class="d-flex align-items-center justify-content-between pb-2 mb-2 px-1 border-bottom">

                <a class="text-decoration-none" href="{{ route('home') }}">
                    <span class="sidebar-brand d-block">NET &amp; PC</span>
                    <small class="sidebar-brand-sub">Lab peminjaman</small>
                </a>

                <!-- Tombol tutup (layar kecil) -->
                <button type="button"
                        class="btn-close btn-sm d-lg-none"
                        data-sidebar-toggle
                        aria-label="Tutup">
                </button>

            </div>


            <!-- Menu -->
            <ul class="nav nav-pills flex-column gap-1">

                @if ($isPengelola)

                    @if ($isAdmin)
                    <li class="nav-item">
                        <a class="nav-link {{ is_route('home') ? 'active' : '' }}"
                           href="{{ route('home') }}">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('kategori.index') ? 'active' : '' }}"
                           href="{{ route('kategori.index') }}">Kategori</a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('alat') ? 'active' : '' }}"
                           href="{{ route('alat.index') }}">Daftar alat</a>
                    </li>

                    {{-- Peminjaman & Pengembalian (CRUD): khusus admin --}}
                    @if ($isAdmin)
                    <li class="nav-item">
                        <a class="nav-link {{ is_route('peminjaman') ? 'active' : '' }}"
                           href="{{ route('peminjaman.index') }}">Peminjaman</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('pengembalian') ? 'active' : '' }}"
                           href="{{ route('pengembalian.index') }}">Pengembalian</a>
                    </li>
                    @endif

                    {{-- Persetujuan, Pemantauan & Cetak Laporan: khusus petugas --}}
                    @if ($currentUser->role === 'petugas')
                    <li class="nav-item">
                        <a class="nav-link {{ is_route('peminjaman.persetujuan') ? 'active' : '' }}"
                           href="{{ route('peminjaman.persetujuan') }}">
                            Persetujuan

                            @if ($pendingCount > 0)
                                <span class="badge rounded-pill bg-danger ms-1 badge-notif">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('peminjaman.pemantauan') ? 'active' : '' }}"
                           href="{{ route('peminjaman.pemantauan') }}">Pemantauan</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('peminjaman.pemantauan.cetak') ? 'active' : '' }}"
                           href="{{ route('peminjaman.pemantauan.cetak') }}"
                           target="_blank">Cetak Laporan</a>
                    </li>
                    @endif

                    {{-- BARU: menu log aktivitas (khusus admin) --}}
                    @if ($isAdmin)
                    <li class="nav-item">
                        <a class="nav-link {{ is_route('admin.log') ? 'active' : '' }}"
                           href="{{ route('admin.log.index') }}">Log aktivitas</a>
                    </li>
                    @endif

                @endif


                @if ($currentUser)

                    <li class="nav-item mt-2 pt-2 border-top">
                        <span class="sidebar-label">Sistem</span>
                    </li>

                    <li class="nav-item mt-1">
                        <a class="nav-link {{ is_route('admin.dashboard', 'dashboard') ? 'active' : '' }}"
                           href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}">Dashboard</a>
                    </li>

                    @if (! $isPengelola)

                        <li class="nav-item">
                            <a class="nav-link {{ is_route('peminjaman.katalog', 'peminjaman.ajukan') ? 'active' : '' }}"
                               href="{{ route('peminjaman.katalog') }}">Katalog alat</a>
                        </li>

                        {{-- DIPERBARUI: peminjam diarahkan ke halaman pengembalian miliknya sendiri --}}
                        <li class="nav-item">
                            <a class="nav-link {{ is_route('peminjaman.pengembalian') ? 'active' : '' }}"
                               href="/pengembalian-saya">Pengembalian</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="/riwayat-peminjaman">Riwayat peminjaman</a>
                        </li>

                    @endif

                @endif

            </ul>

        </div>


        <!-- Bagian bawah: user / login -->
        <div class="border-top pt-2 px-1">

            @if ($currentUser)

                <div class="sidebar-user">

                    <div class="sidebar-avatar">
                        {{ strtoupper(substr($currentUser->username, 0, 1)) }}
                    </div>

                    <div class="overflow-hidden">
                        <div class="text-truncate sidebar-username">{{ $currentUser->username }}</div>
                        <div class="sidebar-role">{{ ucfirst($currentUser->role ?? 'user') }}</div>
                    </div>

                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="btn btn-outline-danger btn-sm w-100 sidebar-btn">
                        Keluar
                    </button>
                </form>

            @else

                @php
                    $dbConnected = false;

                    try {
                        \Sakuci\Database\Connection::pdo();
                        $dbConnected = true;
                    } catch (\Throwable $e) {
                        $dbConnected = false;
                    }

                    $canRegister = false;

                    if ($dbConnected) {
                        try {
                            $canRegister = \App\Models\Role::where('can_register', 1)->exists();
                        } catch (\Throwable $e) {
                            $canRegister = false;
                        }
                    }
                @endphp

                <div class="d-grid gap-1">

                    <a class="btn btn-accent btn-sm sidebar-btn"
                       href="{{ route('login') }}">Masuk</a>

                    @if ($canRegister)
                        <a class="btn btn-accent-outline btn-sm sidebar-btn"
                           href="{{ route('register') }}">Daftar</a>
                    @endif

                </div>

            @endif

        </div>

    </aside>

    <!-- Latar gelap transparan (layar kecil) -->
    <div class="sidebar-backdrop" data-sidebar-toggle></div>


    <!-- Area konten -->
    <div class="app-main">

        <header class="navbar navbar-expand bg-body border-bottom sticky-top px-3 py-1"
                style="min-height: 46px;">

            <!-- Tombol buka/tutup sidebar -->
            <button class="btn btn-outline-secondary btn-sm"
                    type="button"
                    data-sidebar-toggle
                    aria-controls="appSidebar"
                    aria-label="Buka atau tutup sidebar">
                &#9776;
            </button>

            <span class="topbar-title">Lab NET &amp; PC</span>

        </header>

        <main class="container-fluid p-3">
            @include('partials.flash')
            @yield('content')
        </main>

        @include('partials.footer')

    </div>

</div>


<script>
    // Buka-tutup sidebar (animasi diatur CSS). Di layar lebar, pilihan terakhir diingat.
    (function () {
        var wrapper = document.querySelector('.app-wrapper');
        if (!wrapper) return;

        var KEY = 'sidebar-closed';
        var small = window.innerWidth < 992;

        if (small) {
            // Di layar kecil, sidebar awalnya tertutup
            wrapper.classList.add('sidebar-closed');
        } else {
            try {
                if (localStorage.getItem(KEY) === '1') {
                    wrapper.classList.add('sidebar-closed');
                }
            } catch (e) {}
        }

        document.querySelectorAll('[data-sidebar-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                wrapper.classList.toggle('sidebar-closed');

                if (window.innerWidth >= 992) {
                    try {
                        localStorage.setItem(KEY, wrapper.classList.contains('sidebar-closed') ? '1' : '0');
                    } catch (e) {}
                }
            });
        });
    })();
</script>