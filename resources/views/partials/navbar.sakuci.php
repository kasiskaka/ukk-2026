<!-- Styling CSS Kustom untuk Tema Pink Soft, Gold, dan Layout Sidebar Murni CSS/Bootstrap -->
<style>
    :root {
        --sidebar-bg: #fff5f8; /* Soft Pink */
        --sidebar-border: #f3d7e5;
        --sidebar-text: #5a4a52;
        --gold-primary: #d4af37;
        --gold-hover: #c59b27;
        --pink-active: #fae1ed;
    }

    [data-bs-theme="dark"] :root {
        --sidebar-bg: #1f1a1d;
        --sidebar-border: #382d33;
        --sidebar-text: #f0e6ec;
        --pink-active: #3a2732;
    }

    /* Layout Wrapper Fleksibel */
    .app-wrapper {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }

    /* Sidebar Compact (Lebar 220px) */
    .app-sidebar {
        width: 220px;
        min-width: 220px;
        background-color: var(--sidebar-bg);
        border-right: 1px solid var(--sidebar-border);
        font-size: 0.85rem;
        transition: margin-left 0.25s ease;
    }

    /* Efek Collapse Sidebar murni menggunakan collapse class bawaan Bootstrap */
    .app-sidebar.collapse:not(.show) {
        margin-left: -220px;
    }

    /* Area Konten Utama */
    .app-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0; /* Mencegah flex item meluber */
    }

    /* Variasi warna Gold & Navigasi */
    .sidebar-brand-gold {
        color: var(--gold-primary) !important;
        letter-spacing: 0.3px;
    }

    .app-sidebar .nav-pills .nav-link {
        color: var(--sidebar-text);
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 0.82rem;
        transition: background-color 0.2s, color 0.2s;
    }

    .app-sidebar .nav-pills .nav-link:hover {
        background-color: var(--pink-active);
        color: #2c2227;
    }

    .app-sidebar .nav-pills .nav-link.active {
        background: linear-gradient(135deg, var(--gold-primary), var(--gold-hover)) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(212, 175, 55, 0.25);
    }

    .btn-gold {
        background-color: var(--gold-primary);
        border-color: var(--gold-primary);
        color: #ffffff;
    }

    .btn-gold:hover {
        background-color: var(--gold-hover);
        border-color: var(--gold-hover);
        color: #ffffff;
    }

    .text-gold {
        color: var(--gold-primary) !important;
    }

    /* Responsif Mobile: Sidebar otomatis overlay / collapse */
    @media (max-width: 991.98px) {
        .app-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            z-index: 1050;
            margin-left: -220px;
        }
        .app-sidebar.show {
            margin-left: 0 !important;
        }
    }
</style>

<!-- Wrapper Utama Aplikasi -->
<div class="app-wrapper">

    <!-- Sidebar Compact (Menggunakan class collapse bawaan Bootstrap agar bisa buka-tutup tanpa JS) -->
    <aside id="appSidebar" class="app-sidebar collapse show d-flex flex-column justify-content-between p-2 pt-3">
        <div>
            <!-- Header Sidebar -->
            <div class="d-flex align-items-center justify-content-between pb-2 mb-2 px-1 border-bottom">
                <a class="d-flex align-items-center gap-2 text-decoration-none" href="{{ route('home') }}">
                    <div class="rounded bg-white shadow-sm border border-gold d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        💻
                    </div>
                    <div class="overflow-hidden">
                        <span class="fw-bold sidebar-brand-gold fs-7 d-block lh-1">NET & PC</span>
                        <small class="text-muted" style="font-size: 9px;">Lab Peminjaman</small>
                    </div>
                </a>
                <!-- Tombol Tutup Sidebar Khusus Layar Kecil/Mobile -->
                <button type="button" class="btn-close btn-sm d-lg-none" data-bs-toggle="collapse" data-bs-target="#appSidebar" aria-label="Tutup"></button>
            </div>

            <!-- Navigasi Menu Compact dengan Emoji -->
            <ul class="nav nav-pills flex-column gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ is_route('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        🏠 Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('kategori.index') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                        🏷️ Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('alat') ? 'active' : '' }}" href="{{ route('alat.index') }}">
                        🖧 Daftar Alat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('peminjaman') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                        📋 Peminjaman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('pengembalian') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}">
                        🔄 Pengembalian
                    </a>
                </li>

                @php
                    $currentUser = \App\Models\User::current();
                @endphp

                @if ($currentUser)
                    <li class="nav-item mt-2 pt-2 border-top">
                        <small class="text-muted px-2 text-uppercase fw-bold" style="font-size: 9px; letter-spacing: 0.5px;">Sistem</small>
                    </li>
                    <li class="nav-item mt-1">
                        <a class="nav-link {{ is_route('admin.dashboard', 'dashboard') ? 'active' : '' }}"
                           href="{{ $currentUser->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}">
                            📊 Dashboard
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Bagian Bawah Sidebar (User / Auth) -->
        <div class="border-top pt-2 px-1">
            @if ($currentUser)
                <div class="d-flex align-items-center gap-2 mb-2 p-1 bg-white rounded shadow-sm border border-opacity-25">
                    <div class="bg-gold text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 22px; height: 22px; font-size: 10px;">
                        {{ strtoupper(substr($currentUser->username, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-truncate small fw-semibold text-body" style="font-size: 10px;">{{ $currentUser->username }}</div>
                        <div class="text-muted" style="font-size: 9px;">{{ ucfirst($currentUser->role ?? 'user') }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-1" style="font-size: 0.78rem;">
                        🚪 Keluar
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
                    <a class="btn btn-gold btn-sm rounded-pill py-1 shadow-sm text-white" style="font-size: 0.78rem;" href="{{ route('login') }}">
                        🔑 Masuk
                    </a>
                    @if ($canRegister)
                        <a class="btn btn-outline-secondary btn-sm rounded-pill py-1 text-center" style="font-size: 0.78rem;" href="{{ route('register') }}">
                            📝 Daftar
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </aside>

    <!-- Area Konten Utama -->
    <div class="app-main">
        <!-- Top Navbar dengan Tombol Toggle Bootstrap Collapse (Tanpa JS Custom) -->
        <header class="navbar navbar-expand bg-body border-bottom sticky-top px-3 py-1 shadow-sm" style="min-height: 46px;">
            <div class="d-flex align-items-center gap-2">
                <!-- Tombol Collapse Sidebar Murni Atribut Bootstrap -->
                <button class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center p-1" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#appSidebar" 
                        aria-expanded="true" 
                        aria-controls="appSidebar"
                        aria-label="Toggle Sidebar"
                        style="width: 28px; height: 28px; font-size: 13px;">
                    ☰
                </button>
                
                
            </div>

            <!-- Kontrol Kanan (Tema & Status DB) -->
            <div class="ms-auto d-flex align-items-center gap-2">
                @php
                    $dbConnected = isset($dbConnected) ? $dbConnected : false;
                    try {
                        \Sakuci\Database\Connection::pdo();
                        $dbConnected = true;
                    } catch (\Throwable $e) {
                        $dbConnected = false;
                    }
                @endphp
    

            </div>
        </header>

        <!-- Area Konten Halaman -->
        <main class="container-fluid p-3">
            @yield('content')
        </main>
    </div>
</div>