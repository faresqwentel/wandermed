<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WanderMed – Sistem Pemetaan Medis Wisatawan Subang">
    <title>WanderMed – @yield('page_title', 'Dashboard')</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome (Ikon) -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">

    <!-- WanderMed Dashboard CSS Kustom (tanpa SB Admin) -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    @stack('styles')
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
                <a href="/" class="wm-logout-btn" title="Keluar dari Sistem">
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

<!-- jQuery -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

<!-- Global JS: Toggle Sidebar + Toast Helper -->
<script>
    // Fungsi buka/tutup sidebar di layar kecil
    function toggleSidebar() {
        const sidebar = document.getElementById('wmSidebar');
        const overlay = document.getElementById('wmOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    }

    // Fungsi global menampilkan toast notifikasi
    function showToast(msg = 'Perubahan tersimpan!') {
        const toast = document.getElementById('wmToast');
        const msgEl = document.getElementById('wmToastMsg');
        msgEl.textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
</script>

@stack('scripts')
</body>
</html>
