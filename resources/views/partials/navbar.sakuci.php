{{-- CSS sidebar dan layout ada di public/css/app.css --}}

@php
    $currentUser = \App\Models\User::current();
    $isAdmin = $currentUser && $currentUser->role === 'admin';

    // Hitung pengajuan Pending untuk notifikasi admin
    $pendingCount = 0;

    if ($isAdmin) {
        foreach (\App\Models\Peminjaman::all() as $pn) {
            if (strtolower($pn->status) === 'pending') {
                $pendingCount++;
            }
        }
    }
@endphp


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

                @if ($isAdmin)

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('home') ? 'active' : '' }}"
                           href="{{ route('home') }}">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('kategori.index') ? 'active' : '' }}"
                           href="{{ route('kategori.index') }}">Kategori</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('alat') ? 'active' : '' }}"
                           href="{{ route('alat.index') }}">Daftar alat</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('peminjaman') ? 'active' : '' }}"
                           href="{{ route('peminjaman.index') }}">
                            Peminjaman

                            @if ($pendingCount > 0)
                                <span class="badge rounded-pill bg-danger ms-1 badge-notif">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ is_route('pengembalian') ? 'active' : '' }}"
                           href="{{ route('pengembalian.index') }}">Pengembalian</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="/riwayat-peminjaman">Riwayat peminjaman</a>
                    </li>

                @endif


                @if ($currentUser)

                    <li class="nav-item mt-2 pt-2 border-top">
                        <span class="sidebar-label">Sistem</span>
                    </li>

                    <li class="nav-item mt-1">
                        <a class="nav-link {{ is_route('admin.dashboard', 'dashboard') ? 'active' : '' }}"
                           href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}">Dashboard</a>
                    </li>

                    @if (! $isAdmin)

                        <li class="nav-item">
                            <a class="nav-link {{ is_route('peminjaman.katalog', 'peminjaman.ajukan') ? 'active' : '' }}"
                               href="{{ route('peminjaman.katalog') }}">Ajukan peminjaman</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ is_route('pengembalian') ? 'active' : '' }}"
                               href="{{ route('pengembalian.index') }}">Pengembalian</a>
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

        </header>

        <main class="container-fluid p-3">
            @include('partials.flash')
            @yield('content')
        </main>

         @include('partials.footer')    

</div>


<script>
    // Buka-tutup sidebar (animasi diatur CSS lewat margin-left)
    (function () {
        var wrapper = document.querySelector('.app-wrapper');
        if (!wrapper) return;

        // Di layar kecil, sidebar awalnya tertutup
        if (window.innerWidth < 992) {
            wrapper.classList.add('sidebar-closed');
        }

        document.querySelectorAll('[data-sidebar-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                wrapper.classList.toggle('sidebar-closed');
            });
        });
    })();
</script>