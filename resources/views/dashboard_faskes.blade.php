{{-- ============================================================
     Dashboard Mitra Faskes – WanderMed
     Layout: theme/dashboard_layout.blade.php (Kustom, no SB Admin)
     ============================================================ --}}
@extends('theme.dashboard_layout')

@section('page_title', 'Dashboard Faskes')
@section('badge_role', 'Mitra Faskes')
@section('user_name', 'Klinik Kasih Ibu')
@section('user_role', 'Mitra Fasilitas Kesehatan')
@section('user_initial', 'K')
@section('topbar_title', 'Dashboard <span>Operasional Faskes</span>')

@section('sidebar_nav')
    <div class="wm-nav-label">Operasional</div>
    <a href="/dashboard/faskes" class="wm-nav-link active">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="#" class="wm-nav-link">
        <i class="fas fa-toggle-on"></i> Kontrol Status
    </a>
    <a href="#" class="wm-nav-link">
        <i class="fas fa-clipboard-list"></i> Fasilitas & Layanan
    </a>
    <div class="wm-nav-label">Profil</div>
    <a href="#" class="wm-nav-link">
        <i class="fas fa-hospital"></i> Profil Faskes
    </a>
    <a href="#" class="wm-nav-link">
        <i class="fas fa-map-pin"></i> Update Koordinat
    </a>
    <div class="wm-nav-label">Navigasi</div>
    <a href="/peta-faskes" class="wm-nav-link">
        <i class="fas fa-map-marked-alt"></i> Lihat di Peta
    </a>
    <a href="/login" class="wm-nav-link">
        <i class="fas fa-sign-out-alt"></i> Keluar
    </a>
@endsection

@section('content')

    <!-- Page Header -->
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Panel Kontrol Faskes</div>
            <div class="wm-page-subtitle">Perbarui status real-time agar wisatawan mendapat informasi akurat di peta</div>
        </div>
        <button class="wm-btn orange" onclick="showToast('Semua perubahan telah disimpan!')">
            <i class="fas fa-save"></i> Simpan Semua
        </button>
    </div>

    <!-- Stat Cards -->
    <div class="wm-stat-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="wm-stat-card blue">
            <div class="wm-stat-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="wm-stat-value">145</div>
                <div class="wm-stat-label">Wisatawan Bulan Ini</div>
            </div>
        </div>
        <div class="wm-stat-card yellow">
            <div class="wm-stat-icon"><i class="fas fa-star"></i></div>
            <div>
                <div class="wm-stat-value">4.8<span style="font-size:13px; color: var(--text-muted);">/5</span></div>
                <div class="wm-stat-label">Rating Rata-rata</div>
            </div>
        </div>
        <div class="wm-stat-card green">
            <div class="wm-stat-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="wm-stat-value">89</div>
                <div class="wm-stat-label">Total Ulasan</div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 22px; align-items: start;">

        <!-- KIRI: Quick Controls -->
        <div class="wm-card">
            <div class="wm-card-header">
                <div class="wm-card-title"><i class="fas fa-sliders-h"></i> Kontrol Status Real-time</div>
                <span id="lastUpdatedLabel" style="font-size: 11px; color: var(--text-muted);">
                    <i class="fas fa-clock mr-1"></i> Hari ini, 08:30 WIB
                </span>
            </div>
            <div class="wm-card-body">
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 18px;">
                    Perbaruan status ini langsung memengaruhi tampilan di peta publik WanderMed.
                </p>

                <!-- Toggle: Operasional -->
                <div class="wm-toggle-row">
                    <div class="wm-toggle-info">
                        <h6>Status Operasional Klinik</h6>
                        <p>Apakah faskes sedang buka dan dapat menerima pasien?</p>
                    </div>
                    <div class="wm-toggle-group">
                        <span class="wm-toggle-label" id="labelOps" style="color: #1cc88a;">✓ BUKA</span>
                        <label class="wm-switch">
                            <input type="checkbox" id="switchOps" checked
                                onchange="handleToggle('switchOps', 'labelOps', '✓ BUKA', '✕ TUTUP SEMENTARA', '#1cc88a', '#e74a3b', 'Status Operasional diperbarui!')">
                            <span class="wm-switch-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Toggle: BPJS -->
                <div class="wm-toggle-row">
                    <div class="wm-toggle-info">
                        <h6>Penerimaan BPJS Kesehatan</h6>
                        <p>Sedang menerima pasien peserta BPJS saat ini?</p>
                    </div>
                    <div class="wm-toggle-group">
                        <span class="wm-toggle-label" id="labelBPJS" style="color: #1cc88a;">✓ TERIMA BPJS</span>
                        <label class="wm-switch">
                            <input type="checkbox" id="switchBPJS" checked
                                onchange="handleToggle('switchBPJS', 'labelBPJS', '✓ TERIMA BPJS', '✕ TIDAK TERIMA', '#1cc88a', '#e74a3b', 'Status BPJS diperbarui!')">
                            <span class="wm-switch-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Toggle: UGD 24 Jam -->
                <div class="wm-toggle-row">
                    <div class="wm-toggle-info">
                        <h6>Layanan UGD 24 Jam</h6>
                        <p>Unit Gawat Darurat aktif saat ini?</p>
                    </div>
                    <div class="wm-toggle-group">
                        <span class="wm-toggle-label" id="labelUGD" style="color: #1cc88a;">✓ AKTIF</span>
                        <label class="wm-switch">
                            <input type="checkbox" id="switchUGD" checked
                                onchange="handleToggle('switchUGD', 'labelUGD', '✓ AKTIF', '✕ NONAKTIF', '#1cc88a', '#e74a3b', 'Status UGD diperbarui!')">
                            <span class="wm-switch-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Pengumuman Khusus -->
                <div class="wm-form-group" style="margin-top: 20px;">
                    <label class="wm-label">Pengumuman Sementara untuk Wisatawan</label>
                    <textarea class="wm-textarea" rows="3" placeholder="Contoh: Stok oksigen terbatas hari ini. Poli gigi libur.">Stok oksigen aman. Praktek dokter gigi libur Selasa.</textarea>
                </div>
                <button class="wm-btn orange" style="width:100%;" onclick="showToast('Status operasional berhasil diperbarui!')">
                    <i class="fas fa-broadcast-tower"></i> Terapkan & Siaran Ulang ke Peta
                </button>
            </div>
        </div>

        <!-- KANAN: Manajemen Fasilitas -->
        <div class="wm-card">
            <div class="wm-card-header">
                <div class="wm-card-title"><i class="fas fa-clipboard-list"></i> Manajemen Fasilitas</div>
            </div>
            <div class="wm-card-body">
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 18px;">
                    Centang fasilitas yang <strong style="color: #1cc88a;">aktif tersedia</strong> di faskes Anda hari ini.
                </p>

                <!-- Checklist Fasilitas -->
                <div class="wm-check-grid" id="fasilitasGrid">
                    <div class="wm-check-item checked" onclick="toggleCheck(this)">
                        <div class="wm-check-box"><i class="fas fa-check"></i></div>
                        <span class="wm-check-label"><i class="fas fa-ambulance mr-1" style="color: #e74a3b;"></i> UGD 24 Jam</span>
                        <input type="checkbox" checked>
                    </div>
                    <div class="wm-check-item checked" onclick="toggleCheck(this)">
                        <div class="wm-check-box"><i class="fas fa-check"></i></div>
                        <span class="wm-check-label"><i class="fas fa-car mr-1" style="color: #4e73df;"></i> Ambulans</span>
                        <input type="checkbox" checked>
                    </div>
                    <div class="wm-check-item" onclick="toggleCheck(this)">
                        <div class="wm-check-box"><i class="fas fa-check"></i></div>
                        <span class="wm-check-label"><i class="fas fa-bed mr-1" style="color: #36b9cc;"></i> Rawat Inap</span>
                        <input type="checkbox">
                    </div>
                    <div class="wm-check-item checked" onclick="toggleCheck(this)">
                        <div class="wm-check-box"><i class="fas fa-check"></i></div>
                        <span class="wm-check-label"><i class="fas fa-pills mr-1" style="color: #1cc88a;"></i> Apotek / Farmasi</span>
                        <input type="checkbox" checked>
                    </div>
                    <div class="wm-check-item checked" onclick="toggleCheck(this)">
                        <div class="wm-check-box"><i class="fas fa-check"></i></div>
                        <span class="wm-check-label"><i class="fas fa-flask mr-1" style="color: #f6c23e;"></i> Laboratorium</span>
                        <input type="checkbox" checked>
                    </div>
                    <div class="wm-check-item" onclick="toggleCheck(this)">
                        <div class="wm-check-box"><i class="fas fa-check"></i></div>
                        <span class="wm-check-label"><i class="fas fa-user-md mr-1" style="color: var(--orange);"></i> Dok. Spesialis</span>
                        <input type="checkbox">
                    </div>
                    <div class="wm-check-item checked" onclick="toggleCheck(this)">
                        <div class="wm-check-box"><i class="fas fa-check"></i></div>
                        <span class="wm-check-label"><i class="fas fa-baby mr-1" style="color: #e74a3b;"></i> Poli Anak</span>
                        <input type="checkbox" checked>
                    </div>
                    <div class="wm-check-item" onclick="toggleCheck(this)">
                        <div class="wm-check-box"><i class="fas fa-check"></i></div>
                        <span class="wm-check-label"><i class="fas fa-tooth mr-1" style="color: #4e73df;"></i> Poli Gigi</span>
                        <input type="checkbox">
                    </div>
                </div>

                <button class="wm-btn success" style="width:100%; margin-top: 8px;" onclick="showToast('Daftar fasilitas berhasil diperbarui!')">
                    <i class="fas fa-check-double"></i> Simpan Fasilitas
                </button>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    // Handler Toggle Switch dengan perubahan warna label
    function handleToggle(switchId, labelId, textOn, textOff, colorOn, colorOff, toastMsg) {
        const sw = document.getElementById(switchId);
        const label = document.getElementById(labelId);
        if (sw.checked) {
            label.textContent = textOn;
            label.style.color = colorOn;
        } else {
            label.textContent = textOff;
            label.style.color = colorOff;
        }
        // Update timestamp
        const now = new Date();
        document.getElementById('lastUpdatedLabel').innerHTML =
            `<i class="fas fa-clock"></i> Diperbarui ${now.getHours()}:${String(now.getMinutes()).padStart(2,'0')} WIB`;
        showToast(toastMsg);
    }

    // Toggle Checklist Fasilitas
    function toggleCheck(item) {
        item.classList.toggle('checked');
        const checkbox = item.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
    }
</script>
@endpush
