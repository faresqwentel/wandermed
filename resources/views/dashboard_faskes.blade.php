{{-- ============================================================
     Dashboard Mitra Faskes – WanderMed
     Layout: theme/dashboard_layout.blade.php (Kustom, no SB Admin)
     ============================================================ --}}
@extends('theme.dashboard_layout')

@section('page_title', 'Dashboard Faskes')
@section('badge_role', 'Mitra Faskes')
@section('user_name', $faskes && $faskes->nama_faskes ? $faskes->nama_faskes : $mitra->nama_penanggung_jawab)
@section('user_role', 'Mitra Fasilitas Kesehatan')
@section('user_initial', $faskes && $faskes->nama_faskes ? substr($faskes->nama_faskes, 0, 1) : substr($mitra->nama_penanggung_jawab, 0, 1))
@section('topbar_title', 'Dashboard Operasional Faskes')

@section('sidebar_nav')
    <div class="wm-nav-label">Operasional</div>
    <a href="#" class="wm-nav-link active" id="navDashboard">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="#" class="wm-nav-link" id="navKontrolStatus">
        <i class="fas fa-toggle-on"></i> Kontrol Status
    </a>
    <a href="#" class="wm-nav-link" id="navFasilitas">
        <i class="fas fa-clipboard-list"></i> Fasilitas & Layanan
    </a>
    <div class="wm-nav-label">Profil</div>
    <a href="#" class="wm-nav-link" id="navProfilFaskes">
        <i class="fas fa-hospital"></i> Profil Faskes
    </a>
    <a href="#" class="wm-nav-link" id="navKoordinat">
        <i class="fas fa-map-pin"></i> Update Koordinat
    </a>
    <div class="wm-nav-label">Navigasi</div>
    <a href="/peta-faskes" class="wm-nav-link">
        <i class="fas fa-map-marked-alt"></i> Lihat di Peta
    </a>
    <a href="/logout" class="wm-nav-link">
        <i class="fas fa-sign-out-alt"></i> Keluar
    </a>
@endsection

@section('content')

<!-- SESSION ALERT -->
@if(session('success'))
<div class="wm-alert success mb-3" style="background: rgba(28,200,138,0.12); border-left: 4px solid #1cc88a; padding: 12px 18px; border-radius: 8px; color: #1cc88a; font-size:13px;">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="wm-alert danger mb-3" style="background: rgba(231,74,59,0.12); border-left: 4px solid #e74a3b; padding: 12px 18px; border-radius: 8px; color: #e74a3b; font-size:13px;">
    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
</div>
@endif

<!-- ===== SECTION 1: DASHBOARD UTAMA ===== -->
<div id="sectionDashboard" class="faskes-section">

    <!-- Page Header -->
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Panel Kontrol Faskes</div>
            <div class="wm-page-subtitle">Perbarui status real-time agar wisatawan mendapat informasi akurat di peta</div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="wm-stat-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="wm-stat-card blue">
            <div class="wm-stat-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="wm-stat-value">{{ $totalPengunjung ?? 0 }}</div>
                <div class="wm-stat-label">Total Pengunjung</div>
            </div>
        </div>
        <div class="wm-stat-card yellow">
            <div class="wm-stat-icon"><i class="fas fa-bullhorn"></i></div>
            <div>
                <div class="wm-stat-value" style="font-size:14px;">
                    {{ $faskes && $faskes->status_operasional == 'open' ? 'BUKA' : 'TUTUP' }}
                </div>
                <div class="wm-stat-label">Status Operasional</div>
            </div>
        </div>
        <div class="wm-stat-card green">
            <div class="wm-stat-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="wm-stat-value">{{ $totalUlasan ?? 0 }}</div>
                <div class="wm-stat-label">Total Ulasan Masuk</div>
            </div>
        </div>
    </div>

    <!-- Pesan dari Admin (tampil hanya jika ada pesan) -->
    @if(!empty($faskes->pesan_admin))
    <div class="wm-card mt-4" style="border-left: 4px solid #f6c23e; background: rgba(246,194,62,0.06);">
        <div class="wm-card-header" style="border-bottom: 1px solid rgba(246,194,62,0.2);">
            <div class="wm-card-title">
                <i class="fas fa-envelope-open-text" style="color: #f6c23e;"></i>
                Pesan dari Admin
                <span class="wm-badge yellow" style="margin-left: 8px; font-size: 10px; animation: pulse 2s infinite;">Baru</span>
            </div>
        </div>
        <div class="wm-card-body" style="padding: 16px 22px;">
            <p style="font-size: 14px; line-height: 1.6; margin: 0; color: var(--text-secondary);">
                {{ $faskes->pesan_admin }}
            </p>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 10px;">
                <i class="fas fa-clock"></i> Dikirim oleh Administrator WanderMed
            </div>
        </div>
    </div>
    @endif

    <!-- Info ringkas faskes -->
    <div class="wm-card mt-4">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-info-circle"></i> Info Singkat Faskes</div>
            <button class="wm-btn ghost sm" onclick="switchSection('navProfilFaskes', 'sectionProfil')">
                <i class="fas fa-edit"></i> Edit Profil
            </button>
        </div>
        <div class="wm-card-body">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size:13px;">
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">Nama Faskes</div>
                    <div style="font-weight:600;">{{ $faskes->nama_faskes ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">Kategori</div>
                    <div style="font-weight:600;">{{ $faskes->jenis_faskes ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">Alamat</div>
                    <div style="font-weight:600;">{{ $faskes->alamat ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">No. Telepon</div>
                    <div style="font-weight:600;">{{ $faskes->no_telp ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">Koordinat</div>
                    <div style="font-weight:600; font-family: monospace;">
                        {{ $faskes->latitude ?? '0' }}, {{ $faskes->longitude ?? '0' }}
                    </div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">BPJS</div>
                    <div>
                        @if($faskes && $faskes->dukungan_bpjs)
                            <span class="wm-badge green"><i class="fas fa-check"></i> Diterima</span>
                        @else
                            <span class="wm-badge danger"><i class="fas fa-times"></i> Tidak</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- End sectionDashboard -->

<!-- ===== SECTION 2: KONTROL STATUS ===== -->
<div id="sectionKontrolStatus" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Kontrol Status Real-time</div>
            <div class="wm-page-subtitle">Perubahan langsung tampil di peta publik WanderMed</div>
        </div>
        <span id="lastUpdatedLabel" style="font-size: 11px; color: var(--text-muted);">
            <i class="fas fa-clock mr-1"></i> Belum diperbarui
        </span>
    </div>

    <div class="wm-card">
        <div class="wm-card-body">
            <!-- Toggle: Operasional -->
            <div class="wm-toggle-row">
                <div class="wm-toggle-info">
                    <h6>Status Operasional Klinik</h6>
                    <p>Apakah faskes sedang buka dan dapat menerima pasien?</p>
                </div>
                <div class="wm-toggle-group">
                    <span class="wm-toggle-label" id="labelOps" style="color: {{ $faskes && $faskes->status_operasional == 'open' ? '#1cc88a' : '#e74a3b' }};">
                        {{ $faskes && $faskes->status_operasional == 'open' ? '✓ BUKA' : '✕ TUTUP' }}
                    </span>
                    <label class="wm-switch">
                        <input type="checkbox" id="switchOps" {{ $faskes && $faskes->status_operasional == 'open' ? 'checked' : '' }}
                            onchange="handleAjaxToggle('status_operasional', this.checked ? 'true' : 'false', 'switchOps', 'labelOps', '✓ BUKA', '✕ TUTUP', '#1cc88a', '#e74a3b', 'Status Operasional diperbarui!')">
                        <span class="wm-switch-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Toggle: BPJS -->
            <div class="wm-toggle-row" style="margin-top:16px;">
                <div class="wm-toggle-info">
                    <h6>Penerimaan BPJS Kesehatan</h6>
                    <p>Sedang menerima pasien peserta BPJS saat ini?</p>
                </div>
                <div class="wm-toggle-group">
                    <span class="wm-toggle-label" id="labelBPJS" style="color: {{ $faskes && $faskes->dukungan_bpjs ? '#1cc88a' : '#e74a3b' }};">
                        {{ $faskes && $faskes->dukungan_bpjs ? '✓ TERIMA BPJS' : '✕ TIDAK TERIMA' }}
                    </span>
                    <label class="wm-switch">
                        <input type="checkbox" id="switchBPJS" {{ $faskes && $faskes->dukungan_bpjs ? 'checked' : '' }}
                            onchange="handleAjaxToggle('dukungan_bpjs', this.checked ? '1' : '0', 'switchBPJS', 'labelBPJS', '✓ TERIMA BPJS', '✕ TIDAK TERIMA', '#1cc88a', '#e74a3b', 'Status BPJS diperbarui!')">
                        <span class="wm-switch-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Pengumuman -->
            <div class="wm-form-group" style="margin-top: 24px;">
                <label class="wm-label">Pengumuman Sementara untuk Wisatawan di Peta</label>
                <textarea class="wm-textarea" id="inputPengumuman" rows="4" placeholder="Contoh: Stok oksigen terbatas hari ini, harap hubungi kami terlebih dahulu...">{{ $faskes ? $faskes->pengumuman : '' }}</textarea>
            </div>
            <button class="wm-btn orange" style="width:100%;" onclick="savePengumuman()">
                <i class="fas fa-broadcast-tower"></i> Simpan & Siaran ke Peta
            </button>
        </div>
    </div>
</div>

<!-- ===== SECTION 3: FASILITAS ===== -->
<div id="sectionFasilitas" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Manajemen Fasilitas & Layanan</div>
            <div class="wm-page-subtitle">Centang fasilitas yang aktif tersedia—akan langsung tampil di popup peta</div>
        </div>
    </div>

    <div class="wm-card">
        <div class="wm-card-body">
            @php
                $fasilitas_list = $faskes ? ($faskes->layanan_tersedia ?? []) : [];
                $icons = [
                    'UGD 24 Jam'     => ['icon' => 'fa-ambulance',    'color' => '#e74a3b'],
                    'Ambulans'       => ['icon' => 'fa-car',          'color' => '#4e73df'],
                    'Rawat Inap'     => ['icon' => 'fa-bed',          'color' => '#36b9cc'],
                    'Apotek'         => ['icon' => 'fa-pills',        'color' => '#1cc88a'],
                    'Laboratorium'   => ['icon' => 'fa-flask',        'color' => '#f6c23e'],
                    'Dok. Spesialis' => ['icon' => 'fa-user-md',      'color' => 'var(--orange)'],
                    'Poli Anak'      => ['icon' => 'fa-baby',         'color' => '#e74a3b'],
                    'Poli Gigi'      => ['icon' => 'fa-tooth',        'color' => '#4e73df'],
                    'Poli Umum'      => ['icon' => 'fa-stethoscope',  'color' => '#1cc88a'],
                    'Imunisasi'      => ['icon' => 'fa-syringe',      'color' => '#36b9cc'],
                    'Fisioterapi'    => ['icon' => 'fa-hand-holding-heart', 'color' => '#e74a3b'],
                    'Radiologi'      => ['icon' => 'fa-x-ray',        'color' => '#6f42c1'],
                ];
            @endphp
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 18px;">
                Centang fasilitas yang <strong style="color: #1cc88a;">aktif tersedia</strong> di faskes Anda hari ini.
            </p>
            <div class="wm-check-grid" id="fasilitasGrid">
                @foreach($icons as $fName => $props)
                @php $isChecked = in_array($fName, $fasilitas_list); @endphp
                <div class="wm-check-item {{ $isChecked ? 'checked' : '' }}" onclick="toggleCheck(this)">
                    <div class="wm-check-box"><i class="fas fa-check"></i></div>
                    <span class="wm-check-label"><i class="fas {{ $props['icon'] }} mr-1" style="color: {{ $props['color'] }};"></i> {{ $fName }}</span>
                    <input type="checkbox" value="{{ $fName }}" {{ $isChecked ? 'checked' : '' }}>
                </div>
                @endforeach
            </div>

            <button class="wm-btn success" style="width:100%; margin-top: 16px;" onclick="saveFasilitas()">
                <i class="fas fa-check-double"></i> Simpan Fasilitas ke Peta
            </button>
        </div>
    </div>
</div>

<!-- ===== SECTION 4: PROFIL FASKES ===== -->
<div id="sectionProfil" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Profil & Data Faskes</div>
            <div class="wm-page-subtitle">Perubahan akan langsung diperbarui di sistem peta WanderMed</div>
        </div>
    </div>

    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-hospital"></i> Edit Identitas Faskes</div>
        </div>
        <div class="wm-card-body">
            <form action="{{ route('faskes.profil.update') }}" method="POST">
                @csrf
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                    <div class="wm-form-group" style="grid-column: 1/-1;">
                        <label class="wm-label">Nama Faskes <span style="color:#e74a3b">*</span></label>
                        <input type="text" name="nama_faskes" class="wm-input" value="{{ $faskes->nama_faskes ?? '' }}" placeholder="Contoh: RSUD Subang" required>
                    </div>
                    <div class="wm-form-group">
                        <label class="wm-label">Kategori / Jenis <span style="color:#e74a3b">*</span></label>
                        <select name="jenis_faskes" class="wm-input" required>
                            @foreach(['Rumah Sakit','Klinik','Apotek','Puskesmas','Lainnya'] as $jenis)
                            <option value="{{ $jenis }}" {{ ($faskes->jenis_faskes ?? '') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="wm-form-group">
                        <label class="wm-label">Nomor Telepon</label>
                        <input type="tel" name="no_telp" class="wm-input" value="{{ $faskes->no_telp ?? '' }}" placeholder="0260-xxxxxx">
                    </div>
                    <div class="wm-form-group" style="grid-column: 1/-1;">
                        <label class="wm-label">Alamat Lengkap <span style="color:#e74a3b">*</span></label>
                        <textarea name="alamat" class="wm-textarea" rows="2" required>{{ $faskes->alamat ?? '' }}</textarea>
                    </div>
                </div>
                <div style="margin-top: 18px;">
                    <label class="wm-label">Dukungan BPJS Kesehatan</label>
                    <div style="display:flex; gap: 16px; margin-top: 8px;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px;">
                            <input type="radio" name="dukungan_bpjs" value="1" {{ ($faskes->dukungan_bpjs ?? false) ? 'checked' : '' }}> Ya, Menerima BPJS
                        </label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px;">
                            <input type="radio" name="dukungan_bpjs" value="0" {{ !($faskes->dukungan_bpjs ?? false) ? 'checked' : '' }}> Tidak Menerima
                        </label>
                    </div>
                </div>
                {{-- Info: koordinat dikelola di menu Update Koordinat --}}
                <div style="margin-top: 16px; padding: 12px 16px; background: rgba(78,115,223,0.06); border-left: 3px solid #4e73df; border-radius: 8px; font-size: 12px; color: var(--text-muted);">
                    <i class="fas fa-map-pin mr-1" style="color:#4e73df"></i>
                    Untuk mengubah koordinat lokasi PIN di peta, gunakan menu <strong style="color: var(--text-primary);">Update Koordinat</strong> di sidebar.
                </div>
                <div style="margin-top: 20px;">
                    <button type="submit" class="wm-btn orange" style="width:100%;">
                        <i class="fas fa-save"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== SECTION 5: UPDATE KOORDINAT ===== -->
<div id="sectionKoordinat" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Update Koordinat di Peta</div>
            <div class="wm-page-subtitle">Masukkan koordinat baru agar lokasi PIN faskes di peta lebih akurat</div>
        </div>
    </div>

    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-map-marker-alt" style="color:#f6c23e"></i> Koordinat Saat Ini</div>
        </div>
        <div class="wm-card-body">
            <div style="background: rgba(246,194,62,0.08); border: 1px solid rgba(246,194,62,0.3); border-radius:8px; padding:16px; margin-bottom:20px; font-family:monospace; font-size:15px; text-align:center;">
                📍 Lat: <strong>{{ $faskes->latitude ?? '0.000000' }}</strong> &nbsp;|&nbsp; Lng: <strong>{{ $faskes->longitude ?? '0.000000' }}</strong>
            </div>

            <form action="{{ route('faskes.profil.update') }}" method="POST">
                @csrf
                {{-- Kirim semua field lain sebagai hidden agar validasi lolos --}}
                <input type="hidden" name="nama_faskes" value="{{ $faskes->nama_faskes ?? '' }}">
                <input type="hidden" name="jenis_faskes" value="{{ $faskes->jenis_faskes ?? 'Klinik' }}">
                <input type="hidden" name="alamat" value="{{ $faskes->alamat ?? '-' }}">
                <input type="hidden" name="no_telp" value="{{ $faskes->no_telp ?? '' }}">
                <input type="hidden" name="dukungan_bpjs" value="{{ $faskes->dukungan_bpjs ? '1' : '0' }}">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div class="wm-form-group">
                        <label class="wm-label">Latitude Baru <span style="color:#e74a3b">*</span></label>
                        <input type="number" step="any" name="latitude" id="quickLat" class="wm-input" value="{{ $faskes->latitude ?? '' }}" placeholder="-6.571..." required>
                    </div>
                    <div class="wm-form-group">
                        <label class="wm-label">Longitude Baru <span style="color:#e74a3b">*</span></label>
                        <input type="number" step="any" name="longitude" id="quickLng" class="wm-input" value="{{ $faskes->longitude ?? '' }}" placeholder="107.760..." required>
                    </div>
                </div>
                <div style="margin-top: 14px; display:flex; gap:12px;">
                    <button type="button" class="wm-btn blue" onclick="fillGPS()" style="flex:1;">
                        <i class="fas fa-crosshairs"></i> Deteksi Otomatis via GPS
                    </button>
                    <button type="submit" class="wm-btn orange" style="flex:1;">
                        <i class="fas fa-save"></i> Simpan Koordinat Baru
                    </button>
                </div>
            </form>

            <div style="margin-top:24px; padding:14px; background: rgba(255,255,255,0.03); border-radius:8px; font-size:12px; color: var(--text-muted);">
                <i class="fas fa-info-circle mr-1" style="color:#4e73df"></i>
                <strong>Tips:</strong> Buka Google Maps, klik kanan di titik lokasi faskes Anda, lalu salin koordinatnya ke sini.
                Format: <code>Latitude, Longitude</code> (pisahkan dengan koma).
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // =========================================================
    // TAB NAVIGATION (Sidebar Click → Show Section)
    // =========================================================
    const faskesNavMap = {
        'navDashboard':    'sectionDashboard',
        'navKontrolStatus':'sectionKontrolStatus',
        'navFasilitas':    'sectionFasilitas',
        'navProfilFaskes': 'sectionProfil',
        'navKoordinat':    'sectionKoordinat',
    };

    function switchSection(navId, sectionId) {
        // Deactivate all nav links
        document.querySelectorAll('.wm-nav-link').forEach(l => l.classList.remove('active'));
        // Hide all sections
        document.querySelectorAll('.faskes-section').forEach(s => s.style.display = 'none');
        // Activate selected
        const navEl = document.getElementById(navId);
        if (navEl) navEl.classList.add('active');
        const secEl = document.getElementById(sectionId);
        if (secEl) secEl.style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelectorAll('.wm-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const navId = this.id;
            if (!faskesNavMap[navId]) return; // external links (peta, logout)
            e.preventDefault();
            switchSection(navId, faskesNavMap[navId]);
        });
    });

    // =========================================================
    // AJAX Toggle Status
    // =========================================================
    function handleAjaxToggle(field, value, switchId, labelId, textOn, textOff, colorOn, colorOff, toastMsg) {
        const sw = document.getElementById(switchId);
        const label = document.getElementById(labelId);
        if (sw.checked) {
            label.textContent = textOn;
            label.style.color = colorOn;
        } else {
            label.textContent = textOff;
            label.style.color = colorOff;
        }

        fetch("{{ route('faskes.status.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ field: field, value: value })
        })
        .then(r => r.json())
        .then(data => {
            const now = new Date();
            const el = document.getElementById('lastUpdatedLabel');
            if (el) el.innerHTML = `<i class="fas fa-clock"></i> Diperbarui ${now.getHours()}:${String(now.getMinutes()).padStart(2,'0')} WIB`;
            showToast(data.message || toastMsg);
            setTimeout(() => location.reload(), 1500);
        })
        .catch(e => {
            console.error(e);
            showToast('Gagal menyimpan. Coba lagi.', 'danger');
        });
    }

    // =========================================================
    // Simpan Pengumuman
    // =========================================================
    function savePengumuman() {
        const text = document.getElementById('inputPengumuman').value;
        fetch("{{ route('faskes.status.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ field: 'pengumuman', value: text })
        })
        .then(r => r.json())
        .then(data => {
            showToast("Pengumuman berhasil disiarkan ke peta!");
            setTimeout(() => location.reload(), 1500);
        })
        .catch(() => showToast('Gagal menyimpan pengumuman.', 'danger'));
    }

    // =========================================================
    // Toggle Checklist Fasilitas (UI Only)
    // =========================================================
    function toggleCheck(item) {
        item.classList.toggle('checked');
        const checkbox = item.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
    }

    // =========================================================
    // Simpan Fasilitas via AJAX
    // =========================================================
    function saveFasilitas() {
        const checkboxes = document.querySelectorAll('#fasilitasGrid input[type="checkbox"]:checked');
        const checkedValues = Array.from(checkboxes).map(cb => cb.value);

        fetch("{{ route('faskes.fasilitas.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ layanan_tersedia: checkedValues })
        })
        .then(r => r.json())
        .then(data => {
            showToast(data.message || "Fasilitas diperbarui dan tampil di peta!");
            setTimeout(() => location.reload(), 1500);
        })
        .catch(() => showToast('Gagal menyimpan fasilitas.', 'danger'));
    }

    // =========================================================
    // GPS Auto-fill Koordinat
    // =========================================================
    function fillGPS() {
        if (!navigator.geolocation) {
            showToast('Browser tidak mendukung GPS.', 'danger');
            return;
        }
        showToast('Mendeteksi lokasi GPS...');
        navigator.geolocation.getCurrentPosition(pos => {
            document.getElementById('quickLat').value = pos.coords.latitude.toFixed(6);
            document.getElementById('quickLng').value = pos.coords.longitude.toFixed(6);
            showToast('Koordinat GPS berhasil diisi! Klik Simpan untuk menyimpan.');
        }, () => {
            showToast('Gagal mendapat lokasi GPS. Izin ditolak atau tidak tersedia.', 'danger');
        });
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) {
            showToast('Browser tidak mendukung GPS.', 'danger');
            return;
        }
        showToast('Mendeteksi lokasi GPS...');
        navigator.geolocation.getCurrentPosition(pos => {
            document.getElementById('inputLat').value = pos.coords.latitude.toFixed(6);
            document.getElementById('inputLng').value = pos.coords.longitude.toFixed(6);
            showToast('Koordinat GPS telah diisi. Klik "Simpan & Perbarui ke Peta".');
        }, () => {
            showToast('Gagal mendapat lokasi GPS.', 'danger');
        });
    }
</script>
@endpush
