/**
 * WanderMed – dashboard-faskes.js
 * Semua logika JavaScript untuk Dashboard Mitra Faskes.
 *
 * CATATAN: URL endpoint dibaca dari data attributes pada elemen HTML
 * agar tidak bergantung pada Laravel Blade template rendering.
 * Tambahkan data-url-status dan data-url-fasilitas pada elemen #faskesApp di blade.
 */
(function () {
    var appEl          = document.getElementById('faskesApp');
    var urlStatus      = appEl ? appEl.getAttribute('data-url-status')    : '/faskes/status';
    var urlFasilitas   = appEl ? appEl.getAttribute('data-url-fasilitas') : '/faskes/fasilitas';

    // =========================================================
    // TAB NAVIGATION (Sidebar Click → Show Section)
    // =========================================================
    var faskesNavMap = {
        'navDashboard'    : 'sectionDashboard',
        'navKontrolStatus': 'sectionKontrolStatus',
        'navFasilitas'    : 'sectionFasilitas',
        'navProfilFaskes' : 'sectionProfil',
        'navKoordinat'    : 'sectionKoordinat',
    };

    function switchSection(navId, sectionId) {
        document.querySelectorAll('.wm-nav-link').forEach(function(l) { l.classList.remove('active'); });
        document.querySelectorAll('.faskes-section').forEach(function(s) { s.style.display = 'none'; });
        var navEl = document.getElementById(navId);
        if (navEl) navEl.classList.add('active');
        var secEl = document.getElementById(sectionId);
        if (secEl) {
            secEl.style.display = 'block';
            secEl.classList.remove('wm-section-animate');
            void secEl.offsetWidth;
            secEl.classList.add('wm-section-animate');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('.wm-nav-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            var navId = this.id;
            if (!faskesNavMap[navId]) return;
            e.preventDefault();
            switchSection(navId, faskesNavMap[navId]);
        });
    });

    // =========================================================
    // AJAX Toggle Status
    // =========================================================
    window.handleAjaxToggle = function(field, value, switchId, labelId, textOn, textOff, colorOn, colorOff, toastMsg) {
        var sw    = document.getElementById(switchId);
        var label = document.getElementById(labelId);
        if (sw && label) {
            if (sw.checked) { label.textContent = textOn;  label.style.color = colorOn;  }
            else            { label.textContent = textOff; label.style.color = colorOff; }
        }
        fetch(urlStatus, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body   : JSON.stringify({ field: field, value: value })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var el = document.getElementById('lastUpdatedLabel');
            if (el) {
                var now = new Date();
                el.innerHTML = '<i class="fas fa-clock"></i> Diperbarui ' + now.getHours() + ':' + String(now.getMinutes()).padStart(2,'0') + ' WIB';
            }
            showToast(data.message || toastMsg);
            setTimeout(function() { location.reload(); }, 1500);
        })
        .catch(function(e) { console.error(e); showToast('Gagal menyimpan. Coba lagi.', 'danger'); });
    };

    // =========================================================
    // Simpan Pengumuman
    // =========================================================
    window.savePengumuman = function() {
        var text = document.getElementById('inputPengumuman').value;
        fetch(urlStatus, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body   : JSON.stringify({ field: 'pengumuman', value: text })
        })
        .then(function(r) { return r.json(); })
        .then(function() { showToast('Pengumuman berhasil disiarkan ke peta!'); setTimeout(function() { location.reload(); }, 1500); })
        .catch(function() { showToast('Gagal menyimpan pengumuman.', 'danger'); });
    };

    // =========================================================
    // Toggle Checklist Fasilitas (UI Only)
    // =========================================================
    window.toggleCheck = function(item) {
        item.classList.toggle('checked');
        var checkbox = item.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = !checkbox.checked;
    };

    // =========================================================
    // Simpan Fasilitas via AJAX
    // =========================================================
    window.saveFasilitas = function() {
        var checkboxes    = document.querySelectorAll('#fasilitasGrid input[type="checkbox"]:checked');
        var checkedValues = Array.from(checkboxes).map(function(cb) { return cb.value; });
        fetch(urlFasilitas, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body   : JSON.stringify({ layanan_tersedia: checkedValues })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) { showToast(data.message || 'Fasilitas diperbarui dan tampil di peta!'); setTimeout(function() { location.reload(); }, 1500); })
        .catch(function() { showToast('Gagal menyimpan fasilitas.', 'danger'); });
    };

    // =========================================================
    // GPS Auto-fill Koordinat
    // =========================================================
    window.fillGPS = function() {
        if (!navigator.geolocation) { showToast('Browser tidak mendukung GPS.', 'danger'); return; }
        showToast('Mendeteksi lokasi GPS...');
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('quickLat').value = pos.coords.latitude.toFixed(6);
            document.getElementById('quickLng').value = pos.coords.longitude.toFixed(6);
            showToast('Koordinat GPS berhasil diisi! Klik Simpan untuk menyimpan.');
        }, function() {
            showToast('Gagal mendapat lokasi GPS. Izin ditolak atau tidak tersedia.', 'danger');
        });
    };

    window.getCurrentLocation = function() {
        if (!navigator.geolocation) { showToast('Browser tidak mendukung GPS.', 'danger'); return; }
        showToast('Mendeteksi lokasi GPS...');
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('inputLat').value = pos.coords.latitude.toFixed(6);
            document.getElementById('inputLng').value = pos.coords.longitude.toFixed(6);
            showToast('Koordinat GPS telah diisi. Klik "Simpan & Perbarui ke Peta".');
        }, function() {
            showToast('Gagal mendapat lokasi GPS.', 'danger');
        });
    };
})();
