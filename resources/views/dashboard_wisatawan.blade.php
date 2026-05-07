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
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --bg:        #0d1423;
    --bg2:       #111827;
    --surface:   rgba(255,255,255,0.05);
    --border:    rgba(255,255,255,0.09);
    --text:      #e8ecf4;
    --muted:     rgba(255,255,255,0.4);
    --orange:    #ff7a00;
    --red:       #e74a3b;
    --green:     #28a745;
    --yellow:    #f6c23e;
}
.light {
    --bg:      #f0f4f8;
    --bg2:     #fff;
    --surface: rgba(0,0,0,0.03);
    --border:  rgba(0,0,0,0.08);
    --text:    #1a202c;
    --muted:   rgba(0,0,0,0.4);
}
body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    transition: background 0.3s, color 0.3s;
}

/* ── TOP NAV ── */
.w-nav {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--bg2);
    border-bottom: 1px solid var(--border);
    padding: 0 20px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.w-nav-brand {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 800;
    font-size: 18px;
    color: var(--text);
    text-decoration: none;
}
.w-nav-brand i { color: var(--orange); font-size: 22px; }
.w-nav-actions { display: flex; align-items: center; gap: 8px; }
.w-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: opacity 0.2s;
}
.w-btn:hover { opacity: 0.82; }
.w-btn-ghost {
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text);
}
.w-btn-orange { background: var(--orange); color: #fff; }
.w-btn-red    { background: rgba(231,74,59,0.12); color: var(--red); border: 1px solid rgba(231,74,59,0.25); }

/* ── HERO ── */
.w-hero {
    padding: 40px 20px 0;
    text-align: center;
    max-width: 760px;
    margin: 0 auto;
}
.w-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff7a00, #e65c00);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    margin: 0 auto 14px;
    box-shadow: 0 0 0 4px rgba(255,122,0,0.2), 0 8px 20px rgba(255,122,0,0.25);
}
.w-name { font-size: 1.3rem; font-weight: 700; margin-bottom: 4px; }
.w-email { font-size: 12px; color: var(--muted); margin-bottom: 14px; }
.w-chips { display: flex; justify-content: center; flex-wrap: wrap; gap: 6px; margin-bottom: 20px; }
.w-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--muted);
}
.w-chip i { font-size: 9px; }

/* Stats */
.w-stats {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}
.w-stat {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px 22px;
    text-align: center;
    min-width: 100px;
}
.w-stat-val { font-size: 1.8rem; font-weight: 800; color: var(--orange); line-height: 1; }
.w-stat-lbl { font-size: 10px; color: var(--muted); margin-top: 3px; }

/* Tabs */
.w-tabs {
    display: flex;
    border-bottom: 1px solid var(--border);
    justify-content: center;
    gap: 0;
}
.w-tab {
    padding: 12px 24px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--muted);
    cursor: pointer;
    margin-bottom: -1px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.w-tab.active { color: var(--orange); border-bottom-color: var(--orange); }
.w-tab:hover:not(.active) { color: var(--text); }

/* Body */
.w-body { padding: 28px 20px 80px; max-width: 760px; margin: 0 auto; }
.w-pane { display: none; }
.w-pane.active { display: block; }

/* Card */
.w-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 18px;
}
.light .w-card { box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
.w-card-hd {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    font-weight: 700;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.w-card-hd i { color: var(--orange); }

/* Riwayat */
.rw-row {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
}
.rw-row:last-child { border-bottom: none; }
.rw-icon {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.rw-icon.g { background: rgba(40,167,69,0.12); color: #4caf82; }
.rw-icon.y { background: rgba(246,194,62,0.12); color: #e5a800; }
.rw-icon.r { background: rgba(231,74,59,0.12); color: var(--red); }
.rw-main { flex: 1; min-width: 0; }
.rw-name { font-weight: 600; font-size: 14px; margin-bottom: 2px; }
.rw-date { font-size: 11px; color: var(--muted); }
.rw-note { font-size: 11px; color: var(--muted); margin-top: 3px; font-style: italic; }
.lbl {
    padding: 3px 10px; border-radius: 20px; font-size: 10px;
    font-weight: 700; white-space: nowrap; flex-shrink: 0; align-self: flex-start;
}
.lbl-g { background: rgba(40,167,69,0.1); color: #4caf82; border: 1px solid rgba(40,167,69,0.2); }
.lbl-y { background: rgba(246,194,62,0.1); color: #e5a800; border: 1px solid rgba(246,194,62,0.2); }
.lbl-r { background: rgba(231,74,59,0.1); color: var(--red); border: 1px solid rgba(231,74,59,0.2); }

/* Form */
.w-label {
    display: block; font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.7px;
    color: var(--muted); margin-bottom: 6px;
}
.w-input, .w-select, .w-textarea {
    width: 100%;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 9px;
    padding: 11px 14px;
    color: var(--text);
    font-size: 13px;
    font-family: inherit;
    transition: border-color 0.2s;
}
.light .w-input, .light .w-select, .light .w-textarea {
    background: #f7f9fc;
    border-color: #d1d9e0;
    color: #1a202c;
}
.w-input:focus, .w-select:focus, .w-textarea:focus {
    outline: none;
    border-color: var(--orange);
}
.w-input:disabled { opacity: 0.4; cursor: not-allowed; }
.w-textarea { resize: vertical; min-height: 80px; }
.w-select { appearance: none; }
.w-select option { background: #1a2035; color: #fff; }
.light .w-select option { background: #fff; color: #1a202c; }
.w-mb { margin-bottom: 16px; }
.w-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 520px) { .w-row { grid-template-columns: 1fr; } }

/* Alert */
.w-alert-ok {
    background: rgba(40,167,69,0.08);
    border: 1px solid rgba(40,167,69,0.2);
    border-radius: 9px;
    color: #4caf82;
    padding: 10px 14px;
    font-size: 12px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.light .w-alert-ok { background: #f0faf4; border-color: #b7dfc5; color: #276749; }

/* Medis info box */
.w-info-box {
    background: rgba(231,74,59,0.06);
    border: 1px solid rgba(231,74,59,0.15);
    border-radius: 9px;
    padding: 10px 14px;
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 16px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}
.w-info-box i { color: var(--red); flex-shrink: 0; margin-top: 2px; }
</style>
</head>
<body>

{{-- NAV --}}
<nav class="w-nav">
    <a href="/" class="w-nav-brand">
        <i class="fas fa-heartbeat"></i> WanderMed
    </a>
    <div class="w-nav-actions">
        <a href="/peta-faskes" class="w-btn w-btn-ghost">
            <i class="fas fa-map-marked-alt"></i>
            <span style="display:none;" id="mapLabel">Peta</span>
        </a>
        <button id="themeBtn" class="w-btn w-btn-ghost" onclick="toggleTheme()" title="Ganti Mode">
            <i class="fas fa-sun" id="themeIco"></i>
        </button>
        <a href="/logout" class="w-btn w-btn-red" onclick="return confirm('Yakin ingin keluar?')">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </div>
</nav>

<script>
// Inisialisasi tema sebelum render
(function() {
    const t = localStorage.getItem('wanderMedTheme');
    if (t === 'light') {
        document.body.classList.add('light');
        document.getElementById('themeIco') && (document.getElementById('themeIco').className = 'fas fa-moon');
    }
    // Show map label on larger screen
    if (window.innerWidth > 460) {
        const lbl = document.getElementById('mapLabel');
        if (lbl) lbl.style.display = 'inline';
    }
})();
function toggleTheme() {
    const isLight = document.body.classList.toggle('light');
    localStorage.setItem('wanderMedTheme', isLight ? 'light' : 'dark');
    document.getElementById('themeIco').className = isLight ? 'fas fa-moon' : 'fas fa-sun';
}
</script>

{{-- HERO --}}
<div class="w-hero">
    @if(session('success'))
    <div class="w-alert-ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="w-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
    <div class="w-name">{{ $user->name }}</div>
    <div class="w-email">{{ $user->email }}</div>

    <div class="w-chips">
        <span class="w-chip" style="color: #e74a3b; border-color: rgba(231,74,59,0.25);">
            <i class="fas fa-tint"></i> Gol. Darah: <strong>{{ $user->gol_darah ?: '–' }}</strong>
        </span>
        <span class="w-chip" style="color: var(--orange); border-color: rgba(255,122,0,0.25);">
            <i class="fas fa-shield-alt"></i> Wisatawan Aktif
        </span>
        <span class="w-chip">
            <i class="fas fa-calendar-alt"></i> Bergabung {{ $user->created_at->format('M Y') }}
        </span>
    </div>

    <div class="w-stats">
        <div class="w-stat"><div class="w-stat-val">{{ $totalKunjungan }}</div><div class="w-stat-lbl">Faskes Dikunjungi</div></div>
        <div class="w-stat"><div class="w-stat-val">{{ $kunjunganBulan }}</div><div class="w-stat-lbl">Bulan Ini</div></div>
        <div class="w-stat"><div class="w-stat-val">{{ $rekomendasiCount }}</div><div class="w-stat-lbl">Direkomendasikan</div></div>
    </div>

    <div class="w-tabs">
        <button class="w-tab active" onclick="switchTab('riwayat', this)">
            <i class="fas fa-history"></i> Riwayat
        </button>
        <button class="w-tab" onclick="switchTab('profil', this)">
            <i class="fas fa-user-cog"></i> Profil
        </button>
        <button class="w-tab" onclick="switchTab('medis', this)">
            <i class="fas fa-heartbeat" style="color:var(--red);"></i> Data Medis
        </button>
    </div>
</div>

{{-- BODY --}}
<div class="w-body">

    {{-- TAB: RIWAYAT --}}
    <div id="tab-riwayat" class="w-pane active">
        <div class="w-card">
            <div class="w-card-hd"><i class="fas fa-history"></i> Riwayat Kunjungan Faskes</div>
            @forelse($riwayats as $r)
            @php
                $lbl = $r->label_warna ?? 'yellow';
                $ico = ['green'=>'fa-star','yellow'=>'fa-check','red'=>'fa-times'];
                $txt = ['green'=>'Direkomendasikan','yellow'=>'Cukup Baik','red'=>'Tidak Direkomendasikan'];
                $cls = ['green'=>'g','yellow'=>'y','red'=>'r'];
            @endphp
            <div class="rw-row">
                <div class="rw-icon {{ $cls[$lbl] ?? 'y' }}">
                    <i class="fas {{ $ico[$lbl] ?? 'fa-check' }}"></i>
                </div>
                <div class="rw-main">
                    <div class="rw-name">{{ $r->faskes ? $r->faskes->nama_faskes : 'Faskes Dihapus' }}</div>
                    <div class="rw-date"><i class="fas fa-calendar-alt" style="font-size:9px; margin-right:4px;"></i>{{ $r->tanggal_kunjungan->format('d M Y') }}</div>
                    @if($r->catatan_pribadi)
                    <div class="rw-note"><i class="fas fa-sticky-note" style="font-size:9px; margin-right:4px;"></i>{{ $r->catatan_pribadi }}</div>
                    @endif
                </div>
                <span class="lbl lbl-{{ $cls[$lbl] ?? 'y' }}">{{ $txt[$lbl] ?? '' }}</span>
            </div>
            @empty
            <div style="text-align:center; padding: 48px 20px; color: var(--muted);">
                <i class="fas fa-map-marked-alt" style="font-size: 2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
                <p style="margin-bottom:16px;">Belum ada riwayat kunjungan.</p>
                <a href="/peta-faskes" class="w-btn w-btn-orange" style="display:inline-flex;">
                    <i class="fas fa-map"></i> Jelajahi Peta Faskes
                </a>
            </div>
            @endforelse
        </div>

        <div style="text-align:center; margin-top: 12px;">
            <a href="/peta-faskes" class="w-btn w-btn-orange" style="display:inline-flex; font-size:13px;">
                <i class="fas fa-map"></i> Buka Peta Faskes
            </a>
        </div>
    </div>

    {{-- TAB: PROFIL --}}
    <div id="tab-profil" class="w-pane">
        <div class="w-card">
            <div class="w-card-hd"><i class="fas fa-user-cog"></i> Edit Profil Akun</div>
            <div style="padding: 22px;">
                <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                    @csrf
                    <div class="w-row w-mb">
                        <div>
                            <label class="w-label">Nama Lengkap</label>
                            <input type="text" name="name" class="w-input" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div>
                            <label class="w-label">Email (tidak bisa diubah)</label>
                            <input type="email" class="w-input" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    <div class="w-mb">
                        <label class="w-label">Password Baru <span style="opacity:0.4; text-transform: none; font-weight:400;">(kosongkan jika tidak ingin ganti)</span></label>
                        <input type="password" name="password" class="w-input" placeholder="Minimal 8 karakter...">
                    </div>
                    <button type="submit" class="w-btn w-btn-orange" style="width:100%; justify-content:center; padding:12px;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- TAB: MEDIS --}}
    <div id="tab-medis" class="w-pane">
        <div class="w-info-box">
            <i class="fas fa-info-circle"></i>
            <span>Data ini akan <strong style="color:var(--text);">ditampilkan pada ulasan Anda</strong> di halaman faskes, membantu faskes memahami kondisi Anda.</span>
        </div>
        <div class="w-card" style="border-left: 3px solid var(--red);">
            <div class="w-card-hd" style="border-color: rgba(231,74,59,0.15);">
                <i class="fas fa-heartbeat" style="color: var(--red);"></i> Data Medis Darurat
            </div>
            <div style="padding: 22px;">
                <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <div class="w-row w-mb">
                        <div>
                            <label class="w-label">Golongan Darah</label>
                            <select name="gol_darah" class="w-select">
                                <option value="">– Pilih –</option>
                                @foreach(['A','B','AB','O'] as $gd)
                                <option value="{{ $gd }}" {{ $user->gol_darah == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="w-label">Kontak Darurat</label>
                            <input type="text" name="kontak_darurat" class="w-input" value="{{ old('kontak_darurat', $user->kontak_darurat) }}" placeholder="No. HP keluarga/pendamping">
                        </div>
                    </div>
                    <div class="w-mb">
                        <label class="w-label">Riwayat Alergi / Penyakit</label>
                        <textarea name="riwayat_alergi" class="w-textarea" placeholder="Contoh: Alergi penisilin, riwayat asma, diabetes...">{{ old('riwayat_alergi', $user->riwayat_alergi) }}</textarea>
                    </div>
                    <button type="submit" class="w-btn" style="width:100%; justify-content:center; padding:12px; background:rgba(231,74,59,0.1); color:var(--red); border:1px solid rgba(231,74,59,0.3); font-size:13px;">
                        <i class="fas fa-shield-alt"></i> Simpan Data Medis
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.w-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.w-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
    window.scrollTo({ top: document.querySelector('.w-hero').offsetHeight - 60, behavior: 'smooth' });
}
</script>
</body>
</html>
