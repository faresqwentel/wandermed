<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Wisatawan – WanderMed</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/dashboard-wisatawan.css') }}" rel="stylesheet">
    {{-- Inline script tema: set SEBELUM render agar tidak flash --}}
    <script>
        (function() {
            var t = localStorage.getItem('wanderMedTheme') || 'dark';
            if (t === 'dark') document.write('<style>body{background:#0f172a;color:#f1f5f9}</style>');
        })();
    </script>
</head>
<body id="appBody">
<script>
    // Set class tema sebelum konten dirender
    (function() {
        var t = localStorage.getItem('wanderMedTheme') || 'dark';
        document.getElementById('appBody').className = t === 'dark' ? 'dark' : '';
    })();
</script>

{{-- ═══════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════ --}}
<nav class="w-nav">
    <div class="w-nav-left">
        <a href="/" class="w-nav-brand">
            <i class="fas fa-heartbeat"></i>
            WanderMed
        </a>
    </div>
    <div class="w-nav-actions">
        <a href="/peta-faskes" class="w-btn w-btn-ghost" title="Peta Faskes">
            <i class="fas fa-map-marked-alt"></i>
            <span class="d-none-xs">Peta</span>
        </a>
        <button class="w-btn w-btn-ghost" onclick="toggleTheme()" title="Ganti Tema" id="themeBtn">
            <i class="fas fa-moon" id="themeIco"></i>
        </button>
        <a href="/logout" class="w-btn w-btn-red" id="logoutBtn" title="Keluar">
            <i class="fas fa-sign-out-alt"></i>
            <span class="d-none-xs">Keluar</span>
        </a>
    </div>
</nav>

{{-- ═══════════════════════════════════════════
     PROFILE STRIP (mobile only — menggantikan sidebar)
═══════════════════════════════════════════ --}}
<div class="profile-strip">
    <div class="ps-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
    <div class="ps-info">
        <div class="ps-name">{{ $user->name }}</div>
        <div class="ps-email">{{ $user->email }}</div>
        <div class="ps-badge"><i class="fas fa-check-circle"></i> Wisatawan Aktif</div>
    </div>
    <div class="ps-quick-links">
        <a href="/peta-faskes" class="ps-link" title="Peta Faskes">
            <i class="fas fa-map-marked-alt"></i>
        </a>
        <button class="ps-link" onclick="switchTab('medis'); scrollToMain();" title="Rekam Medis">
            <i class="fas fa-notes-medical"></i>
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     LAYOUT UTAMA: Sidebar + Main
═══════════════════════════════════════════ --}}
<div class="w-layout">

    {{-- ── SIDEBAR KIRI (Desktop) ── --}}
    <aside class="w-sidebar" id="wSidebar">

        {{-- Profil --}}
        <div class="profile-main">
            <div class="p-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="p-name">{{ $user->name }}</div>
            <div class="p-email">{{ $user->email }}</div>
            <div class="p-badge"><i class="fas fa-check-circle"></i> Wisatawan Aktif</div>
            <div class="p-join"><i class="fas fa-calendar-alt"></i> Bergabung {{ $user->created_at->format('M Y') }}</div>
        </div>

        <div class="sidebar-divider"></div>

        {{-- Navigasi Tab --}}
        <nav class="sidebar-nav">
            <button class="sidebar-nav-item active" id="sn-riwayat" onclick="switchTab('riwayat')">
                <i class="fas fa-history"></i> Riwayat Kunjungan
            </button>
            <button class="sidebar-nav-item" id="sn-profil" onclick="switchTab('profil')">
                <i class="fas fa-user-cog"></i> Pengaturan Akun
            </button>
            <button class="sidebar-nav-item" id="sn-medis" onclick="switchTab('medis')">
                <i class="fas fa-notes-medical"></i> Rekam Medis
            </button>
        </nav>

        <div class="sidebar-divider"></div>

        {{-- Info Medis Darurat --}}
        <div class="medis-summary">
            <div class="medis-summary-title">Info Medis Darurat</div>
            <div class="med-row">
                <div class="med-lbl"><i class="fas fa-tint" style="color:var(--red)"></i> Gol. Darah</div>
                <div class="med-val red">{{ $user->gol_darah ?: '—' }}</div>
            </div>
            <div class="med-row">
                <div class="med-lbl"><i class="fas fa-phone-alt" style="color:var(--orange)"></i> Kontak Darurat</div>
                <div class="med-val" style="font-size:11px; text-align:right; max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                    {{ $user->kontak_darurat ?: '—' }}
                </div>
            </div>
        </div>

        <div class="sidebar-divider"></div>

        {{-- PIN Pemulihan --}}
        <div class="medis-summary">
            <div class="pin-toggle-row">
                <span><i class="fas fa-key"></i> PIN Pemulihan</span>
                <label class="switch">
                    <input type="checkbox" id="togglePinWisatawan" onchange="togglePinVisibility()">
                    <span class="slider"></span>
                </label>
            </div>
            <div class="pin-display">
                <span id="pinValueWisatawan" style="filter:blur(5px); transition:filter 0.3s; user-select:none;">
                    {{ $user->recovery_pin ?? '000000' }}
                </span>
            </div>
            <div class="pin-hint">Gunakan 6-digit PIN ini jika lupa password akun Anda.</div>
        </div>

    </aside>

    {{-- ── KONTEN UTAMA ── --}}
    <main class="w-main" id="wMain">

        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- Welcome Banner --}}
        <div class="welcome-banner">
            <div class="wb-title">Halo, {{ explode(' ', trim($user->name))[0] }}! 👋</div>
            <div class="wb-desc">Selamat datang di portal wisatawan WanderMed. Kelola kesehatan dan temukan fasilitas medis terbaik di Subang.</div>
            <div class="wb-actions">
                <a href="/peta-faskes" class="wb-btn-solid wb-btn">
                    <i class="fas fa-map-marked-alt"></i> Peta Faskes
                </a>
                <button class="wb-btn" onclick="switchTab('medis')">
                    <i class="fas fa-notes-medical"></i> Rekam Medis
                </button>
            </div>
        </div>

        {{-- ── PIN PEMULIHAN (Mobile only — sidebar tidak tersedia di hp) ── --}}
        <div class="pin-mobile-card">
            <div class="pin-mobile-header">
                <div class="pin-mobile-label">
                    <i class="fas fa-key"></i>
                    <span>PIN Pemulihan Akun</span>
                </div>
                <label class="switch">
                    <input type="checkbox" id="togglePinMobile" onchange="togglePinMobile()">
                    <span class="slider"></span>
                </label>
            </div>
            <div class="pin-mobile-value">
                <span id="pinValueMobile" style="filter:blur(5px); transition:filter 0.3s; user-select:none; letter-spacing:8px;">
                    {{ $user->recovery_pin ?? '000000' }}
                </span>
            </div>
            <div class="pin-mobile-hint">
                <i class="fas fa-shield-alt"></i>
                Aktifkan toggle di atas untuk melihat PIN 6-digit Anda
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="sc-icon blue"><i class="fas fa-hospital-user"></i></div>
                <div class="sc-body">
                    <div class="sc-val">{{ $totalKunjungan }}</div>
                    <div class="sc-lbl">Total Kunjungan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="sc-icon orange"><i class="fas fa-calendar-check"></i></div>
                <div class="sc-body">
                    <div class="sc-val">{{ $kunjunganBulan }}</div>
                    <div class="sc-lbl">Bulan Ini</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="sc-icon green"><i class="fas fa-star"></i></div>
                <div class="sc-body">
                    <div class="sc-val">{{ $rekomendasiCount }}</div>
                    <div class="sc-lbl">Direkomendasikan</div>
                </div>
            </div>
        </div>

        {{-- Tab Pills --}}
        <div class="w-pills">
            <button class="w-pill active" id="pill-riwayat" data-target="tab-riwayat" onclick="switchTab('riwayat')">
                <i class="fas fa-history"></i> Riwayat
            </button>
            <button class="w-pill" id="pill-profil" data-target="tab-profil" onclick="switchTab('profil')">
                <i class="fas fa-user-cog"></i> Akun
            </button>
            <button class="w-pill" id="pill-medis" data-target="tab-medis" onclick="switchTab('medis')">
                <i class="fas fa-notes-medical"></i> Medis
            </button>
        </div>

        {{-- ── TAB: RIWAYAT KUNJUNGAN ── --}}
        <div id="tab-riwayat" class="w-pane active">
            <div class="w-card">
                <div class="w-card-header">
                    <i class="fas fa-history"></i> Riwayat Kunjungan Faskes
                </div>
                <div class="w-card-body">
                    @if($riwayats->count() > 0)
                    <div class="history-list">
                        @foreach($riwayats as $r)
                        @php
                            $lbl = $r->label_warna ?? 'yellow';
                            $ico = ['green'=>'fa-star','yellow'=>'fa-check','red'=>'fa-times-circle'];
                            $txt = ['green'=>'Direkomendasikan','yellow'=>'Cukup Baik','red'=>'Tidak Disarankan'];
                            $cls = ['green'=>'g','yellow'=>'y','red'=>'r'];
                        @endphp
                        <div class="h-item">
                            <div class="h-icon {{ $cls[$lbl] ?? 'y' }}">
                                <i class="fas {{ $ico[$lbl] ?? 'fa-check' }}"></i>
                            </div>
                            <div class="h-main">
                                <div class="h-name">{{ $r->faskes ? $r->faskes->nama_faskes : 'Faskes Tidak Diketahui' }}</div>
                                <div class="h-meta">
                                    <span><i class="fas fa-calendar-alt"></i> {{ $r->tanggal_kunjungan->format('d M Y') }}</span>
                                </div>
                                @if($r->catatan_pribadi)
                                <div class="h-note">"{{ $r->catatan_pribadi }}"</div>
                                @endif
                            </div>
                            <div class="h-badge {{ $cls[$lbl] ?? 'y' }}">{{ $txt[$lbl] ?? '' }}</div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="h-empty">
                        <i class="fas fa-folder-open"></i>
                        <p>Belum ada riwayat kunjungan yang dicatat.</p>
                        <a href="/peta-faskes" class="w-btn w-btn-orange" style="padding:12px 24px; font-size:14px;">
                            <i class="fas fa-map-marked-alt"></i> Jelajahi Peta Faskes
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── TAB: PENGATURAN AKUN ── --}}
        <div id="tab-profil" class="w-pane">
            <div class="w-card">
                <div class="w-card-header"><i class="fas fa-user-cog"></i> Pengaturan Akun</div>
                <div class="w-card-body">
                    <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                        @csrf
                        <div class="w-form-grid">
                            <div>
                                <label class="w-label">Nama Lengkap</label>
                                <input type="text" name="name" class="w-input" value="{{ old('name', $user->name) }}" required maxlength="100">
                            </div>
                            <div>
                                <label class="w-label">Email (Tidak dapat diubah)</label>
                                <input type="email" class="w-input" value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="w-btn w-btn-orange" style="padding:11px 28px;">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>

                    <hr style="border-color:var(--border); margin:24px 0;">

                    <h5 style="font-size:14px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-lock" style="color:var(--orange)"></i> Ganti Password
                    </h5>
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <div class="w-form-group">
                            <label class="w-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="w-input" placeholder="Password lama..." required>
                        </div>
                        <div class="w-form-grid">
                            <div>
                                <label class="w-label">Password Baru</label>
                                <input type="password" name="new_password" class="w-input" placeholder="Min. 8 karakter" required minlength="8">
                            </div>
                            <div>
                                <label class="w-label">Konfirmasi Password</label>
                                <input type="password" name="new_password_confirmation" class="w-input" placeholder="Ulangi password baru" required minlength="8">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="w-btn w-btn-orange" style="padding:11px 28px;">
                                <i class="fas fa-key"></i> Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── TAB: REKAM MEDIS ── --}}
        <div id="tab-medis" class="w-pane">
            <div class="w-card" style="border-top: 3px solid var(--red);">
                <div class="w-card-header" style="color:var(--red);">
                    <i class="fas fa-notes-medical" style="color:var(--red)"></i> Rekam Medis Darurat Pribadi
                </div>
                <div class="w-card-body">
                    <div class="info-banner">
                        <i class="fas fa-info-circle"></i>
                        <div><strong>Perhatian:</strong> Data medis darurat ini membantu petugas medis memahami kondisi Anda jika terjadi kedaruratan saat berwisata di Subang.</div>
                    </div>

                    <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <div class="w-form-grid">
                            <div>
                                <label class="w-label">Golongan Darah</label>
                                <select name="gol_darah" class="w-select">
                                    <option value="">– Belum Diketahui –</option>
                                    @foreach(['A','B','AB','O'] as $gd)
                                    <option value="{{ $gd }}" {{ $user->gol_darah == $gd ? 'selected' : '' }}>Golongan {{ $gd }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="w-label">Kontak Darurat</label>
                                <input type="text" name="kontak_darurat" class="w-input"
                                    value="{{ old('kontak_darurat', $user->kontak_darurat) }}"
                                    placeholder="Contoh: 08123456789 (Nama)" maxlength="15">
                            </div>
                        </div>
                        <div class="w-form-group">
                            <label class="w-label">Riwayat Alergi & Penyakit Bawaan</label>
                            <textarea name="riwayat_alergi" class="w-textarea"
                                placeholder="Contoh: Alergi Penisilin, Asma, Hipertensi..." maxlength="200">{{ old('riwayat_alergi', $user->riwayat_alergi) }}</textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="w-btn" style="padding:11px 28px; background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.3);">
                                <i class="fas fa-shield-alt"></i> Simpan Data Medis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
</div>

<style>
/* Utility: sembunyikan teks di navbar pada layar xs */
@media (max-width: 480px) {
    .d-none-xs { display: none !important; }
}
</style>

<script src="{{ asset('js/dashboard-wisatawan.js') }}"></script>
</body>
</html>
