<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Wisatawan – WanderMed</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* CSS RESET & VARIABLES */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --bg:        #f3f6fa;
    --bg2:       #ffffff;
    --surface:   #f8fafc;
    --border:    #e2e8f0;
    --text:      #1e293b;
    --text-muted:#64748b;
    --orange:    #ff7a00;
    --orange-glow: rgba(255, 122, 0, 0.15);
    --red:       #ef4444;
    --green:     #10b981;
    --yellow:    #f59e0b;
    --radius:    24px;
}
.dark {
    --bg:        #0f172a;
    --bg2:       #1e293b;
    --surface:   #334155;
    --border:    #334155;
    --text:      #f8fafc;
    --text-muted:#94a3b8;
    --orange-glow: rgba(255, 122, 0, 0.25);
}
body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    transition: background 0.3s, color 0.3s;
}

/* ── NAVBAR ── */
.w-nav {
    position: sticky; top: 0; z-index: 100;
    background: var(--bg2);
    border-bottom: 1px solid var(--border);
    padding: 0 24px; height: 70px;
    display: flex; align-items: center; justify-content: space-between;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
}
.w-nav-brand {
    display: flex; align-items: center; gap: 10px;
    font-weight: 800; font-size: 20px; color: var(--text); text-decoration: none;
}
.w-nav-brand i { color: var(--orange); font-size: 24px; }
.w-nav-actions { display: flex; align-items: center; gap: 10px; }
.w-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 12px;
    font-family: inherit; font-size: 13px; font-weight: 600;
    cursor: pointer; border: none; text-decoration: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.w-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.08); }
.w-btn:active { transform: translateY(0); }
.w-btn-ghost { background: var(--surface); color: var(--text); }
.w-btn-orange { background: var(--orange); color: #fff; box-shadow: 0 4px 15px var(--orange-glow); }
.w-btn-orange:hover { box-shadow: 0 8px 25px var(--orange-glow); }
.w-btn-red { background: rgba(239,68,68,0.1); color: var(--red); border: 1px solid rgba(239,68,68,0.2); }

/* ── CONTAINER & GRID ── */
.w-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px 24px;
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 32px;
}
@media (max-width: 992px) {
    .w-container { grid-template-columns: 1fr; }
}

/* ── COMPONENT: CARD ── */
.w-card {
    background: var(--bg2);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: 0 10px 40px rgba(0,0,0,0.03);
    overflow: hidden;
}
.dark .w-card { box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
.w-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    font-weight: 700; font-size: 15px;
    display: flex; align-items: center; gap: 10px;
}
.w-card-header i { color: var(--orange); font-size: 18px; }
.w-card-body { padding: 24px; }

/* ── SIDEBAR PROFILE ── */
.profile-main { text-align: center; padding: 32px 24px; }
.p-avatar {
    width: 100px; height: 100px; border-radius: 50%;
    background: linear-gradient(135deg, #ff7a00, #e65c00);
    display: flex; align-items: center; justify-content: center;
    font-size: 40px; font-weight: 800; color: #fff;
    margin: 0 auto 16px;
    box-shadow: 0 0 0 6px var(--orange-glow), 0 12px 24px rgba(255,122,0,0.3);
}
.p-name { font-size: 20px; font-weight: 700; margin-bottom: 4px; line-height: 1.2; }
.p-email { font-size: 13px; color: var(--text-muted); margin-bottom: 16px; }
.p-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--orange-glow); color: var(--orange);
    padding: 6px 14px; border-radius: 20px;
    font-size: 11px; font-weight: 700; text-transform: uppercase;
}
.p-join { font-size: 11px; color: var(--text-muted); margin-top: 16px; display: flex; align-items: center; justify-content: center; gap: 6px; }

.medis-summary { padding: 0 24px 24px; }
.med-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px; background: var(--surface); border-radius: 12px; margin-bottom: 10px;
}
.med-lbl { font-size: 11px; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 6px;}
.med-val { font-size: 13px; font-weight: 700; color: var(--text); }
.med-val.red { color: var(--red); }

/* ── MAIN CONTENT ── */
/* Banner */
.welcome-banner {
    background: linear-gradient(135deg, var(--orange), #e65c00);
    border-radius: var(--radius);
    padding: 32px 40px;
    color: #fff;
    margin-bottom: 32px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(255,122,0,0.25);
}
.welcome-banner::before {
    content: ''; position: absolute; top: -50%; right: -10%;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    border-radius: 50%; pointer-events: none;
}
.wb-title { font-size: 28px; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.5px; }
.wb-desc { font-size: 14px; opacity: 0.9; max-width: 500px; line-height: 1.6; }

/* Stats Grid */
.stats-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 32px;
}
@media (max-width: 640px) { .stats-grid { grid-template-columns: 1fr; } }
.stat-card {
    background: var(--bg2); border: 1px solid var(--border); border-radius: 20px;
    padding: 24px; display: flex; align-items: center; gap: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.05); }
.dark .stat-card:hover { box-shadow: 0 12px 30px rgba(0,0,0,0.3); }
.sc-icon {
    width: 54px; height: 54px; border-radius: 16px;
    display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;
}
.sc-icon.blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
.sc-icon.green { background: rgba(16,185,129,0.1); color: #10b981; }
.sc-icon.orange { background: var(--orange-glow); color: var(--orange); }
.sc-val { font-size: 24px; font-weight: 800; line-height: 1.1; color: var(--text); }
.sc-lbl { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; }

/* Pills Nav */
.w-pills {
    display: flex; gap: 12px; margin-bottom: 24px;
    background: var(--surface); padding: 6px; border-radius: 16px;
    overflow-x: auto; scrollbar-width: none;
}
.w-pills::-webkit-scrollbar { display: none; }
.w-pill {
    padding: 12px 24px; border-radius: 12px;
    font-family: inherit; font-size: 13px; font-weight: 600;
    color: var(--text-muted); background: transparent; border: none;
    cursor: pointer; transition: all 0.2s; white-space: nowrap;
    display: flex; align-items: center; gap: 8px;
}
.w-pill:hover { color: var(--text); }
.w-pill.active { background: var(--bg2); color: var(--orange); box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.dark .w-pill.active { box-shadow: 0 4px 15px rgba(0,0,0,0.2); }

/* Panes */
.w-pane { display: none; animation: wmFadeIn 0.3s ease; }
.w-pane.active { display: block; }
@keyframes wmFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* History List */
.history-list { display: flex; flex-direction: column; gap: 16px; }
.h-item {
    display: flex; align-items: center; gap: 16px;
    padding: 16px 20px; background: var(--surface);
    border: 1px solid var(--border); border-radius: 16px;
    transition: transform 0.2s;
}
.h-item:hover { transform: translateX(4px); border-color: rgba(255,122,0,0.3); }
.h-icon {
    width: 46px; height: 46px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
}
.h-icon.g { background: rgba(16,185,129,0.1); color: #10b981; }
.h-icon.y { background: rgba(245,158,11,0.1); color: #f59e0b; }
.h-icon.r { background: rgba(239,68,68,0.1); color: #ef4444; }
.h-main { flex: 1; min-width: 0; }
.h-name { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 2px; }
.h-meta { font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 10px; }
.h-note { font-size: 12px; color: var(--text-muted); font-style: italic; margin-top: 4px; }
.h-badge {
    padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap;
}
.h-badge.g { background: rgba(16,185,129,0.1); color: #10b981; }
.h-badge.y { background: rgba(245,158,11,0.1); color: #f59e0b; }
.h-badge.r { background: rgba(239,68,68,0.1); color: #ef4444; }

.h-empty { text-align: center; padding: 48px 20px; }
.h-empty i { font-size: 48px; color: var(--border); margin-bottom: 16px; display: block; }
.h-empty p { color: var(--text-muted); margin-bottom: 24px; }

/* Forms */
.w-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
@media (max-width: 640px) { .w-form-grid { grid-template-columns: 1fr; } }
.w-form-group { margin-bottom: 20px; }
.w-label { display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
.w-input, .w-select, .w-textarea {
    width: 100%; padding: 12px 16px; border-radius: 12px;
    background: var(--surface); border: 1px solid var(--border);
    color: var(--text); font-family: inherit; font-size: 14px;
    transition: all 0.2s;
}
.w-input:focus, .w-select:focus, .w-textarea:focus { outline: none; border-color: var(--orange); box-shadow: 0 0 0 3px var(--orange-glow); }
.w-input:disabled { opacity: 0.5; cursor: not-allowed; }
.w-textarea { min-height: 100px; resize: vertical; }

.alert-success {
    background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #10b981;
    padding: 14px 20px; border-radius: 12px; font-size: 13px; font-weight: 600;
    display: flex; align-items: center; gap: 10px; margin-bottom: 24px;
}

/* SWAL Overrides */
.wm-swal-popup {
    border: 1px solid rgba(255,122,0,0.18) !important; border-radius: 24px !important; padding: 32px !important;
    background: var(--bg2) !important; color: var(--text) !important;
}
.wm-swal-title { color: var(--text) !important; font-family: 'Poppins', sans-serif !important; }
.wm-swal-confirm { background: linear-gradient(135deg, var(--orange), #e65c00) !important; border-radius: 12px !important; }
.wm-swal-cancel { background: var(--surface) !important; color: var(--text-muted) !important; border-radius: 12px !important; }
</style>
</head>
<body class="dark"> <!-- Default Dark, script akan override jika light -->

<nav class="w-nav">
    <a href="/" class="w-nav-brand">
        <i class="fas fa-heartbeat"></i> WanderMed
    </a>
    <div class="w-nav-actions">
        <a href="/peta-faskes" class="w-btn w-btn-ghost">
            <i class="fas fa-map-marked-alt"></i> <span class="hide-mobile">Peta</span>
        </a>
        <button id="themeBtn" class="w-btn w-btn-ghost" onclick="toggleTheme()" title="Ganti Mode">
            <i class="fas fa-sun" id="themeIco"></i>
        </button>
        <a href="/logout" class="w-btn w-btn-red" id="logoutBtn">
            <i class="fas fa-sign-out-alt"></i> <span class="hide-mobile">Keluar</span>
        </a>
    </div>
</nav>
<style> @media (max-width: 480px) { .hide-mobile { display: none; } } </style>

<div class="w-container">

    <!-- KIRI: PROFILE SIDEBAR -->
    <aside>
        <div class="w-card" style="margin-bottom: 24px;">
            <div class="profile-main">
                <div class="p-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="p-name">{{ $user->name }}</div>
                <div class="p-email">{{ $user->email }}</div>
                <div class="p-badge"><i class="fas fa-check-circle"></i> Wisatawan Aktif</div>
                <div class="p-join"><i class="fas fa-calendar-alt"></i> Bergabung {{ $user->created_at->format('M Y') }}</div>
            </div>
            <div class="medis-summary">
                <div class="w-label" style="margin-bottom:12px; padding-left:4px;">Info Medis Darurat</div>
                <div class="med-row">
                    <div class="med-lbl"><i class="fas fa-tint" style="color:var(--red);"></i> Gol. Darah</div>
                    <div class="med-val red">{{ $user->gol_darah ?: 'Belum diisi' }}</div>
                </div>
                <div class="med-row">
                    <div class="med-lbl"><i class="fas fa-phone-alt" style="color:var(--orange);"></i> Kontak Darurat</div>
                    <div class="med-val">{{ $user->kontak_darurat ?: 'Belum diisi' }}</div>
                </div>
                <button class="w-btn w-btn-ghost" style="width: 100%; justify-content: center; margin-top: 8px;" onclick="switchTab('medis')">
                    <i class="fas fa-edit"></i> Edit Data Medis
                </button>
            </div>
        </div>
    </aside>

    <!-- KANAN: MAIN CONTENT -->
    <main>
        @if(session('success'))
        <div class="alert-success animate__animated animate__fadeInDown"><i class="fas fa-check-circle" style="font-size:18px;"></i> {{ session('success') }}</div>
        @endif

        <div class="welcome-banner animate__animated animate__fadeIn">
            <div class="wb-title">Halo, {{ explode(' ', trim($user->name))[0] }}! 👋</div>
            <div class="wb-desc">Selamat datang di portal wisatawan WanderMed. Kelola riwayat kesehatan dan temukan fasilitas medis terbaik selama perjalanan Anda di Subang.</div>
        </div>

        <div class="stats-grid">
            <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="sc-icon blue"><i class="fas fa-hospital-user"></i></div>
                <div>
                    <div class="sc-val">{{ $totalKunjungan }}</div>
                    <div class="sc-lbl">Total Kunjungan</div>
                </div>
            </div>
            <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="sc-icon orange"><i class="fas fa-calendar-check"></i></div>
                <div>
                    <div class="sc-val">{{ $kunjunganBulan }}</div>
                    <div class="sc-lbl">Kunjungan Bulan Ini</div>
                </div>
            </div>
            <div class="stat-card animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="sc-icon green"><i class="fas fa-star"></i></div>
                <div>
                    <div class="sc-val">{{ $rekomendasiCount }}</div>
                    <div class="sc-lbl">Direkomendasikan</div>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <div class="w-pills">
            <button class="w-pill active" data-target="tab-riwayat" onclick="switchTab('riwayat')"><i class="fas fa-history"></i> Riwayat Kunjungan</button>
            <button class="w-pill" data-target="tab-profil" onclick="switchTab('profil')"><i class="fas fa-user-cog"></i> Pengaturan Akun</button>
            <button class="w-pill" data-target="tab-medis" onclick="switchTab('medis')"><i class="fas fa-notes-medical"></i> Rekam Medis Pribadi</button>
        </div>

        <!-- TAB CONTENT: RIWAYAT -->
        <div id="tab-riwayat" class="w-pane active">
            <div class="w-card">
                <div class="w-card-header"><i class="fas fa-history"></i> Riwayat Kunjungan Faskes Anda</div>
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
                            <div class="h-icon {{ $cls[$lbl] ?? 'y' }}"><i class="fas {{ $ico[$lbl] ?? 'fa-check' }}"></i></div>
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
                        <p>Anda belum memiliki riwayat kunjungan fasilitas medis yang dicatat.</p>
                        <a href="/peta-faskes" class="w-btn w-btn-orange" style="padding: 12px 24px; font-size: 14px;">
                            <i class="fas fa-map-marked-alt"></i> Jelajahi Peta Faskes Sekarang
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: PROFIL -->
        <div id="tab-profil" class="w-pane">
            <div class="w-card">
                <div class="w-card-header"><i class="fas fa-user-cog"></i> Pengaturan Akun</div>
                <div class="w-card-body">
                    <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                        @csrf
                        <div class="w-form-grid">
                            <div>
                                <label class="w-label">Nama Lengkap</label>
                                <input type="text" name="name" class="w-input" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div>
                                <label class="w-label">Email Utama (Read Only)</label>
                                <input type="email" class="w-input" value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                        <div class="w-form-group">
                            <label class="w-label">Password Baru <span style="text-transform:none; font-weight:400;">(Opsional, kosongkan jika tidak ingin diubah)</span></label>
                            <input type="password" name="password" class="w-input" placeholder="Masukkan minimal 8 karakter rahasia">
                        </div>
                        <div style="text-align: right; margin-top: 24px;">
                            <button type="submit" class="w-btn w-btn-orange" style="padding: 12px 32px;">
                                <i class="fas fa-save"></i> Simpan Perubahan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: MEDIS -->
        <div id="tab-medis" class="w-pane">
            <div class="w-card" style="border-top: 4px solid var(--red);">
                <div class="w-card-header" style="color: var(--red);">
                    <i class="fas fa-notes-medical" style="color:var(--red);"></i> Rekam Medis Darurat Pribadi
                </div>
                <div class="w-card-body">
                    <div style="background: rgba(239,68,68,0.08); border-radius: 12px; padding: 16px; margin-bottom: 24px; display: flex; gap: 12px; color: var(--text);">
                        <i class="fas fa-info-circle" style="color:var(--red); font-size:18px; margin-top:2px;"></i>
                        <div style="font-size: 13px; line-height: 1.5;">
                            <strong>Perhatian:</strong> Informasi ini sangat penting untuk keselamatan Anda. Data medis darurat ini akan ditampilkan pada ulasan Anda, membantu petugas medis di faskes untuk memahami kondisi spesifik Anda jika terjadi keadaan darurat saat Anda berwisata.
                        </div>
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
                                <label class="w-label">Kontak Darurat (Keluarga/Teman)</label>
                                <input type="text" name="kontak_darurat" class="w-input" value="{{ old('kontak_darurat', $user->kontak_darurat) }}" placeholder="Contoh: 08123456789 (Istri)">
                            </div>
                        </div>
                        <div class="w-form-group">
                            <label class="w-label">Riwayat Alergi & Penyakit Bawaan</label>
                            <textarea name="riwayat_alergi" class="w-textarea" placeholder="Sebutkan jika Anda memiliki alergi obat (contoh: Penisilin), makanan, atau penyakit bawaan (contoh: Asma, Hipertensi)...">{{ old('riwayat_alergi', $user->riwayat_alergi) }}</textarea>
                        </div>
                        
                        <div style="text-align: right; margin-top: 24px;">
                            <button type="submit" class="w-btn" style="padding: 12px 32px; background: rgba(239,68,68,0.1); color: var(--red); border: 1px solid rgba(239,68,68,0.3);">
                                <i class="fas fa-shield-alt"></i> Simpan Data Medis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>

</div>

<script>
// INISIALISASI TEMA SEGERA
(function() {
    const t = localStorage.getItem('wanderMedTheme') || 'dark';
    if (t === 'light') {
        document.body.classList.remove('dark');
        document.body.classList.add('light');
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    // Sinkronkan icon tema
    const isLight = document.body.classList.contains('light');
    const ico = document.getElementById('themeIco');
    if (ico) ico.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
});

function toggleTheme() {
    const isLight = document.body.classList.toggle('light');
    if (!isLight) document.body.classList.add('dark'); 
    else document.body.classList.remove('dark');
    
    localStorage.setItem('wanderMedTheme', isLight ? 'light' : 'dark');
    const ico = document.getElementById('themeIco');
    if (ico) ico.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
}

function switchTab(name) {
    // Update Panes
    document.querySelectorAll('.w-pane').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    
    // Update Pills
    document.querySelectorAll('.w-pill').forEach(btn => btn.classList.remove('active'));
    document.querySelector('.w-pill[data-target="tab-' + name + '"]').classList.add('active');
}

// LOGOUT CONFIRMATION (SweetAlert2)
document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const dest = this.href;
            Swal.fire({
                title: 'Keluar dari Akun?',
                html: `<p style="color:var(--text-muted);font-size:14px;margin:0;line-height:1.6;">
                          Sesi Anda akan diakhiri dan Anda akan<br>diarahkan kembali ke halaman login.
                       </p>`,
                icon: 'warning',
                iconColor: '#ff7a00',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Ya, Keluar',
                cancelButtonText:  '<i class="fas fa-times" style="margin-right:6px;"></i>Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup:         'wm-swal-popup',
                    title:         'wm-swal-title',
                    confirmButton: 'wm-swal-confirm',
                    cancelButton:  'wm-swal-cancel',
                    icon:          'wm-swal-icon',
                },
                showClass:  { popup: 'animate__animated animate__fadeInDown animate__faster' },
                hideClass:  { popup: 'animate__animated animate__fadeOutUp animate__faster' },
            }).then(function (result) {
                if (result.isConfirmed) {
                    document.body.style.transition = 'opacity 0.18s ease';
                    document.body.style.opacity    = '0';
                    setTimeout(function () { window.location.href = dest; }, 185);
                }
            });
        });
    }
});
</script>
</body>
</html>
