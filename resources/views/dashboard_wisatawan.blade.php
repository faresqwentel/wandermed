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
<link href="{{ asset('css/dashboard-wisatawan.css') }}" rel="stylesheet">
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

        <div class="stats-wrapper animate__animated animate__fadeInUp">
            <div class="stats-header">
                <i class="fas fa-chart-pie"></i> Ringkasan Aktivitas Anda
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="sc-icon blue"><i class="fas fa-hospital-user"></i></div>
                    <div>
                        <div class="sc-val">{{ $totalKunjungan }}</div>
                        <div class="sc-lbl">Total Kunjungan</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="sc-icon orange"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="sc-val">{{ $kunjunganBulan }}</div>
                        <div class="sc-lbl">Kunjungan Bulan Ini</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="sc-icon green"><i class="fas fa-star"></i></div>
                    <div>
                        <div class="sc-val">{{ $rekomendasiCount }}</div>
                        <div class="sc-lbl">Direkomendasikan</div>
                    </div>
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

<script src="{{ asset('js/dashboard-wisatawan.js') }}"></script>
</body>
</html>
