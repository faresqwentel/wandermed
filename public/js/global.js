/**
 * WanderMed – global.js
 * Berisi fungsi-fungsi global yang digunakan di semua halaman dashboard.
 * Dipanggil oleh theme/dashboard_layout.blade.php
 */

// ============================================================
// 1. DARK MODE
// Apply icon sesuai state saat halaman pertama dimuat
// ============================================================
(function () {
    const icon = document.getElementById('darkModeIcon');
    if (icon && document.documentElement.classList.contains('dark')) {
        icon.className = 'fas fa-sun';
    }
})();

window.toggleDarkMode = function () {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('wm_dark_mode', isDark ? '1' : '0');
    if (icon) icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
};

// ============================================================
// 2. MOBILE SIDEBAR TOGGLE
// ============================================================
window.toggleSidebar = function () {
    const sidebar = document.getElementById('wmSidebar');
    const overlay = document.getElementById('wmOverlay');
    if (sidebar) sidebar.classList.toggle('open');
    if (overlay) overlay.classList.toggle('show');
};

// ============================================================
// 3. TOAST NOTIFICATION
// ============================================================
window.showToast = function (msg, type) {
    const toast  = document.getElementById('wmToast');
    const msgEl  = document.getElementById('wmToastMsg');
    const icon   = toast ? toast.querySelector('i') : null;

    if (!toast || !msgEl) return;

    msgEl.textContent = msg || 'Perubahan tersimpan!';

    // Tipe: 'success' (default), 'danger', 'info'
    toast.className = 'wm-toast';
    if (type === 'danger') {
        toast.classList.add('toast-danger');
        if (icon) icon.className = 'fas fa-times-circle';
    } else if (type === 'info') {
        toast.classList.add('toast-info');
        if (icon) icon.className = 'fas fa-info-circle';
    } else {
        if (icon) icon.className = 'fas fa-check-circle';
    }

    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3200);
};

// ============================================================
// 4. PAGE TRANSITION (Fade Out saat navigasi keluar)
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    // Fade-in body saat halaman selesai load
    document.body.style.opacity = '0';
    requestAnimationFrame(() => {
        document.body.style.transition = 'opacity 0.22s ease';
        document.body.style.opacity    = '1';
    });

    // Fade-out saat klik link navigasi ke halaman lain
    document.querySelectorAll('a[href]').forEach(function (link) {
        const href = link.getAttribute('href');
        // Skip: anchor, javascript, external, logout (supaya cepat)
        if (!href || href.startsWith('#') || href.startsWith('javascript') ||
            href.includes('logout') || (link.hostname && link.hostname !== window.location.hostname)) {
            return;
        }
        link.addEventListener('click', function (e) {
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            e.preventDefault();
            document.body.style.transition = 'opacity 0.18s ease';
            document.body.style.opacity    = '0';
            const dest = this.href;
            setTimeout(function () { window.location.href = dest; }, 185);
        });
    });
});
