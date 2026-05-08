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

    // ── LOGOUT CONFIRMATION (SweetAlert2) ─────────────────────
    document.querySelectorAll('a[href="/logout"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const dest = this.href;

            Swal.fire({
                title: 'Keluar dari Akun?',
                html: `<p style="color:#94a3b8;font-size:14px;margin:0;line-height:1.6;">
                          Sesi Anda akan diakhiri dan Anda akan diarahkan<br>ke halaman login.
                       </p>`,
                icon: 'warning',
                iconColor: '#ff7a00',
                background: '#111827',
                color: '#e8ecf4',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Ya, Keluar',
                cancelButtonText: '<i class="fas fa-times" style="margin-right:6px;"></i>Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup:         'wm-swal-popup',
                    title:         'wm-swal-title',
                    confirmButton: 'wm-swal-confirm',
                    cancelButton:  'wm-swal-cancel',
                    icon:          'wm-swal-icon',
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster',
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster',
                },
            }).then(function (result) {
                if (result.isConfirmed) {
                    document.body.style.transition = 'opacity 0.18s ease';
                    document.body.style.opacity    = '0';
                    setTimeout(function () { window.location.href = dest; }, 185);
                }
            });
        });
    });
    // ──────────────────────────────────────────────────────────

    // Fade-out saat klik link navigasi ke halaman lain
    document.querySelectorAll('a[href]').forEach(function (link) {
        const href = link.getAttribute('href');
        // Skip: anchor, javascript, external, logout
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

    // ── SEARCH BAR ────────────────────────────────────────────
    initSearchBar();
});

// ============================================================
// 5. SEARCH BAR – Sidebar Navigation
// ============================================================
function initSearchBar() {
    const searchBox  = document.getElementById('wmSearchBox');
    const dropdown   = document.getElementById('wmSearchDropdown');
    if (!searchBox || !dropdown) return;

    // Collect only SPA nav links (href="#"), exclude external/logout
    const navLinks = Array.from(document.querySelectorAll('.wm-nav-link')).filter(function (link) {
        const href = link.getAttribute('href');
        return href === '#';
    });

    searchBox.addEventListener('input', function () {
        const query = this.value.trim().toLowerCase();
        if (!query) { dropdown.style.display = 'none'; return; }

        const matches = navLinks.filter(function (link) {
            return link.textContent.trim().toLowerCase().includes(query);
        });

        if (matches.length === 0) {
            dropdown.innerHTML =
                '<div class="wm-search-empty"><i class="fas fa-search"></i>Tidak ada menu yang cocok</div>';
        } else {
            dropdown.innerHTML = matches.map(function (link) {
                const iconEl  = link.querySelector('i');
                const iconCls = iconEl ? iconEl.className : 'fas fa-circle';
                const label   = link.textContent.trim().replace(/\s+/g, ' ');
                return '<div class="wm-search-result" data-nav-id="' + (link.id || '') + '" onclick="wmSearchNavigate(this)">' +
                           '<i class="' + iconCls + '"></i><span>' + label + '</span>' +
                       '</div>';
            }).join('');
        }
        dropdown.style.display = 'block';
    });

    searchBox.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            dropdown.style.display = 'none';
            this.value = '';
        } else if (e.key === 'Enter') {
            const first = dropdown.querySelector('.wm-search-result');
            if (first) first.click();
        }
    });

    // Click outside → close
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.wm-search')) {
            dropdown.style.display = 'none';
        }
    });
}

window.wmSearchNavigate = function (el) {
    const navId    = el.getAttribute('data-nav-id');
    const dropdown = document.getElementById('wmSearchDropdown');
    const searchBox = document.getElementById('wmSearchBox');
    if (navId) {
        const navLink = document.getElementById(navId);
        if (navLink) navLink.click();
    }
    if (dropdown)  dropdown.style.display = 'none';
    if (searchBox) searchBox.value = '';
};

// ============================================================
// 6. NOTIFICATION BELL PANEL
// ============================================================
window.toggleNotifPanel = function () {
    const panel = document.getElementById('wmNotifPanel');
    if (!panel) return;
    panel.classList.toggle('open');
};

// Click outside bell → close panel
document.addEventListener('click', function (e) {
    const wrap = document.getElementById('wmBellWrap');
    if (wrap && !wrap.contains(e.target)) {
        const panel = document.getElementById('wmNotifPanel');
        if (panel) panel.classList.remove('open');
    }
});

