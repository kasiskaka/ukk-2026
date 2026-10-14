<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    {{-- Tema dikunci terang (soft pink). Mode gelap dimatikan. --}}
    <script>
        (function () {
            try { localStorage.setItem('sakuci-theme', 'light'); } catch (e) {}
            document.documentElement.setAttribute('data-bs-theme', 'light');
        })();
    </script>

    {{-- Bootstrap 5.3.8 -- file lokal, tidak butuh internet --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Tema soft pink + soft blue + semi gold (berlaku di semua halaman) --}}
    <style>
        :root,
        :root[data-bs-theme="light"],
        :root[data-bs-theme="dark"] {
            --pk-50: #fff6fa;
            --pk-100: #ffe6f0;
            --pk-200: #fccfe0;
            --pk-400: #f59abb;
            --pk-500: #ec6a9a;
            --pk-plum: #6d2b52;
            --pk-plum-2: #a14577;
            --pk-blue: #c6e2fa;
            --pk-gold: #d9a441;
            --pk-gold-2: #f3d58a;

            --bs-body-bg: #fff3f8;
            --bs-body-bg-rgb: 255, 243, 248;
            --bs-body-color: #6d2b52;
            --bs-body-color-rgb: 109, 43, 82;
            --bs-emphasis-color: #6d2b52;
            --bs-secondary-color: #98677f;
            --bs-secondary-bg: #ffe6f0;
            --bs-tertiary-bg: #fff3f8;
            --bs-tertiary-bg-rgb: 255, 243, 248;
            --bs-border-color: rgba(109, 43, 82, .15);
            --bs-primary: #ec6a9a;
            --bs-primary-rgb: 236, 106, 154;
            --bs-link-color: #a14577;
            --bs-link-color-rgb: 161, 69, 119;
            --bs-link-hover-color: #ec6a9a;
            --bs-link-hover-color-rgb: 236, 106, 154;
        }

        html, body {
            min-height: 100%;
            background: linear-gradient(180deg, #fff3f8 0%, #ffe3ee 55%, #e6f2fd 100%) fixed !important;
            color: var(--pk-plum);
        }

        /* Navbar */
        .navbar,
        body > nav {
            background: linear-gradient(120deg, #fde3ee, #fcc9dc) !important;
            border-bottom: 1px solid rgba(109, 43, 82, .15) !important;
            box-shadow: 0 6px 18px rgba(161, 69, 119, .12);
            --bs-navbar-color: #6d2b52;
            --bs-navbar-hover-color: #ec6a9a;
            --bs-navbar-active-color: #ec6a9a;
            --bs-navbar-brand-color: #6d2b52;
            --bs-navbar-brand-hover-color: #ec6a9a;
        }

        .navbar a,
        .navbar .navbar-brand,
        .navbar .nav-link,
        body > nav a {
            color: #6d2b52 !important;
            font-weight: 600;
        }

        .navbar a:hover,
        .navbar .nav-link:hover,
        .navbar .nav-link.active { color: #ec6a9a !important; }

        .navbar-toggler {
            border-color: #a14577 !important;
            background: rgba(255, 255, 255, .55) !important;
            color: #6d2b52 !important;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%236d2b52' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
        }

        /* Halaman utama (/) : sembunyikan navbar layout, home punya bar sendiri */
        body.hide-nav .navbar,
        body.hide-nav > nav,
        body.hide-nav > header,
        body.hide-nav .navbar-toggler,
        body.hide-nav .navbar-collapse,
        /* semua elemen yang berada SEBELUM bagian home (.lp), apa pun tag-nya */
        body.hide-nav *:has(~ .lp),
        body.hide-nav *:has(~ *:has(.lp)) {
            display: none !important;
        }

        /* Tombol & komponen umum */
        .btn-primary {
            --bs-btn-bg: #ec6a9a;
            --bs-btn-border-color: #ec6a9a;
            --bs-btn-hover-bg: #d6558a;
            --bs-btn-hover-border-color: #d6558a;
            --bs-btn-active-bg: #c04a7b;
            --bs-btn-active-border-color: #c04a7b;
            --bs-btn-color: #fff;
            --bs-btn-hover-color: #fff;
        }

        .btn-outline-primary {
            --bs-btn-color: #a14577;
            --bs-btn-border-color: #ec6a9a;
            --bs-btn-hover-bg: #ec6a9a;
            --bs-btn-hover-border-color: #ec6a9a;
            --bs-btn-hover-color: #fff;
        }

        .card { border-color: rgba(109, 43, 82, .12); border-radius: 18px; }
        .form-control:focus, .form-select:focus {
            border-color: #f59abb;
            box-shadow: 0 0 0 .25rem rgba(236, 106, 154, .2);
        }
    </style>
</head>
<body class="bg-body-tertiary {{ (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) === '/') ? 'hide-nav' : '' }}">

@include('partials.navbar')

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>
@yield('scripts')

</body>
</html>