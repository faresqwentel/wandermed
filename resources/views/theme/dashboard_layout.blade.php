<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WanderMed – Sistem Pemetaan Medis Wisatawan Subang">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WanderMed – @yield('page_title', 'Dashboard')</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome (Ikon) -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <!-- Bootstrap 4 CSS (untuk modul seperti Modal & Grid system) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- WanderMed Dashboard CSS Kustom (tanpa SB Admin) -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    <!-- AOS Animate On Scroll -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    @stack('styles')

    {{-- Dark Mode Init: Terapkan class SEBELUM render untuk hindari flash --}}
    <script>
        (function() {
            if (localStorage.getItem('wm_dark_mode') === '1') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body>

<!-- Overlay untuk Mobile Sidebar -->
<div class="wm-overlay" id="wmOverlay" onclick="toggleSidebar()"></div>

<!-- Toast Notification Global -->
<div class="wm-toast" id="wmToast">
    <i class="fas fa-check-circle"></i>
    <span id="wmToastMsg">Perubahan tersimpan!</span>
</div>

<!-- Shell Utama -->
<div class="wm-shell">

    {{-- ========================== SIDEBAR ========================== --}}
    <aside class="wm-sidebar" id="wmSidebar">
        <!-- Brand -->
        <div class="wm-sidebar-brand">
            <div class="wm-brand-icon">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div>
                <div class="wm-brand-text">WanderMed</div>
                <span class="wm-brand-badge">@yield('badge_role', 'Platform')</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="wm-nav">
            @yield('sidebar_nav')
        </nav>

        <!-- User Footer -->
        <div class="wm-sidebar-footer">
            <div class="wm-user-mini">
                <div class="wm-avatar">
                    @yield('user_initial', 'A')
                </div>
                <div>
                    <div class="wm-user-name">@yield('user_name', 'Pengguna')</div>
                    <div class="wm-user-role">@yield('user_role', 'WanderMed')</div>
                </div>
                <a href="/logout" class="wm-logout-btn" title="Keluar dari Sistem">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </aside>
    {{-- ======================== END SIDEBAR ======================== --}}

    <!-- Main Area -->
    <div class="wm-main">

        {{-- ======================== TOPBAR ========================== --}}
        <header class="wm-topbar">
            <!-- Mobile Toggle -->
            <button class="wm-mobile-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Page Title -->
            <div class="wm-topbar-title">
                {!! $__env->yieldContent('topbar_title', 'Dashboard') !!}
            </div>

            <!-- Search -->
            <div class="wm-search d-none d-md-block">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari sesuatu...">
            </div>

            <!-- Notification Bell -->
            <div class="wm-topbar-icon">
                <i class="fas fa-bell"></i>
                <span class="dot"></span>
            </div>

            <!-- Dark Mode Toggle -->
            <button class="wm-darkmode-btn" id="darkModeToggle" title="Ganti tema gelap/terang" onclick="toggleDarkMode()">
                <i class="fas fa-moon" id="darkModeIcon"></i>
            </button>

            <!-- Map Quick Link -->
            <a href="/peta-faskes" class="wm-btn ghost sm" title="Buka Peta">
                <i class="fas fa-map-marked-alt"></i>
                <span class="d-none d-md-inline">Peta</span>
            </a>
        </header>
        {{-- ======================== END TOPBAR ======================== --}}

        <!-- Content -->
        <main class="wm-content">
            @yield('content')
        </main>

    </div>
    <!-- End Main -->

</div>
<!-- End Shell -->

<!-- jQuery (dari CDN untuk kestabilan) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap 4 Bundle JS (mencakup Popper.js dan Modal component) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

<!-- AOS Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- WanderMed Global JS (sidebar, toast, dark mode, page transitions) -->
<script src="{{ asset('js/global.js') }}"></script>

<!-- WanderMed Animations (AOS init, counters, stagger) -->
<script src="{{ asset('js/animations.js') }}"></script>

@stack('scripts')
</body>
</html>
