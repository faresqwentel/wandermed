/**
 * WanderMed – animations.js
 * Inisialisasi AOS (Animate On Scroll) dan helper animasi lainnya.
 * Di-load di akhir halaman yang memerlukan animasi scroll (landing page, daftar).
 */
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // 1. AOS – Animate On Scroll
    // Hanya aktif jika AOS library sudah dimuat dan ada elemen [data-aos]
    // ============================================================
    if (typeof AOS !== 'undefined' && document.querySelector('[data-aos]')) {
        AOS.init({
            duration : 600,    // durasi animasi (ms)
            once     : true,   // animasi hanya sekali
            offset   : 60,     // jarak scroll sebelum trigger (px)
            easing   : 'ease-out-cubic',
        });
    }

    // ============================================================
    // 2. STAGGER ANIMATION – Animasi berurutan untuk item list
    // Digunakan di halaman daftar faskes/pariwisata
    // ============================================================
    const staggerItems = document.querySelectorAll('[data-stagger]');
    staggerItems.forEach(function (item, i) {
        item.style.animationDelay = (i * 60) + 'ms';
        item.classList.add('wm-stagger-item');
    });

    // ============================================================
    // 3. COUNTER ANIMATION – Angka stat card naik perlahan
    // ============================================================
    document.querySelectorAll('[data-counter]').forEach(function (el) {
        const target = parseInt(el.getAttribute('data-counter')) || 0;
        if (target === 0) { el.textContent = '0'; return; }
        let current  = 0;
        const step   = Math.max(1, Math.ceil(target / 50));
        const timer  = setInterval(function () {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString('id-ID');
            if (current >= target) clearInterval(timer);
        }, 25);
    });

    // ============================================================
    // 4. LEAFLET MARKER BOUNCE HELPER (dipanggil dari map-core)
    // ============================================================
    window.addBounceClass = function (markerElement) {
        if (!markerElement) return;
        markerElement.classList.add('wm-marker-bounce');
        setTimeout(function () {
            markerElement.classList.remove('wm-marker-bounce');
        }, 800);
    };
});
