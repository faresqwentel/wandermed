{{-- ============================================================
     Dashboard Administrator – WanderMed
     Layout: theme/dashboard_layout.blade.php (Kustom, no SB Admin)
     ============================================================ --}}
@extends('theme.dashboard_layout')

@section('page_title', 'Admin Dashboard')
@section('badge_role', 'Administrator')
@section('user_name', 'Faresya Brilyan')
@section('user_role', 'Super Administrator')
@section('user_initial', 'F')
@section('topbar_title', 'Pusat <span>Kendali Utama</span>')

@section('sidebar_nav')
    <div class="wm-nav-label">Overview</div>
    <a href="/dashboard/admin" class="wm-nav-link active">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <div class="wm-nav-label">Validasi & Moderasi</div>
    <a href="#" class="wm-nav-link" id="navValidasi">
        <i class="fas fa-user-check"></i> Validasi Mitra
        <span class="badge-pill-side">3</span>
    </a>
    <a href="#" class="wm-nav-link" id="navLaporan">
        <i class="fas fa-exclamation-triangle"></i> Laporan Masalah
        <span class="badge-pill-side">2</span>
    </a>

    <div class="wm-nav-label">Data Master</div>
    <a href="#" class="wm-nav-link">
        <i class="fas fa-clinic-medical"></i> Fasilitas Kesehatan
    </a>
    <a href="#" class="wm-nav-link">
        <i class="fas fa-mountain"></i> Destinasi Pariwisata
    </a>
    <a href="#" class="wm-nav-link">
        <i class="fas fa-users"></i> Data Wisatawan
    </a>

    <div class="wm-nav-label">Sistem</div>
    <a href="/peta-faskes" class="wm-nav-link">
        <i class="fas fa-map-marked-alt"></i> Lihat Peta Publik
    </a>
    <a href="/login" class="wm-nav-link">
        <i class="fas fa-sign-out-alt"></i> Keluar
    </a>
@endsection

@section('content')

    <!-- Page Header -->
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
                <div class="wm-stat-value">4,520</div>
                <div class="wm-stat-label">Total Wisatawan</div>
            </div>
        </div>
        <div class="wm-stat-card green">
            <div class="wm-stat-icon"><i class="fas fa-clinic-medical"></i></div>
            <div>
                <div class="wm-stat-value">185</div>
                <div class="wm-stat-label">Mitra Faskes</div>
            </div>
        </div>
        <div class="wm-stat-card teal">
            <div class="wm-stat-icon"><i class="fas fa-mountain"></i></div>
            <div>
                <div class="wm-stat-value">48</div>
                <div class="wm-stat-label">Mitra Pariwisata</div>
            </div>
        </div>
        <div class="wm-stat-card orange">
            <div class="wm-stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <div class="wm-stat-value">3</div>
                <div class="wm-stat-label">Menunggu Validasi</div>
            </div>
        </div>
    </div>

    <!-- Tabel Validasi Mitra -->
    <div class="wm-card" id="sectionValidasi">
        <div class="wm-card-header">
            <div class="wm-card-title">
                <i class="fas fa-user-check"></i> Antrean Validasi Mitra Baru
                <span class="wm-badge orange" style="margin-left: 8px; font-size: 10px;">3 Pending</span>
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
                        <th>Nama Institusi</th>
                        <th>Jenis Mitra</th>
                        <th>Email Daftar</th>
                        <th>Dokumen</th>
                        <th>Tgl Daftar</th>
                        <th class="text-center">Aksi Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="bold col-nama">Klinik Kasih Ibu</td>
                        <td><span class="wm-badge green"><i class="fas fa-clinic-medical"></i> Faskes</span></td>
                        <td style="color: var(--text-muted);">admin@kasihibu.com</td>
                        <td>
                            <button class="wm-btn info sm"><i class="fas fa-file-pdf"></i> Izin.pdf</button>
                        </td>
                        <td style="color: var(--text-muted);">10 Apr 2026</td>
                        <td style="text-align: center;">
                            <button class="wm-btn success sm" onclick="approveRow(this, 'Klinik Kasih Ibu')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="wm-btn danger sm" onclick="rejectRow(this, 'Klinik Kasih Ibu')" style="margin-left: 6px;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="bold col-nama">Tangkuban Perahu Park</td>
                        <td><span class="wm-badge teal"><i class="fas fa-mountain"></i> Pariwisata</span></td>
                        <td style="color: var(--text-muted);">contact@tangkuban.co.id</td>
                        <td>
                            <button class="wm-btn info sm"><i class="fas fa-file-pdf"></i> Legal.pdf</button>
                        </td>
                        <td style="color: var(--text-muted);">09 Apr 2026</td>
                        <td style="text-align: center;">
                            <button class="wm-btn success sm" onclick="approveRow(this, 'Tangkuban Perahu Park')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="wm-btn danger sm" onclick="rejectRow(this, 'Tangkuban Perahu Park')" style="margin-left: 6px;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="bold col-nama">Apotek Subang Sehat</td>
                        <td><span class="wm-badge green"><i class="fas fa-clinic-medical"></i> Faskes</span></td>
                        <td style="color: var(--text-muted);">subangsehat@gmail.com</td>
                        <td>
                            <button class="wm-btn info sm"><i class="fas fa-file-pdf"></i> SIPA.pdf</button>
                        </td>
                        <td style="color: var(--text-muted);">08 Apr 2026</td>
                        <td style="text-align: center;">
                            <button class="wm-btn success sm" onclick="approveRow(this, 'Apotek Subang Sehat')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="wm-btn danger sm" onclick="rejectRow(this, 'Apotek Subang Sehat')" style="margin-left: 6px;">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </td>
                    </tr>
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
                    <tr>
                        <td class="bold" style="color: var(--orange);">#TKT-0091</td>
                        <td>Ahmad Rizky</td>
                        <td style="max-width: 280px; color: var(--text-muted); font-size: 12px; font-style: italic;">
                            "Titik peta masuk ke gang sempit, tidak sesuai lokasi asli klinik."
                        </td>
                        <td class="bold">Klinik Pratama Sehat</td>
                        <td><span class="wm-badge yellow"><i class="fas fa-clock"></i> Pending</span></td>
                        <td style="text-align: center;">
                            <button class="wm-btn orange sm" onclick="resolveTicket(this, '#TKT-0091')">
                                <i class="fas fa-tools"></i> Tinjau & Selesaikan
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="bold" style="color: var(--orange);">#TKT-0088</td>
                        <td>Sarah Nurul</td>
                        <td style="max-width: 280px; color: var(--text-muted); font-size: 12px; font-style: italic;">
                            "Di app tertulis buka 24 jam, kenyataannya tutup jam 9 malam."
                        </td>
                        <td class="bold">Apotek Budi Luhur</td>
                        <td><span class="wm-badge green"><i class="fas fa-check-circle"></i> Resolved</span></td>
                        <td style="text-align: center;">
                            <button class="wm-btn ghost sm" disabled style="opacity:0.5; cursor:not-allowed;">
                                <i class="fas fa-lock"></i> Closed
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
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

    // Approve row: ganti action button menjadi badge "Disetujui"
    function approveRow(btn, name) {
        const row = btn.closest('tr');
        const actionCell = row.querySelector('td:last-child');
        actionCell.innerHTML = '<span class="wm-badge green"><i class="fas fa-check-circle"></i> Disetujui</span>';
        row.style.opacity = '0.6';
        showToast(`✅ Mitra "${name}" berhasil disetujui!`);
    }

    // Reject row: hapus baris dengan animasi
    function rejectRow(btn, name) {
        const row = btn.closest('tr');
        row.style.transition = 'all 0.4s ease';
        row.style.opacity = '0';
        row.style.transform = 'translateX(30px)';
        setTimeout(() => row.remove(), 400);
        showToast(`❌ Mitra "${name}" telah ditolak.`);
    }

    // Resolve ticket
    function resolveTicket(btn, ticketId) {
        const row = btn.closest('tr');
        const statusCell = row.querySelector('td:nth-child(5)');
        statusCell.innerHTML = '<span class="wm-badge green"><i class="fas fa-check-circle"></i> Resolved</span>';
        btn.outerHTML = '<button class="wm-btn ghost sm" disabled style="opacity:0.5;cursor:not-allowed;"><i class="fas fa-lock"></i> Closed</button>';
        showToast(`Tiket ${ticketId} berhasil diselesaikan!`);
    }

    // Scroll to sections dari sidebar
    document.getElementById('navValidasi').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('sectionValidasi').scrollIntoView({ behavior: 'smooth' });
    });
    document.getElementById('navLaporan').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('sectionLaporan').scrollIntoView({ behavior: 'smooth' });
    });
</script>
@endpush
