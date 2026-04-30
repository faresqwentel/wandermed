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
                        $recents = collect($recentFaskes)->merge(collect($recentWisata))->sortByDesc('tanggal')->take(5);
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
            <div class="wm-page-subtitle">Kelola seluruh mitra faskes — klik Detail untuk edit lokasi, BPJS, dan pesan admin</div>
        </div>
    </div>
    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-clinic-medical" style="color:#ff7a00;"></i> Daftar Mitra Faskes
                <span class="wm-badge orange" style="margin-left:8px;font-size:10px;">{{ $faskesList->count() }} Faskes</span>
            </div>
            <div style="position:relative;flex:0 0 200px;">
                <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px;"></i>
                <input type="text" id="filterFaskesInput" class="wm-input" style="padding-left:32px;height:34px;font-size:12px;" placeholder="Cari faskes..." onkeyup="filterTable('filterFaskesInput','faskesTable','col-nama-faskes')">
            </div>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table" id="faskesTable">
                <thead>
                    <tr>
                        <th>Nama Faskes</th>
                        <th>Jenis</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>BPJS</th>
                        <th class="text-center" width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faskesList as $faskesItem)
                    <tr id="faskesTableRow-{{ $faskesItem->id }}">
                        <td class="bold col-nama-faskes">
                            <i class="fas fa-hospital-alt" style="color:#ff7a00;margin-right:6px;"></i>
                            {{ $faskesItem->nama_faskes }}
                        </td>
                        <td>
                            <span class="wm-badge" style="background:rgba(56,161,105,0.1);color:#38a169;border:1px solid rgba(56,161,105,0.3);font-size:10px;">
                                {{ $faskesItem->jenis_faskes }}
                            </span>
                        </td>
                        <td style="color:var(--text-muted);font-size:12px;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $faskesItem->alamat ?? '-' }}
                        </td>
                        <td id="statusBadge-{{ $faskesItem->id }}">
                            @if($faskesItem->status_operasional == 'open')
                                <span class="wm-badge green"><i class="fas fa-circle" style="font-size:8px;"></i> Buka</span>
                            @else
                                <span class="wm-badge danger"><i class="fas fa-circle" style="font-size:8px;"></i> Tutup</span>
                            @endif
                        </td>
                        <td>
                            @if($faskesItem->dukungan_bpjs)
                                <span class="wm-badge info" style="font-size:10px;">✅ BPJS</span>
                            @else
                                <span class="wm-badge" style="font-size:10px;background:rgba(255,255,255,0.06);color:var(--text-muted);border:1px solid var(--border);">❌ Non-BPJS</span>
                            @endif
                        </td>
                        <td style="text-align:center;white-space:nowrap;">
                            <div style="display:inline-flex;align-items:center;gap:6px;">
                                <button class="wm-btn info sm" onclick='openEditFaskes(@json($faskesItem))' title="Edit Detail">
                                    <i class="fas fa-edit"></i> Detail
                                </button>
                                <button class="wm-btn danger sm" onclick='deleteFaskes(this, {{ $faskesItem->id ?? 0 }}, @json($faskesItem->nama_faskes ?? ""))' title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:20px;color:#888;">Belum ada mitra faskes yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== SECTION: DATA MASTER PARIWISATA ==================== --}}
<div id="sectionPariwisata" class="admin-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Destinasi Pariwisata</div>
            <div class="wm-page-subtitle">Kelola seluruh destinasi wisata terverifikasi — klik Detail untuk ubah peta lokasi & deskripsi</div>
        </div>
    </div>
    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-mountain" style="color:#805ad5;"></i> Data Destinasi Terverifikasi</div>
            <div style="position:relative;flex:0 0 200px;">
                <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px;"></i>
                <input type="text" id="filterWisataInput" class="wm-input" style="padding-left:32px;height:34px;font-size:12px;" placeholder="Cari destinasi..." onkeyup="filterTable('filterWisataInput','wisataTable','col-nama-wisata')">
            </div>
        </div>
        <div class="wm-table-wrap">
            <table class="wm-table" id="wisataTable">
                <thead>
                    <tr>
                        <th>Nama Destinasi</th>
                        <th>Kategori</th>
                        <th>Alamat</th>
                        <th>Pengelola</th>
                        <th class="text-center" width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wisataApproved ?? [] as $wi)
                    <tr id="wisataMasterRow-{{ $wi->type }}-{{ $wi->id }}">
                        <td class="bold col-nama-wisata">
                            @if($wi->type == 'mitra')
                                <i class="fas fa-check-circle" style="color:#38a169; margin-right:4px;" title="Mitra Resmi"></i>
                            @endif
                            {{ $wi->nama_wisata }}
                        </td>
                        <td>
                            @if($wi->type == 'mitra')
                                <span class="wm-badge" style="background:rgba(56,161,105,0.1);color:#38a169;border:1px solid rgba(56,161,105,0.3);">Mitra {{ $wi->kategori }}</span>
                            @else
                                <span class="wm-badge" style="background:rgba(128,90,213,0.1);color:#805ad5;border:1px solid rgba(128,90,213,0.3);">Publik {{ $wi->kategori }}</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted);font-size:12px;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $wi->alamat ?? '-' }}
                        </td>
                        <td style="color:var(--text-muted);">{{ $wi->nama_pengelola }}</td>
                        <td style="text-align:center;white-space:nowrap;">
                            <div style="display:inline-flex;align-items:center;gap:6px;">
                                <button class="wm-btn info sm" onclick='openEditPariwisata(@json($wi))' title="Edit Detail">
                                    <i class="fas fa-edit"></i> Detail
                                </button>
                                <button class="wm-btn danger sm" onclick='deletePariwisata(this, {{ $wi->id ?? 0 }}, @json($wi->nama_wisata ?? ""), "{{ $wi->type }}")' title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center" style="padding: 20px; color: #888;">Belum ada destinasi yang disetujui.</td></tr>
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

{{-- ==================== MODAL: EDIT DATA FASKES ==================== --}}
<div class="modal fade" id="modalEditFaskes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background:#ffffff;border:none;border-radius:16px;box-shadow:0 25px 60px rgba(43,54,116,0.2);overflow:hidden;">
            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#ff7a00 0%,#e53e3e 100%);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-clinic-medical" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <div style="color:#fff;font-weight:700;font-size:16px;">Edit Data Faskes</div>
                        <div style="color:rgba(255,255,255,0.8);font-size:12px;" id="editFaskesNamaLabel">-</div>
                    </div>
                </div>
                <button type="button" data-dismiss="modal" style="background:rgba(255,255,255,0.15);border:none;color:#fff;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:20px;line-height:1;">&times;</button>
            </div>
            {{-- Body --}}
            <div class="modal-body" style="padding:24px;background:#ffffff;">
                <div class="row">
                    {{-- Kolom Kiri: Koordinat + BPJS --}}
                    <div class="col-md-6">
                        <div style="margin-bottom:16px;">
                            <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;display:block;margin-bottom:6px;">
                                <i class="fas fa-map-pin" style="color:#ff7a00;"></i> Koordinat Lokasi (Lat, Lng)
                            </label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="number" step="any" id="editFaskesLat" class="wm-input" style="padding:6px 10px;font-size:12px;" placeholder="Latitude">
                                <input type="number" step="any" id="editFaskesLng" class="wm-input" style="padding:6px 10px;font-size:12px;" placeholder="Longitude">
                            </div>
                        </div>
                        <div style="margin-bottom:16px;">
                            <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;display:block;margin-bottom:6px;">
                                <i class="fas fa-id-card" style="color:#3182ce;"></i> Dukungan BPJS
                            </label>
                            <select id="editFaskesBPJS" class="wm-input" style="font-size:12px;padding:6px 10px;">
                                <option value="1">✅ Menerima BPJS</option>
                                <option value="0">❌ Non-BPJS</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;display:block;margin-bottom:6px;">
                                <i class="fas fa-stethoscope" style="color:#805ad5;"></i> Layanan / Fasilitas Utama
                            </label>
                            <textarea id="editFaskesPengumuman" class="wm-input" rows="3" style="font-size:12px;padding:8px 10px;resize:vertical;"></textarea>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Pesan Admin --}}
                    <div class="col-md-6">
                        <div style="background:#fffbeb;border:1px solid #f6e05e;border-radius:10px;padding:14px;margin-bottom:16px;height:100%;">
                            <label style="font-size:11px;font-weight:700;color:#d69e2e;text-transform:uppercase;display:block;margin-bottom:8px;">
                                <i class="fas fa-comment-dots"></i> Pesan untuk Mitra Faskes
                            </label>
                            <textarea id="editFaskesPesanAdmin" rows="5"
                                style="width:100%;border:1px solid #f6e05e;border-radius:8px;padding:10px;font-size:12px;background:#fffff0;resize:vertical;"
                                placeholder="Ketik catatan/instruksi yang akan tampil di dashboard faskes ini..."></textarea>
                            <div style="font-size:10px;color:#d69e2e;margin-top:4px;"><i class="fas fa-info-circle"></i> Hanya terbaca oleh admin dan mitra tersebut.</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div style="background:#f8f9fa;border-top:1px solid #e2e8f0;padding:16px 24px;display:flex;justify-content:flex-end;gap:10px;">
                <input type="hidden" id="editFaskesId">
                <button type="button" data-dismiss="modal" style="background:#fff;color:#707eae;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 18px;font-size:13px;font-weight:600;">Batal</button>
                <button type="button" id="btnSaveFaskesEdit" onclick="saveFaskesData()" style="background:#ff7a00;color:#fff;border:none;border-radius:8px;padding:9px 22px;font-size:13px;font-weight:700;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL: EDIT DATA PARIWISATA ==================== --}}
<div class="modal fade" id="modalEditPariwisata" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background:#ffffff;border:none;border-radius:16px;box-shadow:0 25px 60px rgba(43,54,116,0.2);overflow:hidden;">
            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#805ad5 0%,#553c9a 100%);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-mountain" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <div style="color:#fff;font-weight:700;font-size:16px;">Edit Data Pariwisata</div>
                        <div style="color:rgba(255,255,255,0.8);font-size:12px;" id="editWisataNamaLabel">-</div>
                    </div>
                </div>
                <button type="button" data-dismiss="modal" style="background:rgba(255,255,255,0.15);border:none;color:#fff;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:20px;line-height:1;">&times;</button>
            </div>
            {{-- Body --}}
            <div class="modal-body" style="padding:24px;background:#ffffff;">
                <div class="row">
                    {{-- Kolom Kiri: Alamat & Koordinat --}}
                    <div class="col-md-6">
                        <div style="margin-bottom:16px;">
                            <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;display:block;margin-bottom:6px;">
                                <i class="fas fa-map-marker-alt"></i> Alamat Lengkap
                            </label>
                            <textarea id="editWisataAlamat" class="wm-input" rows="2" style="font-size:12px;padding:8px 10px;resize:vertical;"></textarea>
                        </div>
                        <div style="margin-bottom:16px;">
                            <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;display:block;margin-bottom:6px;">
                                <i class="fas fa-map-pin" style="color:#ff7a00;"></i> Koordinat Lokasi (Lat, Lng)
                            </label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="number" step="any" id="editWisataLat" class="wm-input" style="padding:6px 10px;font-size:12px;" placeholder="Latitude">
                                <input type="number" step="any" id="editWisataLng" class="wm-input" style="padding:6px 10px;font-size:12px;" placeholder="Longitude">
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Harga & Deskripsi --}}
                    <div class="col-md-6">
                        <div style="margin-bottom:16px;">
                            <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;display:block;margin-bottom:6px;">
                                <i class="fas fa-ticket-alt" style="color:#38a169;"></i> Harga Tiket Masuk (Rp)
                            </label>
                            <input type="number" id="editWisataTiket" class="wm-input" style="font-size:12px;padding:6px 10px;" placeholder="Contoh: 15000">
                        </div>
                        <div>
                            <label style="font-size:11px;font-weight:600;color:#a3aed1;text-transform:uppercase;display:block;margin-bottom:6px;">
                                <i class="fas fa-align-left" style="color:#3182ce;"></i> Deskripsi & Daya Tarik
                            </label>
                            <textarea id="editWisataDeskripsi" rows="4" style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px;font-size:12px;resize:vertical;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Footer --}}
            <div style="background:#f8f9fa;border-top:1px solid #e2e8f0;padding:16px 24px;display:flex;justify-content:flex-end;gap:10px;">
                <input type="hidden" id="editWisataId">
                <input type="hidden" id="editWisataType">
                <button type="button" data-dismiss="modal" style="background:#fff;color:#707eae;border:1.5px solid #e2e8f0;border-radius:8px;padding:9px 18px;font-size:13px;font-weight:600;">Batal</button>
                <button type="button" id="btnSaveWisataEdit" onclick="updateWisataLokasi()" style="background:#805ad5;color:#fff;border:none;border-radius:8px;padding:9px 22px;font-size:13px;font-weight:700;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/dashboard-admin.js') }}"></script>
@endpush
