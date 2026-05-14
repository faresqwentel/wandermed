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
    <a href="#" class="wm-nav-link" id="navJadwal">
        <i class="fas fa-calendar-alt"></i> Jadwal Praktik
    </a>
    <a href="#" class="wm-nav-link" id="navFasilitas">
        <i class="fas fa-clipboard-list"></i> Fasilitas & Layanan
    </a>
    <div class="wm-nav-label">Feedback</div>
    <a href="#" class="wm-nav-link" id="navUlasan">
        <i class="fas fa-star"></i> Ulasan Wisatawan
    </a>
    <div class="wm-nav-label">Profil</div>
    <a href="#" class="wm-nav-link" id="navProfilFaskes">
        <i class="fas fa-hospital"></i> Profil Faskes
    </a>
    <a href="#" class="wm-nav-link" id="navKoordinat">
        <i class="fas fa-map-pin"></i> Update Koordinat
    </a>
    <div class="wm-nav-label">Komunikasi</div>
    <a href="#" class="wm-nav-link" id="navChat" style="position:relative;">
        <i class="fas fa-comments"></i> Chat Admin
        <span id="chatNavBadge" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);background:#ff7a00;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;font-weight:700;align-items:center;justify-content:center;">0</span>
    </a>
    <div class="wm-nav-label">Navigasi</div>
    <a href="/peta-faskes" class="wm-nav-link">
        <i class="fas fa-map-marked-alt"></i> Lihat di Peta
    </a>
    <a href="/logout" class="wm-nav-link">
        <i class="fas fa-sign-out-alt"></i> Keluar
    </a>
@endsection

@section('topbar_bell')
@php $hasPesan = !empty($faskes->pesan_admin); @endphp
<div class="wm-notif-bell" id="wmBellWrap">
    <div class="wm-topbar-icon" onclick="toggleNotifPanel()" title="Notifikasi" style="cursor:pointer;">
        <i class="fas fa-bell"></i>
        @if($hasPesan)
        <span class="wm-notif-badge">1</span>
        @endif
    </div>
    <div class="wm-notif-panel" id="wmNotifPanel">
        {{-- Header --}}
        <div class="wm-notif-header">
            <span><i class="fas fa-bell"></i> Notifikasi</span>
            @if($hasPesan)
            <span class="wm-notif-header-count">1 Baru</span>
            @endif
        </div>
        {{-- Content --}}
        @if($hasPesan)
        <div class="wm-notif-item unread">
            <div class="wm-notif-icon orange"><i class="fas fa-envelope-open-text"></i></div>
            <div class="wm-notif-content">
                <div class="wm-notif-title">Pesan dari Admin WanderMed</div>
                <div class="wm-notif-body">{{ $faskes->pesan_admin }}</div>
                <div class="wm-notif-meta"><i class="fas fa-shield-alt"></i> Dikirim oleh Administrator</div>
            </div>
        </div>
        @else
        <div class="wm-notif-empty">
            <i class="fas fa-bell-slash"></i>
            <p>Tidak ada notifikasi baru</p>
        </div>
        @endif
    </div>
</div>
@endsection

@section('content')

{{-- Data URLs untuk dashboard-faskes.js (menghindari Blade route helper di file .js statis) --}}
<div id="faskesApp"
    data-url-status="{{ route('faskes.status.update') }}"
    data-url-fasilitas="{{ route('faskes.fasilitas.update') }}"
    style="display:contents;">

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

    {{-- Rating Summary Card --}}
    @php
        $rataRating = isset($ulasans) && $ulasans->count() > 0 ? round($ulasans->avg('rating'), 1) : 0;
        $persen = $rataRating > 0 ? ($rataRating / 5) * 100 : 0;
        $totalUlasanCount = $totalUlasan ?? 0;
    @endphp
    <div class="wm-card" style="border-left: 4px solid #f6c23e; margin-bottom: 22px;">
        <div class="wm-card-body" style="padding: 16px 22px; display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
            <div style="text-align:center; min-width:80px;">
                <div style="font-size:2.8rem; font-weight:800; color:#f6c23e; line-height:1;">{{ $rataRating > 0 ? $rataRating : '–' }}</div>
                <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">rata-rata</div>
            </div>
            <div style="flex:1; min-width:160px;">
                <div style="margin-bottom:6px;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star" style="font-size:16px; {{ $i <= round($rataRating) ? 'color:#f6c23e;' : 'color:rgba(255,255,255,0.15);' }}"></i>
                    @endfor
                </div>
                <div style="background:rgba(255,255,255,0.06); border-radius:20px; height:8px; overflow:hidden; margin-bottom:6px;">
                    <div style="background:linear-gradient(90deg,#f6c23e,#e5a800); height:100%; width:{{ $persen }}%; border-radius:20px;"></div>
                </div>
                <div style="font-size:12px; color:var(--text-muted);">
                    Berdasarkan <strong style="color:var(--text-primary);">{{ $totalUlasanCount }}</strong> ulasan wisatawan
                </div>
            </div>
            @if($totalUlasanCount > 0 && isset($ulasans))
            <div style="display:grid; gap:4px; min-width:130px;">
                @for($b = 5; $b >= 1; $b--)
                @php $cnt = $ulasans->where('rating', $b)->count(); $pct = $totalUlasanCount > 0 ? ($cnt / $totalUlasanCount) * 100 : 0; @endphp
                <div style="display:flex; align-items:center; gap:6px; font-size:10px;">
                    <span style="color:#f6c23e; width:10px; text-align:right;">{{ $b }}</span>
                    <i class="fas fa-star" style="font-size:8px; color:#f6c23e;"></i>
                    <div style="flex:1; background:rgba(255,255,255,0.06); border-radius:10px; height:5px; overflow:hidden;">
                        <div style="background:#f6c23e; height:100%; width:{{ $pct }}%;"></div>
                    </div>
                    <span style="color:var(--text-muted); width:16px;">{{ $cnt }}</span>
                </div>
                @endfor
            </div>
            @endif
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
                <textarea class="wm-textarea" id="inputPengumuman" rows="4" placeholder="Contoh: Stok oksigen terbatas hari ini, harap hubungi kami terlebih dahulu..." maxlength="200">{{ $faskes ? $faskes->pengumuman : '' }}</textarea>
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

<!-- ===== SECTION JADWAL PRAKTIK ===== -->
<div id="sectionJadwal" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Manajemen Jadwal Praktik</div>
            <div class="wm-page-subtitle">Atur jadwal dokter yang tersedia di faskes Anda</div>
        </div>
        @if(isset($jadwals) && $jadwals->isNotEmpty())
            @php
                $lastUpdatedJadwal = $jadwals->max('updated_at');
            @endphp
            @if($lastUpdatedJadwal)
            <span style="font-size: 11.5px; color: var(--text-muted); background: rgba(0,0,0,0.03); padding: 4px 10px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05);">
                <i class="fas fa-history mr-1" style="color: #f6c23e;"></i> Terakhir diubah: {{ \Carbon\Carbon::parse($lastUpdatedJadwal)->locale('id')->diffForHumans() }}
            </span>
            @endif
        @endif
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="wm-card">
                <div class="wm-card-header"><div class="wm-card-title">Tambah Jadwal Baru</div></div>
                <div class="wm-card-body">
                    <form action="{{ route('faskes.jadwal.store') }}" method="POST">
                        @csrf
                        <div class="wm-form-group mb-2">
                            <label class="wm-label">Nama Dokter</label>
                            <input type="text" name="nama_dokter" class="wm-input" required maxlength="100">
                        </div>
                        <div class="wm-form-group mb-2">
                            <label class="wm-label">Spesialisasi</label>
                            <input type="text" name="spesialisasi" class="wm-input" placeholder="Misal: Poli Umum, Dokter Gigi" required maxlength="100">
                        </div>
                        <div class="wm-form-group mb-2">
                            <label class="wm-label">Hari Praktik</label>
                            <select name="hari" class="wm-input" required>
                                <option value="Senin">Senin</option><option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option><option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                        </div>
                        <div style="display:flex;gap:10px;" class="mb-3">
                            <div class="wm-form-group flex-1">
                                <label class="wm-label">Jam Mulai</label>
                                <input type="text" name="jam_mulai" class="wm-input" placeholder="08:00" pattern="^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$" title="Format 24 jam (misal 08:00 atau 14:30)" required maxlength="5">
                            </div>
                            <div class="wm-form-group flex-1">
                                <label class="wm-label">Jam Selesai</label>
                                <input type="text" name="jam_selesai" class="wm-input" placeholder="16:00" pattern="^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$" title="Format 24 jam (misal 08:00 atau 14:30)" required maxlength="5">
                            </div>
                        </div>
                        <button type="submit" class="wm-btn blue w-100"><i class="fas fa-plus"></i> Tambah Jadwal</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="wm-card">
                <div class="wm-card-header"><div class="wm-card-title">Daftar Jadwal Praktik</div></div>
                <div class="wm-table-wrap">
                    <table class="wm-table">
                        <thead>
                            <tr><th>Dokter</th><th>Spesialis</th><th>Hari</th><th>Jam</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse($jadwals ?? [] as $jadwal)
                            <tr>
                                <td class="bold">{{ $jadwal->nama_dokter }}</td>
                                <td>{{ $jadwal->spesialisasi }}</td>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ substr($jadwal->jam_mulai,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}</td>
                                <td>
                                    <form action="{{ route('faskes.jadwal.destroy', $jadwal->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="wm-btn danger sm" onclick="return confirm('Hapus jadwal?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada jadwal praktik ditambahkan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== SECTION ULASAN WISATAWAN ===== -->
<div id="sectionUlasan" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Ulasan & Feedback Wisatawan</div>
            <div class="wm-page-subtitle">Baca dan balas ulasan dari wisatawan yang telah berkunjung</div>
        </div>
    </div>
    <div class="wm-card">
        <div class="wm-card-body" style="padding: 0;">
            @forelse($ulasans ?? [] as $ulasan)
            @php
                $reviewer = $ulasan->user;
                $hasAlergi = $reviewer && !empty($reviewer->riwayat_alergi);
                $hasGolDarah = $reviewer && !empty($reviewer->gol_darah);
                $initial = $reviewer ? strtoupper(substr($reviewer->name, 0, 1)) : '?';
            @endphp
            <div style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05);">

                {{-- Header: Avatar + Nama + Bintang + Tanggal --}}
                <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:12px;">
                    <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,#4e73df,#224abe); display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; font-size:16px; flex-shrink:0;">
                        {{ $initial }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:6px;">
                            <div>
                                <strong style="font-size:14px;">{{ $reviewer->name ?? 'Wisatawan' }}</strong>
                                <span style="margin-left:8px; color:#f6c23e;">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fas fa-star" style="font-size:11px; {{ $i <= $ulasan->rating ? 'color:#f6c23e;' : 'color:rgba(255,255,255,0.15);' }}"></i>
                                    @endfor
                                    <span style="font-size:11px; color:var(--text-muted); margin-left:4px;">({{ $ulasan->rating }}/5)</span>
                                </span>
                            </div>
                            <small style="color:var(--text-muted); font-size:11px; white-space:nowrap;">
                                <i class="fas fa-clock mr-1"></i>{{ $ulasan->created_at->format('d M Y, H:i') }}
                            </small>
                        </div>

                        {{-- Medical Info Chips --}}
                        @if($hasGolDarah || $hasAlergi)
                        <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:6px;">
                            @if($hasGolDarah)
                            <span style="display:inline-flex; align-items:center; gap:4px; background:rgba(231,74,59,0.1); color:#e74a3b; border:1px solid rgba(231,74,59,0.3); border-radius:20px; padding:2px 9px; font-size:10px; font-weight:700;">
                                <i class="fas fa-tint" style="font-size:9px;"></i> Gol. Darah: {{ $reviewer->gol_darah }}
                            </span>
                            @endif
                            @if($hasAlergi)
                            <span style="display:inline-flex; align-items:center; gap:4px; background:rgba(246,194,62,0.1); color:#f6c23e; border:1px solid rgba(246,194,62,0.3); border-radius:20px; padding:2px 9px; font-size:10px; font-weight:600;" title="{{ $reviewer->riwayat_alergi }}">
                                <i class="fas fa-exclamation-triangle" style="font-size:9px;"></i>
                                Alergi: {{ Str::limit($reviewer->riwayat_alergi, 50) }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Komentar --}}
                <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:12px 14px; font-size:13px; color:var(--text-secondary); line-height:1.6; margin-bottom:12px; border-left:3px solid rgba(255,255,255,0.1);">
                    "{{ $ulasan->komentar }}"
                </div>

                {{-- Balasan dihapus sesuai request user (tidak perlu fitur balas) --}}
            </div>
            @empty
            <div class="text-center py-5" style="color:var(--text-muted);">
                <i class="fas fa-comment-slash fa-2x mb-3 d-block" style="opacity:0.3;"></i>
                Belum ada ulasan masuk dari wisatawan.
            </div>
            @endforelse
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
                        <input type="text" name="nama_faskes" class="wm-input" value="{{ $faskes->nama_faskes ?? '' }}" placeholder="Contoh: RSUD Subang" required maxlength="100">
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
                        <input type="tel" name="no_telp" class="wm-input" value="{{ $faskes->no_telp ?? '' }}" placeholder="0260-xxxxxx" maxlength="15">
                    </div>
                    <div class="wm-form-group" style="grid-column: 1/-1;">
                        <label class="wm-label">Alamat Lengkap <span style="color:#e74a3b">*</span></label>
                        <textarea name="alamat" class="wm-textarea" rows="2" required maxlength="200">{{ $faskes->alamat ?? '' }}</textarea>
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

{{-- ===== SECTION CHAT ADMIN ===== --}}
@include('partials.chat_mitra')

@endsection

</div>{{-- /faskesApp --}}

@push('scripts')
<script src="{{ asset('js/dashboard-faskes.js') }}"></script>
<script src="{{ asset('js/chat-mitra.js') }}"></script>
@endpush

