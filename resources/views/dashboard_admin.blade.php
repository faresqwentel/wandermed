{{-- ============================================================
     Dashboard Administrator – WanderMed
     Layout: theme/dashboard_layout.blade.php (Kustom, no SB Admin)
     ============================================================ --}}
@extends('theme.dashboard_layout')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('page_title', 'Admin Dashboard')
@section('badge_role', 'Administrator')
@section('user_name', 'Administrator')
@section('user_role', 'Super Administrator')
@section('user_initial', 'A')
@section('topbar_title', 'Pusat <span>Kendali Utama</span>')

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

<!-- Wrapper Section: Dashboard Utama -->
<div id="sectionDashboard" class="admin-section">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Helicopter View Sistem</div>
            <div class="wm-page-subtitle">Pantau seluruh aktivitas platform WanderMed secara real-time</div>
        </div>
        <button class="wm-btn orange">
            <i class="fas fa-file-export"></i> Export Laporan
        </button>
    </div>

    <!-- Global Stats -->
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

    <!-- Tabel Validasi Mitra -->
    <div class="wm-card" id="sectionValidasi">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-user-check"></i> Antrean Validasi Mitra Baru
                <span class="wm-badge orange" style="margin-left: 8px; font-size: 10px;" id="tableBadgePending">{{ $pendingMitra }} Pending</span>
            </div>
            <!-- Search Filter -->
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
                        <th>Dokumen Izin</th>
                        <th>Tgl Daftar</th>
                        <th class="text-center">Aksi Validasi</th>
                    </tr>
                </thead>
                    {{-- Antrean Faskes --}}
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
                        <td>
                            <span class="wm-badge green"><i class="fas fa-clinic-medical"></i> Faskes</span>
                        </td>
                        <td style="color: var(--text-muted); font-size:12px;">
                            {{ $m->email }}<br>
                            <i class="fas fa-phone-alt mr-1"></i>{{ $m->no_telp ?? '-' }}
                        </td>
                        <td>
                            @if($m->catatan_admin && !str_contains($m->catatan_admin ?? '', ' '))
                                <a href="{{ Storage::url($m->catatan_admin) }}" target="_blank" class="wm-btn info sm">
                                    <i class="fas fa-file-alt"></i> Lihat Dokumen
                                </a>
                            @else
                                <span style="color: var(--text-muted); font-size:12px;"><i class="fas fa-minus"></i> Tidak ada</span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted); font-size:12px;">{{ $m->created_at->format('d M Y') }}</td>
                        <td style="text-align: center;">
                            <button class="wm-btn info sm" onclick='showDetailFaskes(@json($m))'>
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="wm-btn success sm" onclick="approveRow(this, {{ $m->id }}, '{{ $m->nama_penanggung_jawab }}')" style="margin-left: 6px;">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="wm-btn danger sm" onclick="rejectRow(this, {{ $m->id }}, '{{ $m->nama_penanggung_jawab }}')" style="margin-left: 6px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach

                    {{-- Antrean Pariwisata --}}
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
                        <td>
                            <span class="wm-badge teal"><i class="fas fa-mountain"></i> Pariwisata</span>
                        </td>
                        <td style="color: var(--text-muted); font-size:12px;">
                            {{ $w->email_kontak }}<br>
                            <i class="fas fa-phone-alt mr-1"></i>{{ $w->no_telp ?? '-' }}
                        </td>
                        <td>
                            @if($w->foto_path)
                                <a href="{{ Storage::url($w->foto_path) }}" target="_blank" class="wm-btn info sm">
                                    <i class="fas fa-file-alt"></i> Lihat Dokumen
                                </a>
                            @else
                                <span style="color: var(--text-muted); font-size:12px;"><i class="fas fa-minus"></i> Tidak ada</span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted); font-size:12px;">{{ $w->created_at->format('d M Y') }}</td>
                        <td style="text-align: center;">
                            <button class="wm-btn info sm" onclick='showDetailWisata(@json($w))'>
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="wm-btn success sm" onclick="approveRowWisata(this, {{ $w->id }}, '{{ $w->nama_wisata }}')" style="margin-left: 6px;">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="wm-btn danger sm" onclick="rejectRowWisata(this, {{ $w->id }}, '{{ $w->nama_wisata }}')" style="margin-left: 6px;">
                                <i class="fas fa-times"></i>
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

    <!-- Tabel Laporan Masalah -->
    <div class="wm-card" id="sectionLaporan">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-exclamation-triangle" style="color: #f6c23e;"></i>
                Laporan Masalah dari Wisatawan
            </div>
            <span style="font-size: 12px; color: var(--text-muted);">2 Belum Ditangani</span>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table" id="laporanTable">
                <thead>
                    <tr>
                        <th>ID Tiket</th>
                        <th>Pelapor</th>
                        <th>Isi Laporan</th>
                        <th>Terkait Mitra</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
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
                                <i class="fas fa-tools"></i> Tinjau & Selesaikan
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
                        <td colspan="6" class="text-center" style="padding: 20px; color: #888;">Belum ada laporan masalah terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div> <!-- Tag Penutup Section Dashboard Utama -->

    <!-- Tabel Data Master: Wisatawan -->
    <div class="wm-card mt-4 admin-section" id="sectionWisatawan" style="display: none;">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-users" style="color: var(--blue);"></i> Data Wisatawan (Pengguna)
            </div>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Gol. Darah</th>
                        <th>Status Akun</th>
                        <th class="text-center">Aksi</th>
                    </tr>
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

    <!-- Tabel Data Master: Faskes -->
    <div class="wm-card mt-4 admin-section" id="sectionFaskes" style="display: none;">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-clinic-medical" style="color: var(--green);"></i> Data Fasilitas Kesehatan
            </div>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table">
                <thead>
                    <tr>
                        <th>Nama Faskes</th>
                        <th>Kategori</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th class="text-center">Aksi (Koreksi)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faskesList as $faskesItem)
                    <tr>
                        <td class="bold">{{ $faskesItem->nama_faskes }}</td>
                        <td>{{ $faskesItem->jenis_faskes }}</td>
                        <td>
                            <input type="number" step="any" id="lat-{{ $faskesItem->id }}" value="{{ $faskesItem->latitude }}" class="wm-input" style="width: 110px; padding: 4px; font-size:12px;">
                        </td>
                        <td>
                            <input type="number" step="any" id="lng-{{ $faskesItem->id }}" value="{{ $faskesItem->longitude }}" class="wm-input" style="width: 110px; padding: 4px; font-size:12px;">
                        </td>
                        <td style="text-align: center;">
                            <button class="wm-btn blue sm" onclick="updateFaskesLocation(this, {{ $faskesItem->id }})">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div> <!-- End sectionFaskes -->

    <!-- Modal Detail Faskes -->
    <div class="modal fade" id="modalDetailFaskes" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                    <h5 class="modal-title font-weight-bold" style="color: var(--text-light);"><i class="fas fa-clinic-medical" style="color: var(--green);"></i> Detail Pengajuan Mitra Faskes</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.8; text-shadow: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Nama Faskes</label>
                            <div class="font-weight-bold" id="detailFaskesNama" style="color: var(--text-light); font-size: 15px;">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Kategori</label>
                            <div class="font-weight-bold" id="detailFaskesKategori" style="color: var(--text-light);">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Penanggung Jawab</label>
                            <div class="font-weight-bold" id="detailFaskesPJ" style="color: var(--text-light);">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Kontak Utama</label>
                            <div class="font-weight-bold" id="detailFaskesKontak" style="color: var(--text-light);">-</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="small text-muted mb-1 d-block">Alamat Lengkap</label>
                            <div id="detailFaskesAlamat" style="color: var(--text-color); background: rgba(255,255,255,0.05); padding: 10px; border-radius: 6px;">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Koordinat (Lat, Lng)</label>
                            <div id="detailFaskesKoordinat" style="font-family: monospace; color: var(--orange);">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Status Layanan BPJS</label>
                            <div id="detailFaskesBPJS">-</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="small text-muted mb-1 d-block">Layanan/Pengumuman Utama</label>
                            <div id="detailFaskesLayanan" style="color: var(--text-color); background: rgba(255,255,255,0.05); padding: 10px; border-radius: 6px;">-</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="small text-muted mb-2 d-block">Pratinjau Dokumen Izin / SK</label>
                            <div id="detailFaskesDokumenWrap"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 16px 20px;">
                    <button type="button" class="wm-btn" data-dismiss="modal" style="background: transparent; color: var(--text-muted); border: 1px solid var(--border-color);">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pariwisata -->
    <div class="modal fade" id="modalDetailWisata" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 20px;">
                    <h5 class="modal-title font-weight-bold" style="color: var(--text-light);"><i class="fas fa-mountain" style="color: var(--teal);"></i> Detail Pendaftaran Pariwisata</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity: 0.8; text-shadow: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Nama Destinasi</label>
                            <div class="font-weight-bold" id="detailWisataNama" style="color: var(--text-light); font-size: 15px;">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Kategori Wisata</label>
                            <div class="font-weight-bold" id="detailWisataKategori" style="color: var(--text-light);">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Nama Pengelola / PJ</label>
                            <div class="font-weight-bold" id="detailWisataPengelola" style="color: var(--text-light);">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Kontak (Email & Telp)</label>
                            <div class="font-weight-bold" id="detailWisataKontak" style="color: var(--text-light);">-</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="small text-muted mb-1 d-block">Alamat Lengkap</label>
                            <div id="detailWisataAlamat" style="color: var(--text-color); background: rgba(255,255,255,0.05); padding: 10px; border-radius: 6px;">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Koordinat (Lat, Lng)</label>
                            <div id="detailWisataKoordinat" style="font-family: monospace; color: var(--orange);">-</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted mb-1 d-block">Harga Tiket</label>
                            <div id="detailWisataTiket" class="font-weight-bold" style="color: var(--green);">-</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="small text-muted mb-1 d-block">Deskripsi & Daya Tarik</label>
                            <div id="detailWisataDeskripsi" style="color: var(--text-color); background: rgba(255,255,255,0.05); padding: 10px; border-radius: 6px;">-</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="small text-muted mb-2 d-block">Pratinjau Foto Bukti / Dokumen</label>
                            <div id="detailWisataDokumenWrap"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 16px 20px;">
                    <button type="button" class="wm-btn" data-dismiss="modal" style="background: transparent; color: var(--text-muted); border: 1px solid var(--border-color);">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Tab Navigation Logic
    const sections = {
        'navDashboard': 'sectionDashboard',
        'navValidasi': 'sectionDashboard', // Scrolls to validasi
        'navLaporan': 'sectionDashboard', // Scrolls to laporan
        'navDataWisatawan': 'sectionWisatawan',
        'navDataFaskes': 'sectionFaskes',
    };
    
    document.querySelectorAll('.wm-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.id;
            if(!sections[targetId]) return; // For logout or peta links

            e.preventDefault();
            
            // Remove active classes
            document.querySelectorAll('.wm-nav-link').forEach(l => l.classList.remove('active'));
            
            // Add active class to main menu or clicked menu
            if(targetId === 'navValidasi' || targetId === 'navLaporan') {
                document.getElementById('navDashboard').classList.add('active');
            } else {
                this.classList.add('active');
            }

            // Hide all sections
            document.querySelectorAll('.admin-section').forEach(sec => sec.style.display = 'none');

            // Show target section
            const targetSection = document.getElementById(sections[targetId]);
            targetSection.style.display = 'block';

            // Special Scroll behaviors for dashboard sub-sections
            if (targetId === 'navValidasi') {
                document.getElementById('sectionValidasi').scrollIntoView({ behavior: 'smooth' });
            } else if (targetId === 'navLaporan') {
                document.getElementById('sectionLaporan').scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Filter tabel validasi secara real-time
    function filterTable(inputId, tableId, colClass) {
        const query = document.getElementById(inputId).value.toLowerCase();
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        rows.forEach(row => {
            const cell = row.querySelector(`.${colClass}`);
            if (cell) {
                row.style.display = cell.textContent.toLowerCase().includes(query) ? '' : 'none';
            }
        });
    }

    function updatePendingCount() {
        // Kurangi count via DOM (sekadar UI update, next reload akan akurat)
        const curCount = parseInt(document.getElementById('cardPendingCount').textContent) || 0;
        if(curCount > 0) {
            document.getElementById('cardPendingCount').textContent = curCount - 1;
            document.getElementById('navPendingCount').textContent = curCount - 1;
        }
    }

    // Approve row Faskes
    function approveRow(btn, id, name) {
        if(!confirm(`Yakin ingin menyetujui "${name}"?`)) return;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        fetch(`/admin/mitra/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(data => {
            const row = btn.closest('tr');
            const actionCell = row.querySelector('td:last-child');
            actionCell.innerHTML = '<span class="wm-badge green"><i class="fas fa-check-circle"></i> Disetujui</span>';
            row.style.opacity = '0.6';
            updatePendingCount();
            showToast(data.message);
        });
    }

    // Reject row Faskes
    function rejectRow(btn, id, name) {
        if(!confirm(`Yakin ingin mendepak/menolak "${name}" secara permanen?`)) return;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        fetch(`/admin/mitra/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ alasan: 'Dokumen ditolak manual.' })
        })
        .then(r => r.json())
        .then(data => {
            const row = btn.closest('tr');
            row.style.transform = 'scale(0.9)';
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
                updatePendingCount();
            }, 300);
            showToast(data.message);
        });
    }

    // Approve row Pariwisata
    function approveRowWisata(btn, id, name) {
        if(!confirm(`Yakin ingin menyetujui destinasi wisata "${name}"?`)) return;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        fetch(`/admin/pariwisata/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(data => {
            const row = btn.closest('tr');
            const actionCell = row.querySelector('td:last-child');
            actionCell.innerHTML = '<span class="wm-badge green"><i class="fas fa-check-circle"></i> Disetujui</span>';
            row.style.opacity = '0.6';
            updatePendingCount();
            showToast(data.message);
        });
    }

    // Reject row Pariwisata
    function rejectRowWisata(btn, id, name) {
        if(!confirm(`Yakin ingin menolak destinasi "${name}"?`)) return;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        fetch(`/admin/pariwisata/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ catatan: 'Dokumen / syarat ditolak manual oleh Admin.' })
        })
        .then(r => r.json())
        .then(data => {
            const row = btn.closest('tr');
            row.style.transition = 'all 0.4s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(30px)';
            setTimeout(() => row.remove(), 400);
            updatePendingCount();
            showToast(data.message);
        });
    }

    // Resolve ticket
    function resolveTicket(btn, id, ticketText) {
        if(!confirm(`Tandai tiket ${ticketText} sudah diselesaikan?`)) return;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/admin/laporan/${id}/resolve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(data => {
            const row = btn.closest('tr');
            const statusCell = row.querySelector('td:nth-child(5)');
            statusCell.innerHTML = '<span class="wm-badge green"><i class="fas fa-check-circle"></i> Resolved</span>';
            btn.outerHTML = '<button class="wm-btn ghost sm" disabled style="opacity:0.5;cursor:not-allowed;"><i class="fas fa-lock"></i> Closed</button>';
            showToast(data.message);
        });
    }

    // Toggle user status
    function toggleUserStatus(btn, id) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/admin/user/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(data => {
            const statusCell = document.getElementById(`statusUser-${id}`);
            if (data.is_active) {
                statusCell.innerHTML = '<span class="wm-badge green">Aktif</span>';
                btn.className = 'wm-btn danger sm';
                btn.innerHTML = '<i class="fas fa-ban"></i> Blokir';
            } else {
                statusCell.innerHTML = '<span class="wm-badge danger">Diblokir</span>';
                btn.className = 'wm-btn success sm';
                btn.innerHTML = '<i class="fas fa-check"></i> Aktifkan';
            }
            showToast(data.message);
        });
    }

    // Update Faskes Location
    function updateFaskesLocation(btn, id) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        const lat = document.getElementById(`lat-${id}`).value;
        const lng = document.getElementById(`lng-${id}`).value;

        fetch(`/admin/faskes/${id}/update-lokasi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ latitude: lat, longitude: lng })
        })
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
            showToast(data.message);
        })
        .catch(e => {
            console.error(e);
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
            showToast('Gagal menyimpan koordinat.');
        });

        // Fungsi Menampilkan Detail Faskes
        window.showDetailFaskes = function(data) {
            document.getElementById('detailFaskesNama').textContent = data.faskes ? data.faskes.nama_faskes : '-';
            document.getElementById('detailFaskesKategori').textContent = data.faskes ? data.faskes.jenis_faskes : '-';
            document.getElementById('detailFaskesPJ').textContent = data.nama_penanggung_jawab;
            document.getElementById('detailFaskesKontak').textContent = data.email + ' / ' + (data.no_telp || '-');
            document.getElementById('detailFaskesAlamat').textContent = data.faskes ? data.faskes.alamat : '-';
            document.getElementById('detailFaskesKoordinat').textContent = data.faskes ? (data.faskes.latitude + ', ' + data.faskes.longitude) : '-';
            
            let bpjsStatus = '-';
            if (data.faskes) {
                bpjsStatus = data.faskes.dukungan_bpjs ? '<span class="wm-badge green">Menerima BPJS</span>' : '<span class="wm-badge danger">Umum (Non-BPJS)</span>';
            }
            document.getElementById('detailFaskesBPJS').innerHTML = bpjsStatus;
            document.getElementById('detailFaskesLayanan').textContent = (data.faskes && data.faskes.pengumuman) ? data.faskes.pengumuman : 'Tidak ada catatan layanan.';

            const docWrap = document.getElementById('detailFaskesDokumenWrap');
            if (data.catatan_admin && !data.catatan_admin.includes(' ')) {
                docWrap.innerHTML = `<a href="/storage/${data.catatan_admin}" target="_blank" class="wm-btn info" style="display:inline-block; margin-top:8px;"><i class="fas fa-external-link-alt"></i> Buka Dokumen</a>`;
            } else {
                docWrap.innerHTML = '<span style="color:var(--text-muted);"><i class="fas fa-times-circle"></i> Dokumen/Izin tidak diunggah</span>';
            }

            $('#modalDetailFaskes').modal('show');
        };

        // Fungsi Menampilkan Detail Pariwisata
        window.showDetailWisata = function(data) {
            document.getElementById('detailWisataNama').textContent = data.nama_wisata;
            document.getElementById('detailWisataKategori').textContent = data.kategori;
            document.getElementById('detailWisataPengelola').textContent = data.nama_pengelola;
            document.getElementById('detailWisataKontak').textContent = data.email_kontak + ' / ' + (data.no_telp || '-');
            document.getElementById('detailWisataAlamat').textContent = data.alamat;
            document.getElementById('detailWisataKoordinat').textContent = (data.latitude || '-') + ', ' + (data.longitude || '-');
            
            const tiket = data.harga_tiket ? `Rp ${parseInt(data.harga_tiket).toLocaleString('id-ID')}` : 'Gratis';
            document.getElementById('detailWisataTiket').textContent = tiket;
            document.getElementById('detailWisataDeskripsi').textContent = data.deskripsi || '-';

            const docWrap = document.getElementById('detailWisataDokumenWrap');
            if (data.foto_path) {
                // If it's an image, show a thumbnail. Otherwise, just a link.
                if (data.foto_path.match(/\.(jpeg|jpg|gif|png)$/i)) {
                    docWrap.innerHTML = `<img src="/storage/${data.foto_path}" style="max-height: 200px; max-width: 100%; border-radius: 8px; border: 1px solid var(--border-color); display:block; margin-top:10px;">
                                         <a href="/storage/${data.foto_path}" target="_blank" class="wm-btn info sm mt-2" style="display:inline-block;"><i class="fas fa-external-link-alt"></i> Buka Penuh</a>`;
                } else {
                    docWrap.innerHTML = `<a href="/storage/${data.foto_path}" target="_blank" class="wm-btn info" style="display:inline-block; margin-top:8px;"><i class="fas fa-external-link-alt"></i> Buka Dokumen</a>`;
                }
            } else {
                docWrap.innerHTML = '<span style="color:var(--text-muted);"><i class="fas fa-times-circle"></i> Tidak ada dokumen diunggah</span>';
            }

            $('#modalDetailWisata').modal('show');
        };
    }
</script>
@endpush
