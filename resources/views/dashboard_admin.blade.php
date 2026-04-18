{{-- ============================================================
     Dashboard Administrator – WanderMed
     Layout: theme/dashboard_layout.blade.php
     ============================================================ --}}
@extends('theme.dashboard_layout')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('page_title', 'Admin Dashboard')
@section('badge_role', 'Administrator')
@section('user_name', 'Administrator')
@section('user_role', 'Super Administrator')
@section('user_initial', 'A')
@section('topbar_title')
    Pusat <span style="color:#ff7a00;">Kendali Utama</span>
@endsection

@section('sidebar_nav')
    <div class="wm-nav-label">Overview</div>
    <a href="#" class="wm-nav-link active" id="navDashboard">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <div class="wm-nav-label">Validasi & Moderasi</div>
    <a href="#" class="wm-nav-link" id="navValidasi">
        <i class="fas fa-user-check"></i> Validasi Mitra
        <span class="badge-pill-side" id="navPendingCount">{{ $pendingMitra }}</span>
    </a>
    <a href="#" class="wm-nav-link" id="navLaporan">
        <i class="fas fa-exclamation-triangle"></i> Laporan Masalah
        <span class="badge-pill-side">{{ $laporans->where('status', 'pending')->count() }}</span>
    </a>

    <div class="wm-nav-label">Data Master</div>
    <a href="#" class="wm-nav-link" id="navDataFaskes">
        <i class="fas fa-clinic-medical"></i> Fasilitas Kesehatan
    </a>
    <a href="#" class="wm-nav-link" id="navDataPariwisata">
        <i class="fas fa-mountain"></i> Destinasi Pariwisata
    </a>
    <a href="#" class="wm-nav-link" id="navDataWisatawan">
        <i class="fas fa-users"></i> Data Wisatawan
    </a>

    <div class="wm-nav-label">Sistem</div>
    <a href="/peta-faskes" class="wm-nav-link">
        <i class="fas fa-map-marked-alt"></i> Lihat Peta Publik
    </a>
    <a href="/logout" class="wm-nav-link">
        <i class="fas fa-sign-out-alt"></i> Keluar
    </a>
@endsection

@section('content')

{{-- ==================== SECTION: DASHBOARD UTAMA ==================== --}}
<div id="sectionDashboard" class="admin-section">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Helicopter View Sistem</div>
            <div class="wm-page-subtitle">Pantau seluruh aktivitas platform WanderMed secara real-time</div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="wm-stat-grid" style="grid-template-columns: repeat(4, 1fr);">
        <div class="wm-stat-card blue">
            <div class="wm-stat-icon"><i class="fas fa-user-friends"></i></div>
            <div>
                <div class="wm-stat-value">{{ number_format($totalWisatawan) }}</div>
                <div class="wm-stat-label">Total Wisatawan</div>
            </div>
        </div>
        <div class="wm-stat-card green">
            <div class="wm-stat-icon"><i class="fas fa-clinic-medical"></i></div>
            <div>
                <div class="wm-stat-value">{{ number_format($totalFaskes) }}</div>
                <div class="wm-stat-label">Mitra Faskes</div>
            </div>
        </div>
        <div class="wm-stat-card teal">
            <div class="wm-stat-icon"><i class="fas fa-mountain"></i></div>
            <div>
                <div class="wm-stat-value">{{ number_format($totalPariwisata) }}</div>
                <div class="wm-stat-label">Mitra Pariwisata</div>
            </div>
        </div>
        <div class="wm-stat-card orange">
            <div class="wm-stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div class="wm-stat-value" id="cardPendingCount">{{ $pendingMitra }}</div>
                <div class="wm-stat-label">Menunggu Validasi</div>
            </div>
        </div>
    </div>

    {{-- Widget 5 Pendaftar Terbaru --}}
    <div class="wm-card" style="margin-bottom: 22px;">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-bell" style="color: var(--orange);"></i>
                Pendaftar Terbaru
                <span style="font-size: 11px; font-weight: 400; color: var(--text-muted); margin-left: 6px;">5 terkini</span>
            </div>
            <a href="#" class="wm-btn ghost sm" id="navValidasiLink">Lihat Semua →</a>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th>Nama / Institusi</th><th>Jenis</th><th>Tgl Daftar</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $recentFaskes = $mitraPending->take(3)->map(fn($m) => (object)[
                            'nama' => $m->nama_penanggung_jawab . ($m->faskes ? ' – ' . $m->faskes->nama_faskes : ''),
                            'jenis' => 'Faskes', 'tanggal' => $m->created_at
                        ]);
                        $recentWisata = $wisataPending->take(2)->map(fn($w) => (object)[
                            'nama' => $w->nama_pengelola . ' – ' . $w->nama_wisata,
                            'jenis' => 'Pariwisata', 'tanggal' => $w->created_at
                        ]);
                        $recents = $recentFaskes->merge($recentWisata)->sortByDesc('tanggal')->take(5);
                    @endphp
                    @forelse($recents as $r)
                    <tr>
                        <td class="bold">{{ $r->nama }}</td>
                        <td>
                            @if($r->jenis === 'Faskes')
                                <span class="wm-badge green"><i class="fas fa-clinic-medical"></i> Faskes</span>
                            @else
                                <span class="wm-badge teal"><i class="fas fa-mountain"></i> Pariwisata</span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted); font-size: 12px;">{{ $r->tanggal->format('d M Y') }}</td>
                        <td><span class="wm-badge yellow"><i class="fas fa-clock"></i> Menunggu</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center" style="padding: 16px; color: var(--text-muted);">Tidak ada pendaftar baru ✅</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== SECTION: VALIDASI MITRA ==================== --}}
<div id="sectionValidasi" class="admin-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Antrean Validasi Mitra</div>
            <div class="wm-page-subtitle">Klik Detail untuk memeriksa data lengkap, lalu berikan keputusan</div>
        </div>
    </div>
    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-user-check"></i> Antrean Validasi Mitra Baru
                <span class="wm-badge orange" style="margin-left: 8px; font-size: 10px;" id="tableBadgePending">{{ $pendingMitra }} Pending</span>
            </div>
            <div style="position: relative; flex: 0 0 220px;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" id="filterValidasiInput" class="wm-input" style="padding-left: 36px; height: 36px; font-size: 12px;"
                    placeholder="Cari nama mitra..." onkeyup="filterTable('filterValidasiInput', 'validasiTable', 'col-nama')">
            </div>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table" id="validasiTable">
                <thead>
                    <tr>
                        <th>Nama PJ / Institusi</th>
                        <th>Jenis Mitra</th>
                        <th>Email & Telp</th>
                        <th>Dokumen</th>
                        <th>Tgl Daftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mitraPending as $m)
                    <tr id="mitraRow-{{ $m->id }}">
                        <td class="bold col-nama">
                            {{ $m->nama_penanggung_jawab }}
                            @if($m->faskes)
                            <div style="font-size:11px; color: var(--text-muted); font-weight:400; margin-top:3px;">
                                <i class="fas fa-hospital-alt mr-1"></i>{{ $m->faskes->nama_faskes }}
                                &nbsp;|&nbsp;
                                <span style="font-family:monospace;">{{ $m->faskes->latitude }}, {{ $m->faskes->longitude }}</span>
                            </div>
                            @endif
                        </td>
                        <td><span class="wm-badge green"><i class="fas fa-clinic-medical"></i> Faskes</span></td>
                        <td style="color: var(--text-muted); font-size:12px;">
                            {{ $m->email }}<br>
                            <i class="fas fa-phone-alt mr-1"></i>{{ $m->no_telp ?? '-' }}
                        </td>
                        <td>
                            @if($m->catatan_admin && !str_contains($m->catatan_admin ?? '', ' '))
                                <a href="{{ Storage::url($m->catatan_admin) }}" target="_blank" class="wm-btn info sm">
                                    <i class="fas fa-file-alt"></i> Lihat
                                </a>
                            @else
                                <span style="color: var(--text-muted); font-size:12px;"><i class="fas fa-minus"></i> Tidak ada</span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted); font-size:12px;">{{ $m->created_at->format('d M Y') }}</td>
                        <td style="text-align: center;">
                            <button class="wm-btn info sm" onclick='showDetailFaskes(@json($m))'>
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach

                    @foreach($wisataPending as $w)
                    <tr id="wisataRow-{{ $w->id }}">
                        <td class="bold col-nama">
                            {{ $w->nama_pengelola }}
                            <div style="font-size:11px; color: var(--text-muted); font-weight:400; margin-top:3px;">
                                <i class="fas fa-mountain mr-1"></i>{{ $w->nama_wisata }} ({{ $w->kategori }})
                                &nbsp;|&nbsp;
                                <span style="font-family:monospace;">{{ $w->latitude }}, {{ $w->longitude }}</span>
                            </div>
                        </td>
                        <td><span class="wm-badge teal"><i class="fas fa-mountain"></i> Pariwisata</span></td>
                        <td style="color: var(--text-muted); font-size:12px;">
                            {{ $w->email_kontak }}<br>
                            <i class="fas fa-phone-alt mr-1"></i>{{ $w->no_telp ?? '-' }}
                        </td>
                        <td>
                            @if($w->foto_path)
                                <a href="{{ Storage::url($w->foto_path) }}" target="_blank" class="wm-btn info sm">
                                    <i class="fas fa-file-alt"></i> Lihat
                                </a>
                            @else
                                <span style="color: var(--text-muted); font-size:12px;"><i class="fas fa-minus"></i> Tidak ada</span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted); font-size:12px;">{{ $w->created_at->format('d M Y') }}</td>
                        <td style="text-align: center;">
                            <button class="wm-btn info sm" onclick='showDetailWisata(@json($w))'>
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach

                    @if($mitraPending->isEmpty() && $wisataPending->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 20px; color: #888;">Tidak ada antrean validasi mitra saat ini. ✅</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== SECTION: LAPORAN MASALAH ==================== --}}
<div id="sectionLaporan" class="admin-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Laporan Masalah Wisatawan</div>
            <div class="wm-page-subtitle">Tinjau dan selesaikan laporan yang masuk dari pengguna</div>
        </div>
    </div>
    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-exclamation-triangle" style="color: #f6c23e;"></i>
                Laporan Masalah dari Wisatawan
            </div>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table" id="laporanTable">
                <thead>
                    <tr>
                        <th>ID Tiket</th><th>Pelapor</th><th>Isi Laporan</th><th>Terkait Mitra</th><th>Status</th><th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $lap)
                    <tr>
                        <td class="bold" style="color: var(--orange);">#TKT-{{ str_pad($lap->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $lap->user->name ?? 'Anonim' }}</td>
                        <td style="max-width: 280px; color: var(--text-muted); font-size: 12px; font-style: italic;">
                            "{{ $lap->deskripsi }}"
                        </td>
                        <td class="bold">{{ $lap->faskes ? $lap->faskes->nama_faskes : 'Tidak spesifik' }}</td>
                        <td>
                            @if($lap->status == 'pending')
                            <span class="wm-badge yellow"><i class="fas fa-clock"></i> Pending</span>
                            @elseif($lap->status == 'resolved')
                            <span class="wm-badge green"><i class="fas fa-check-circle"></i> Resolved</span>
                            @else
                            <span class="wm-badge info"><i class="fas fa-spinner fa-spin"></i> On Review</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($lap->status != 'resolved')
                            <button class="wm-btn orange sm" onclick="resolveTicket(this, {{ $lap->id }}, '#TKT-{{ str_pad($lap->id, 4, '0', STR_PAD_LEFT) }}')">
                                <i class="fas fa-tools"></i> Selesaikan
                            </button>
                            @else
                            <button class="wm-btn ghost sm" disabled style="opacity:0.5; cursor:not-allowed;">
                                <i class="fas fa-lock"></i> Closed
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 20px; color: #888;">Belum ada laporan masalah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== SECTION: DATA MASTER FASKES ==================== --}}
<div id="sectionFaskes" class="admin-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Fasilitas Kesehatan</div>
            <div class="wm-page-subtitle">Kelola lokasi, status, fasilitas, dan pesan untuk setiap mitra faskes</div>
        </div>
    </div>

    @foreach($faskesList as $faskesItem)
    <div class="wm-card" style="margin-bottom: 20px;" id="faskesCard-{{ $faskesItem->id }}">
        {{-- Header Card --}}
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-clinic-medical" style="color:#ff7a00;"></i>
                {{ $faskesItem->nama_faskes }}
                <span class="wm-badge" style="margin-left:8px;background:rgba(56,161,105,0.1);color:#38a169;border:1px solid rgba(56,161,105,0.3);font-size:10px;">
                    {{ $faskesItem->jenis_faskes }}
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                {{-- Status Operasional --}}
                @if($faskesItem->status_operasional == 'open')
                    <span class="wm-badge green" id="statusBadge-{{ $faskesItem->id }}"><i class="fas fa-circle" style="font-size:8px;"></i> Buka</span>
                @else
                    <span class="wm-badge danger" id="statusBadge-{{ $faskesItem->id }}"><i class="fas fa-circle" style="font-size:8px;"></i> Tutup</span>
                @endif
                {{-- Toggle Status Operasional --}}
                <button class="wm-btn ghost sm" onclick="toggleStatusFaskes(this, {{ $faskesItem->id }})" title="Toggle Status Operasional">
                    <i class="fas fa-power-off"></i>
                </button>
            </div>
        </div>

        <div class="modal-body" style="padding: 16px 20px;">
            <div class="row">
                {{-- Kolom Kiri: Koordinat + BPJS --}}
                <div class="col-md-6">
                    <div style="margin-bottom:16px;">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:6px;">
                            <i class="fas fa-map-pin" style="color:#ff7a00;"></i> Koordinat Lokasi
                        </label>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="number" step="any" id="lat-{{ $faskesItem->id }}" value="{{ $faskesItem->latitude }}"
                                class="wm-input" style="width:130px;padding:6px 10px;font-size:12px;" placeholder="Latitude">
                            <input type="number" step="any" id="lng-{{ $faskesItem->id }}" value="{{ $faskesItem->longitude }}"
                                class="wm-input" style="width:130px;padding:6px 10px;font-size:12px;" placeholder="Longitude">
                        </div>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:6px;">
                            <i class="fas fa-id-card" style="color:#3182ce;"></i> Dukungan BPJS
                        </label>
                        <select id="bpjs-{{ $faskesItem->id }}" class="wm-input" style="font-size:12px;padding:6px 10px;">
                            <option value="1" {{ ($faskesItem->dukungan_bpjs ?? false) ? 'selected' : '' }}>✅ Menerima BPJS</option>
                            <option value="0" {{ !($faskesItem->dukungan_bpjs ?? false) ? 'selected' : '' }}>❌ Non-BPJS</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:6px;">
                            <i class="fas fa-stethoscope" style="color:#805ad5;"></i> Layanan / Fasilitas Utama
                        </label>
                        <textarea id="layanan-{{ $faskesItem->id }}" class="wm-input" rows="2"
                            style="font-size:12px;padding:8px 10px;resize:vertical;width:100%;"
                            placeholder="Contoh: IGD 24 Jam, Poli Umum, Rawat Inap...">{{ $faskesItem->pengumuman ?? '' }}</textarea>
                    </div>
                </div>

                {{-- Kolom Kanan: Pesan Admin --}}
                <div class="col-md-6">
                    <div style="background:#fffbeb;border:1px solid #f6e05e;border-radius:10px;padding:14px;margin-bottom:16px;">
                        <label style="font-size:11px;font-weight:700;color:#d69e2e;text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:8px;">
                            <i class="fas fa-comment-dots"></i> Pesan untuk Mitra Faskes
                        </label>
                        <textarea id="pesan-{{ $faskesItem->id }}" rows="4"
                            style="width:100%;border:1px solid #f6e05e;border-radius:8px;padding:10px;font-size:12px;background:#fffff0;resize:vertical;font-family:inherit;"
                            placeholder="Ketik catatan/instruksi untuk mitra ini... (akan tampil di dashboard mereka)">{{ $faskesItem->pesan_admin ?? '' }}</textarea>
                        @if($faskesItem->pesan_admin)
                        <div style="font-size:10px;color:#d69e2e;margin-top:4px;"><i class="fas fa-info-circle"></i> Pesan terakhir telah dikirimkan</div>
                        @endif
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:8px;">
                        <button class="wm-btn ghost sm" onclick="resetFaskes({{ $faskesItem->id }})">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button class="wm-btn orange" onclick="saveFaskesData(this, {{ $faskesItem->id }})" style="padding:8px 20px;font-size:13px;">
                            <i class="fas fa-save"></i> Simpan Semua
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ==================== SECTION: DATA MASTER PARIWISATA ==================== --}}
<div id="sectionPariwisata" class="admin-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Destinasi Pariwisata</div>
            <div class="wm-page-subtitle">Data destinasi wisata terverifikasi (edit koordinat atau hapus)</div>
        </div>
    </div>
    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-mountain" style="color:#805ad5;"></i> Data Destinasi Terverifikasi</div>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table">
                <thead>
                    <tr>
                        <th>Nama Destinasi</th><th>Kategori</th><th>Pengelola</th>
                        <th>Latitude</th><th>Longitude</th><th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wisataApproved ?? [] as $wi)
                    <tr id="wisataMasterRow-{{ $wi->id }}">
                        <td class="bold">{{ $wi->nama_wisata }}</td>
                        <td><span class="wm-badge" style="background:rgba(128,90,213,0.1);color:#805ad5;border:1px solid rgba(128,90,213,0.3);">{{ $wi->kategori }}</span></td>
                        <td style="color: var(--text-muted);">{{ $wi->nama_pengelola }}</td>
                        <td><input type="number" step="any" id="wlat-{{ $wi->id }}" value="{{ $wi->latitude }}" class="wm-input" style="width:110px;padding:4px;font-size:12px;"></td>
                        <td><input type="number" step="any" id="wlng-{{ $wi->id }}" value="{{ $wi->longitude }}" class="wm-input" style="width:110px;padding:4px;font-size:12px;"></td>
                        <td style="text-align:center;">
                            <button class="wm-btn orange sm" onclick="updateWisataLokasi(this, {{ $wi->id }})">
                                <i class="fas fa-save"></i>
                            </button>
                            <button class="wm-btn danger sm" onclick="deletePariwisata(this, {{ $wi->id }}, '{{ addslashes($wi->nama_wisata) }}')" style="margin-left:6px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center" style="padding: 20px; color: #888;">Belum ada destinasi yang disetujui.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== SECTION: DATA WISATAWAN ==================== --}}
<div id="sectionWisatawan" class="admin-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Data Wisatawan</div>
            <div class="wm-page-subtitle">Kelola akun pengguna wisatawan yang terdaftar di platform</div>
        </div>
    </div>
    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-users"></i> Daftar Pengguna Wisatawan</div>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table">
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>Gol. Darah</th><th>Status Akun</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($users as $usr)
                    <tr>
                        <td class="bold">{{ $usr->name }}</td>
                        <td>{{ $usr->email }}</td>
                        <td>{{ $usr->gol_darah ?? '-' }}</td>
                        <td id="statusUser-{{ $usr->id }}">
                            @if($usr->is_active)
                                <span class="wm-badge green">Aktif</span>
                            @else
                                <span class="wm-badge danger">Diblokir</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <button class="wm-btn {{ $usr->is_active ? 'danger' : 'success' }} sm" onclick="toggleUserStatus(this, {{ $usr->id }})">
                                @if($usr->is_active)
                                    <i class="fas fa-ban"></i> Blokir
                                @else
                                    <i class="fas fa-check"></i> Aktifkan
                                @endif
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== MODAL: DETAIL FASKES (untuk approve) ==================== --}}
<div class="modal fade" id="modalDetailFaskes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background:#ffffff;border:none;border-radius:16px;box-shadow:0 25px 60px rgba(43,54,116,0.2);overflow:hidden;">
            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#2b3674 0%,#1e2c63 100%);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-clinic-medical" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <div style="color:#fff;font-weight:700;font-size:16px;">Detail Pengajuan Mitra Faskes</div>
                        <div style="color:rgba(255,255,255,0.6);font-size:12px;">Periksa semua data sebelum memberikan keputusan</div>
                    </div>
                </div>
                <button type="button" data-dismiss="modal" style="background:rgba(255,255,255,0.15);border:none;color:#fff;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:20px;line-height:1;display:flex;align-items:center;justify-content:center;">&times;</button>
            </div>
            {{-- Body --}}
            <div class="modal-body" style="padding:24px;background:#ffffff;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Nama Faskes</label>
                        <div id="detailFaskesNama" style="font-weight:700;font-size:15px;color:#2b3674;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Kategori Faskes</label>
                        <div id="detailFaskesKategori" style="font-weight:600;color:#2b3674;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Penanggung Jawab</label>
                        <div id="detailFaskesPJ" style="font-weight:600;color:#2b3674;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Kontak Utama</label>
                        <div id="detailFaskesKontak" style="font-weight:600;color:#2b3674;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Alamat Lengkap</label>
                        <div id="detailFaskesAlamat" style="color:#707eae;background:#f4f7fe;padding:10px 14px;border-radius:8px;margin-top:4px;font-size:13px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Koordinat (Lat, Lng)</label>
                        <div id="detailFaskesKoordinat" style="font-family:monospace;color:#ff7a00;font-weight:700;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Dukungan BPJS</label>
                        <div id="detailFaskesBPJS" style="margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Layanan / Pengumuman</label>
                        <div id="detailFaskesLayanan" style="color:#707eae;background:#f4f7fe;padding:10px 14px;border-radius:8px;margin-top:4px;font-size:13px;">-</div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Dokumen Izin / SK</label>
                        <div id="detailFaskesDokumenWrap" style="margin-top:6px;"></div>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div style="background:#f8f9fa;border-top:1px solid #e2e8f0;padding:16px 24px;display:flex;align-items:center;gap:10px;">
                <input type="hidden" id="faskesModalId" value="">
                <button type="button" data-dismiss="modal" style="background:#fff;color:#707eae;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 18px;font-size:13px;font-weight:600;cursor:pointer;margin-right:auto;">✕ Tutup</button>
                <button type="button" id="btnRejectFaskesModal" onclick="rejectFaskesFromModal()" style="background:#fff;color:#e53e3e;border:1.5px solid #f8a5a5;border-radius:8px;padding:9px 18px;font-size:13px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-times-circle"></i> Tolak Pendaftaran
                </button>
                <button type="button" id="btnApproveFaskesModal" onclick="approveFaskesFromModal()" style="background:linear-gradient(135deg,#38a169,#2f855a);color:#fff;border:none;border-radius:8px;padding:9px 22px;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(56,161,105,0.3);">
                    <i class="fas fa-check-circle"></i> Setujui Mitra
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL: DETAIL PARIWISATA (untuk approve) ==================== --}}
<div class="modal fade" id="modalDetailWisata" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background:#ffffff;border:none;border-radius:16px;box-shadow:0 25px 60px rgba(43,54,116,0.2);overflow:hidden;">
            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#553c9a 0%,#3d2a7a 100%);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-mountain" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <div style="color:#fff;font-weight:700;font-size:16px;">Detail Pendaftaran Pariwisata</div>
                        <div style="color:rgba(255,255,255,0.6);font-size:12px;">Tinjau identitas dan dokumen sebelum approve</div>
                    </div>
                </div>
                <button type="button" data-dismiss="modal" style="background:rgba(255,255,255,0.15);border:none;color:#fff;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:20px;line-height:1;display:flex;align-items:center;justify-content:center;">&times;</button>
            </div>
            {{-- Body --}}
            <div class="modal-body" style="padding:24px;background:#ffffff;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Nama Destinasi</label>
                        <div id="detailWisataNama" style="font-weight:700;font-size:15px;color:#2b3674;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Kategori Wisata</label>
                        <div id="detailWisataKategori" style="font-weight:600;color:#553c9a;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Nama Pengelola / PJ</label>
                        <div id="detailWisataPengelola" style="font-weight:600;color:#2b3674;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Kontak (Email & Telp)</label>
                        <div id="detailWisataKontak" style="font-weight:600;color:#2b3674;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Alamat Lengkap</label>
                        <div id="detailWisataAlamat" style="color:#707eae;background:#f4f7fe;padding:10px 14px;border-radius:8px;margin-top:4px;font-size:13px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Koordinat (Lat, Lng)</label>
                        <div id="detailWisataKoordinat" style="font-family:monospace;color:#ff7a00;font-weight:700;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Harga Tiket Masuk</label>
                        <div id="detailWisataTiket" style="font-weight:700;color:#38a169;margin-top:4px;">-</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Deskripsi & Daya Tarik</label>
                        <div id="detailWisataDeskripsi" style="color:#707eae;background:#f4f7fe;padding:10px 14px;border-radius:8px;margin-top:4px;font-size:13px;">-</div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;letter-spacing:.5px;">Foto / Dokumen Pendukung</label>
                        <div id="detailWisataDokumenWrap" style="margin-top:6px;"></div>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div style="background:#f8f9fa;border-top:1px solid #e2e8f0;padding:16px 24px;display:flex;align-items:center;gap:10px;">
                <input type="hidden" id="wisataModalId" value="">
                <button type="button" data-dismiss="modal" style="background:#fff;color:#707eae;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 18px;font-size:13px;font-weight:600;cursor:pointer;margin-right:auto;">✕ Tutup</button>
                <button type="button" id="btnRejectWisataModal" onclick="rejectFromModal()" style="background:#fff;color:#e53e3e;border:1.5px solid #f8a5a5;border-radius:8px;padding:9px 18px;font-size:13px;font-weight:600;cursor:pointer;">
                    <i class="fas fa-times-circle"></i> Tolak
                </button>
                <button type="button" id="btnApproveWisataModal" onclick="approveFromModal()" style="background:linear-gradient(135deg,#553c9a,#3d2a7a);color:#fff;border:none;border-radius:8px;padding:9px 22px;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(85,60,154,0.3);">
                    <i class="fas fa-check-circle"></i> Setujui Pendaftaran
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // 1. NAVIGASI SIDEBAR (SPA)
    // =========================================================
    var sections = {
        'navDashboard':      'sectionDashboard',
        'navValidasi':       'sectionValidasi',
        'navLaporan':        'sectionLaporan',
        'navDataWisatawan':  'sectionWisatawan',
        'navDataFaskes':     'sectionFaskes',
        'navDataPariwisata': 'sectionPariwisata',
    };

    document.querySelectorAll('.wm-nav-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            var targetId = this.id;
            if (!sections[targetId]) return;
            e.preventDefault();
            document.querySelectorAll('.wm-nav-link').forEach(function(l) { l.classList.remove('active'); });
            this.classList.add('active');
            document.querySelectorAll('.admin-section').forEach(function(sec) { sec.style.display = 'none'; });
            var target = document.getElementById(sections[targetId]);
            if (target) target.style.display = 'block';
        });
    });

    // Tombol "Lihat Semua" di widget pendaftar
    var navValidasiLink = document.getElementById('navValidasiLink');
    if (navValidasiLink) {
        navValidasiLink.addEventListener('click', function(e) {
            e.preventDefault();
            var navValidasi = document.getElementById('navValidasi');
            if (navValidasi) navValidasi.click();
        });
    }

    // =========================================================
    // 2. HELPER FUNCTIONS
    // =========================================================
    window.filterTable = function(inputId, tableId, colClass) {
        var query = document.getElementById(inputId).value.toLowerCase();
        document.querySelectorAll('#' + tableId + ' tbody tr').forEach(function(row) {
            var cell = row.querySelector('.' + colClass);
            if (cell) row.style.display = cell.textContent.toLowerCase().includes(query) ? '' : 'none';
        });
    };

    window.updatePendingCount = function() {
        var cardEl = document.getElementById('cardPendingCount');
        var navEl  = document.getElementById('navPendingCount');
        var cur = parseInt(cardEl ? cardEl.textContent : 0) || 0;
        if (cur > 0) { if (cardEl) cardEl.textContent = cur - 1; if (navEl) navEl.textContent = cur - 1; }
    };

    // =========================================================
    // 3. MODAL DETAIL FASKES
    // =========================================================
    window.showDetailFaskes = function(data) {
        document.getElementById('faskesModalId').value = data.id;
        document.getElementById('detailFaskesNama').textContent     = data.faskes ? data.faskes.nama_faskes : '-';
        document.getElementById('detailFaskesKategori').textContent = data.faskes ? data.faskes.jenis_faskes : '-';
        document.getElementById('detailFaskesPJ').textContent       = data.nama_penanggung_jawab;
        document.getElementById('detailFaskesKontak').textContent   = data.email + ' / ' + (data.no_telp || '-');
        document.getElementById('detailFaskesAlamat').textContent   = data.faskes ? data.faskes.alamat : '-';
        document.getElementById('detailFaskesKoordinat').textContent = data.faskes ? (data.faskes.latitude + ', ' + data.faskes.longitude) : '-';

        var bpjs = '-';
        if (data.faskes) {
            bpjs = data.faskes.dukungan_bpjs
                ? '<span style="background:rgba(56,161,105,0.1);color:#38a169;border:1px solid rgba(56,161,105,0.3);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">✅ Menerima BPJS</span>'
                : '<span style="background:rgba(229,62,62,0.1);color:#e53e3e;border:1px solid rgba(229,62,62,0.3);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">❌ Non-BPJS</span>';
        }
        document.getElementById('detailFaskesBPJS').innerHTML    = bpjs;
        document.getElementById('detailFaskesLayanan').textContent = (data.faskes && data.faskes.pengumuman) ? data.faskes.pengumuman : 'Tidak ada informasi layanan.';

        var docWrap = document.getElementById('detailFaskesDokumenWrap');
        if (data.catatan_admin && !data.catatan_admin.includes(' ')) {
            docWrap.innerHTML = '<a href="/storage/' + data.catatan_admin + '" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#ebf8ff;color:#3182ce;border:1px solid #bee3f8;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;"><i class="fas fa-external-link-alt"></i> Buka Dokumen</a>';
        } else {
            docWrap.innerHTML = '<span style="color:#a3aed1;font-size:13px;"><i class="fas fa-times-circle"></i> Dokumen tidak diunggah</span>';
        }

        document.getElementById('btnApproveFaskesModal').style.display = data.is_verified ? 'none' : '';
        document.getElementById('btnRejectFaskesModal').style.display  = data.is_verified ? 'none' : '';
        $('#modalDetailFaskes').modal('show');
    };

    window.approveFaskesFromModal = function() {
        var id = document.getElementById('faskesModalId').value;
        if (!id || !confirm('Setujui mitra faskes ini?\n\nData akan otomatis muncul di peta publik.')) return;
        var btn = document.getElementById('btnApproveFaskesModal');
        btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        fetch('/admin/mitra/' + id + '/approve', {
            method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(function(r) { return r.json(); }).then(function(data) {
            $('#modalDetailFaskes').modal('hide');
            var row = document.getElementById('mitraRow-' + id);
            if (row) { row.style.opacity='0.4'; row.querySelector('td:last-child').innerHTML='<span class="wm-badge green"><i class="fas fa-check-circle"></i> Disetujui</span>'; }
            updatePendingCount(); showToast(data.message || 'Mitra berhasil disetujui!');
        }).finally(function() { btn.disabled=false; btn.innerHTML='<i class="fas fa-check-circle"></i> Setujui Mitra'; });
    };

    window.rejectFaskesFromModal = function() {
        var id = document.getElementById('faskesModalId').value;
        if (!id || !confirm('TOLAK mitra faskes ini?\n\nAksi ini tidak dapat dibatalkan.')) return;
        var btn = document.getElementById('btnRejectFaskesModal');
        btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        fetch('/admin/mitra/' + id + '/reject', {
            method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ alasan: 'Ditolak setelah peninjauan detail.' })
        }).then(function(r) { return r.json(); }).then(function(data) {
            $('#modalDetailFaskes').modal('hide');
            var row = document.getElementById('mitraRow-' + id);
            if (row) { row.style.transition='all .4s'; row.style.opacity='0'; setTimeout(function(){row.remove();},400); }
            updatePendingCount(); showToast(data.message || 'Mitra ditolak.');
        }).finally(function() { btn.disabled=false; btn.innerHTML='<i class="fas fa-times-circle"></i> Tolak Pendaftaran'; });
    };

    // =========================================================
    // 4. MODAL DETAIL PARIWISATA
    // =========================================================
    window.showDetailWisata = function(data) {
        document.getElementById('wisataModalId').value = data.id;
        document.getElementById('detailWisataNama').textContent      = data.nama_wisata;
        document.getElementById('detailWisataKategori').textContent  = data.kategori;
        document.getElementById('detailWisataPengelola').textContent = data.nama_pengelola;
        document.getElementById('detailWisataKontak').textContent    = (data.email_kontak || '-') + ' / ' + (data.no_telp || '-');
        document.getElementById('detailWisataAlamat').textContent    = data.alamat || '-';
        document.getElementById('detailWisataKoordinat').textContent = (data.latitude||'-') + ', ' + (data.longitude||'-');
        var tiket = data.harga_tiket ? 'Rp ' + parseInt(data.harga_tiket).toLocaleString('id-ID') : 'Gratis / Tidak ada info';
        document.getElementById('detailWisataTiket').textContent    = tiket;
        document.getElementById('detailWisataDeskripsi').textContent = data.deskripsi || 'Tidak ada deskripsi.';

        var docWrap = document.getElementById('detailWisataDokumenWrap');
        if (data.foto_path) {
            if (/\.(jpe?g|png|gif|webp)$/i.test(data.foto_path)) {
                docWrap.innerHTML = '<img src="/storage/' + data.foto_path + '" style="max-height:200px;max-width:100%;border-radius:10px;border:1px solid #e2e8f0;display:block;margin-top:8px;"><a href="/storage/' + data.foto_path + '" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#ebf8ff;color:#3182ce;border:1px solid #bee3f8;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;margin-top:8px;"><i class="fas fa-external-link-alt"></i> Buka Penuh</a>';
            } else {
                docWrap.innerHTML = '<a href="/storage/' + data.foto_path + '" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#ebf8ff;color:#3182ce;border:1px solid #bee3f8;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;"><i class="fas fa-external-link-alt"></i> Buka Dokumen</a>';
            }
        } else {
            docWrap.innerHTML = '<span style="color:#a3aed1;font-size:13px;"><i class="fas fa-times-circle"></i> Tidak ada foto/dokumen</span>';
        }
        var isPending = (data.status_review === 'menunggu');
        document.getElementById('btnApproveWisataModal').style.display = isPending ? '' : 'none';
        document.getElementById('btnRejectWisataModal').style.display  = isPending ? '' : 'none';
        $('#modalDetailWisata').modal('show');
    };

    window.approveFromModal = function() {
        var id = document.getElementById('wisataModalId').value;
        if (!id || !confirm('Setujui destinasi pariwisata ini?')) return;
        var btn = document.getElementById('btnApproveWisataModal');
        btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Memproses...';
        fetch('/admin/pariwisata/' + id + '/approve', {
            method:'POST', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}
        }).then(function(r){return r.json();}).then(function(data){
            $('#modalDetailWisata').modal('hide');
            var row = document.getElementById('wisataRow-' + id);
            if (row) { row.style.opacity='0.4'; row.querySelector('td:last-child').innerHTML='<span class="wm-badge green"><i class="fas fa-check-circle"></i> Disetujui</span>'; }
            updatePendingCount(); showToast(data.message || 'Destinasi disetujui!');
        }).finally(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-check-circle"></i> Setujui Pendaftaran';});
    };

    window.rejectFromModal = function() {
        var id = document.getElementById('wisataModalId').value;
        if (!id || !confirm('TOLAK destinasi pariwisata ini?')) return;
        var btn = document.getElementById('btnRejectWisataModal');
        btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
        fetch('/admin/pariwisata/' + id + '/reject', {
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify({catatan:'Ditolak setelah peninjauan detail.'})
        }).then(function(r){return r.json();}).then(function(data){
            $('#modalDetailWisata').modal('hide');
            var row = document.getElementById('wisataRow-' + id);
            if (row) { row.style.transition='all .4s'; row.style.opacity='0'; setTimeout(function(){row.remove();},400); }
            updatePendingCount(); showToast(data.message || 'Destinasi ditolak.');
        }).finally(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-times-circle"></i> Tolak';});
    };

    // =========================================================
    // 5. SIMPAN DATA FASKES (koordinat + layanan + BPJS + pesan)
    // =========================================================
    window.saveFaskesData = function(btn, id) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        btn.disabled = true;
        var lat   = (document.getElementById('lat-'    + id)||{}).value || '';
        var lng   = (document.getElementById('lng-'    + id)||{}).value || '';
        var pesan = (document.getElementById('pesan-'  + id)||{}).value || '';
        var bpjs  = (document.getElementById('bpjs-'   + id)||{}).value || '0';
        var layanan = (document.getElementById('layanan-'+ id)||{}).value || '';
        fetch('/admin/faskes/' + id + '/update-lokasi', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ latitude: lat, longitude: lng, pesan_admin: pesan, dukungan_bpjs: bpjs, pengumuman: layanan })
        }).then(function(r){return r.json();}).then(function(data){
            showToast(data.message || 'Data faskes berhasil disimpan!');
        }).catch(function(){
            showToast('Gagal menyimpan. Cek koneksi.');
        }).finally(function(){
            btn.disabled=false; btn.innerHTML='<i class="fas fa-save"></i> Simpan Semua';
        });
    };

    // Backward compat alias
    window.updateFaskesLocation = window.saveFaskesData;

    // =========================================================
    // 6. TOGGLE STATUS OPERASIONAL FASKES
    // =========================================================
    window.toggleStatusFaskes = function(btn, id) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        fetch('/admin/faskes/' + id + '/toggle-status', {
            method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(function(r){ return r.json(); }).then(function(data){
            var badge = document.getElementById('statusBadge-' + id);
            if (data.status_operasional === 'open') {
                badge.className = 'wm-badge green';
                badge.innerHTML = '<i class="fas fa-circle" style="font-size:8px;"></i> Buka';
            } else {
                badge.className = 'wm-badge danger';
                badge.innerHTML = '<i class="fas fa-circle" style="font-size:8px;"></i> Tutup';
            }
            btn.innerHTML = '<i class="fas fa-power-off"></i>';
            showToast(data.message || 'Status operasional diperbarui!');
        }).catch(function(){ btn.innerHTML='<i class="fas fa-power-off"></i>'; showToast('Gagal mengubah status.'); });
    };

    // =========================================================
    // 7. UPDATE KOORDINAT PARIWISATA
    // =========================================================
    window.updateWisataLokasi = function(btn, id) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        var lat = (document.getElementById('wlat-' + id)||{}).value || '';
        var lng = (document.getElementById('wlng-' + id)||{}).value || '';
        fetch('/admin/pariwisata/' + id + '/update-lokasi', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ latitude: lat, longitude: lng })
        }).then(function(r){return r.json();}).then(function(data){
            btn.innerHTML = '<i class="fas fa-save"></i>'; showToast(data.message || 'Koordinat pariwisata diperbarui!');
        }).catch(function(){ btn.innerHTML='<i class="fas fa-save"></i>'; showToast('Gagal menyimpan.'); });
    };

    // =========================================================
    // 8. HAPUS PARIWISATA
    // =========================================================
    window.deletePariwisata = function(btn, id, nama) {
        if (!confirm('Yakin HAPUS permanen destinasi "' + nama + '"?\n\nData ini tidak bisa dikembalikan.')) return;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; btn.disabled = true;
        fetch('/admin/pariwisata/' + id, {
            method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(function(r){return r.json();}).then(function(data){
            var row = btn.closest('tr');
            row.style.transition='all .4s'; row.style.opacity='0'; setTimeout(function(){row.remove();},400);
            showToast(data.message || 'Destinasi dihapus!');
        }).catch(function(){ btn.disabled=false; btn.innerHTML='<i class="fas fa-trash"></i>'; showToast('Gagal menghapus.'); });
    };

    // =========================================================
    // 9. RESOLVE TIKET LAPORAN
    // =========================================================
    window.resolveTicket = function(btn, id, tiket) {
        if (!confirm('Tandai tiket ' + tiket + ' sebagai selesai?')) return;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        fetch('/admin/laporan/' + id + '/resolve', {
            method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(function(r){return r.json();}).then(function(data){
            var row = btn.closest('tr');
            row.querySelector('td:nth-child(5)').innerHTML = '<span class="wm-badge green"><i class="fas fa-check-circle"></i> Resolved</span>';
            btn.outerHTML = '<button class="wm-btn ghost sm" disabled style="opacity:0.5;"><i class="fas fa-lock"></i> Closed</button>';
            showToast(data.message || 'Tiket diselesaikan!');
        });
    };

    // =========================================================
    // 10. TOGGLE STATUS AKUN WISATAWAN
    // =========================================================
    window.toggleUserStatus = function(btn, id) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        fetch('/admin/user/' + id + '/toggle-status', {
            method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        }).then(function(r){return r.json();}).then(function(data){
            var statusCell = document.getElementById('statusUser-' + id);
            if (data.is_active) {
                statusCell.innerHTML = '<span class="wm-badge green">Aktif</span>';
                btn.className = 'wm-btn danger sm'; btn.innerHTML = '<i class="fas fa-ban"></i> Blokir';
            } else {
                statusCell.innerHTML = '<span class="wm-badge danger">Diblokir</span>';
                btn.className = 'wm-btn success sm'; btn.innerHTML = '<i class="fas fa-check"></i> Aktifkan';
            }
            showToast(data.message || 'Status akun diperbarui!');
        });
    };

}); // END DOMContentLoaded
</script>
@endpush
