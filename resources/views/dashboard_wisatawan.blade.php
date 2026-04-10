{{-- ============================================================
     Dashboard Wisatawan – WanderMed
     Layout: theme/dashboard_layout.blade.php (Kustom, no SB Admin)
     ============================================================ --}}
@extends('theme.dashboard_layout')

@section('page_title', 'Dashboard Saya')
@section('badge_role', 'Wisatawan')
@section('user_name', 'John Doe')
@section('user_role', 'Wisatawan Umum')
@section('user_initial', 'J')
@section('topbar_title', 'Dashboard <span>Wisatawan</span>')

@section('sidebar_nav')
    <div class="wm-nav-label">Menu Utama</div>
    <a href="/dashboard/wisatawan" class="wm-nav-link active">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a href="/peta-faskes" class="wm-nav-link">
        <i class="fas fa-map-marked-alt"></i> Peta Faskes
    </a>

    <div class="wm-nav-label">Akun Saya</div>
    <a href="#" class="wm-nav-link" id="navProfileLink">
        <i class="fas fa-user-circle"></i> Profil & Pengaturan
    </a>
    <a href="#" class="wm-nav-link" id="navMedisLink">
        <i class="fas fa-notes-medical"></i> Data Medis Darurat
    </a>

    <div class="wm-nav-label">Navigasi</div>
    <a href="/" class="wm-nav-link">
        <i class="fas fa-home"></i> Halaman Utama
    </a>
    <a href="/login" class="wm-nav-link">
        <i class="fas fa-sign-out-alt"></i> Keluar
    </a>
@endsection

@section('content')

    <!-- Page Header -->
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Riwayat & Profil Saya</div>
            <div class="wm-page-subtitle">Kelola catatan kunjungan dan data medis darurat Anda</div>
        </div>
        <button class="wm-btn orange" onclick="document.getElementById('navMedisLink').click()">
            <i class="fas fa-shield-alt"></i> Data Medis
        </button>
    </div>

    <!-- Stat Summary Row -->
    <div class="wm-stat-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="wm-stat-card green">
            <div class="wm-stat-icon"><i class="fas fa-clinic-medical"></i></div>
            <div>
                <div class="wm-stat-value">3</div>
                <div class="wm-stat-label">Faskes Dikunjungi</div>
            </div>
        </div>
        <div class="wm-stat-card yellow">
            <div class="wm-stat-icon"><i class="fas fa-star"></i></div>
            <div>
                <div class="wm-stat-value">2</div>
                <div class="wm-stat-label">Sangat Direkomendasikan</div>
            </div>
        </div>
        <div class="wm-stat-card orange">
            <div class="wm-stat-icon"><i class="fas fa-sticky-note"></i></div>
            <div>
                <div class="wm-stat-value">3</div>
                <div class="wm-stat-label">Catatan Tersimpan</div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 22px; align-items: start;">

        <!-- KIRI: Tabel Riwayat Kunjungan -->
        <div class="wm-card">
            <div class="wm-card-header">
                <div class="wm-card-title">
                    <i class="fas fa-history"></i> Riwayat Kunjungan Faskes
                </div>
            </div>
            <div class="wm-card-body" style="padding: 0;">
                <div class="wm-table-wrap">
                    <table class="wm-table" id="historyTable">
                        <thead>
                            <tr>
                                <th>Nama Faskes</th>
                                <th>Tanggal</th>
                                <th>Label</th>
                                <th width="38%">Catatan Pribadi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-label="green">
                                <td class="bold">RSUD Subang</td>
                                <td>15 Okt 2026</td>
                                <td>
                                    <span class="wm-badge green">
                                        <i class="fas fa-star"></i> Sangat Direk.
                                    </span>
                                </td>
                                <td>
                                    <!-- Mode View -->
                                    <div class="note-view" onclick="startEditNote(this)">
                                        <span class="note-text">Pelayanan IGD cepat, dokter ramah.</span>
                                        <i class="fas fa-pencil-alt note-edit-icon"></i>
                                    </div>
                                    <!-- Mode Edit -->
                                    <div class="note-edit-active">
                                        <input type="text" value="Pelayanan IGD cepat, dokter ramah.">
                                        <button class="wm-btn success sm" onclick="saveNote(this)"><i class="fas fa-check"></i></button>
                                        <button class="wm-btn ghost sm" onclick="cancelNote(this)"><i class="fas fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-label="yellow">
                                <td class="bold">Klinik Pratama Sehat</td>
                                <td>22 Nov 2026</td>
                                <td>
                                    <span class="wm-badge yellow">
                                        <i class="fas fa-check"></i> Standar
                                    </span>
                                </td>
                                <td>
                                    <div class="note-view" onclick="startEditNote(this)">
                                        <span class="note-text">Buka 24 jam, parkiran sempit.</span>
                                        <i class="fas fa-pencil-alt note-edit-icon"></i>
                                    </div>
                                    <div class="note-edit-active">
                                        <input type="text" value="Buka 24 jam, parkiran sempit.">
                                        <button class="wm-btn success sm" onclick="saveNote(this)"><i class="fas fa-check"></i></button>
                                        <button class="wm-btn ghost sm" onclick="cancelNote(this)"><i class="fas fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-label="green">
                                <td class="bold">Apotek Kimia Farma</td>
                                <td>01 Des 2026</td>
                                <td>
                                    <span class="wm-badge green">
                                        <i class="fas fa-star"></i> Sangat Direk.
                                    </span>
                                </td>
                                <td>
                                    <div class="note-view" onclick="startEditNote(this)">
                                        <span class="note-text">Obat lengkap, apoteker sigap.</span>
                                        <i class="fas fa-pencil-alt note-edit-icon"></i>
                                    </div>
                                    <div class="note-edit-active">
                                        <input type="text" value="Obat lengkap, apoteker sigap.">
                                        <button class="wm-btn success sm" onclick="saveNote(this)"><i class="fas fa-check"></i></button>
                                        <button class="wm-btn ghost sm" onclick="cancelNote(this)"><i class="fas fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- KANAN: Profil + Data Medis -->
        <div>
            <!-- Pengaturan Akun -->
            <div class="wm-card" style="margin-bottom: 18px;" id="sectionProfil">
                <div class="wm-card-header">
                    <div class="wm-card-title"><i class="fas fa-user-cog"></i> Pengaturan Akun</div>
                </div>
                <div class="wm-card-body">
                    <div class="wm-form-group">
                        <label class="wm-label">Nama Lengkap</label>
                        <input type="text" class="wm-input" value="John Doe Wisatawan">
                    </div>
                    <div class="wm-form-group">
                        <label class="wm-label">Email</label>
                        <input type="email" class="wm-input" value="johndoe@example.com">
                    </div>
                    <div class="wm-form-group">
                        <label class="wm-label">Password Baru</label>
                        <input type="password" class="wm-input" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <button class="wm-btn orange" style="width:100%;" onclick="showToast('Profil berhasil diperbarui!')">
                        <i class="fas fa-save"></i> Simpan Profil
                    </button>
                </div>
            </div>

            <!-- Data Medis -->
            <div class="wm-card" id="sectionMedis" style="border-left: 4px solid #e74a3b;">
                <div class="wm-card-header" style="background: rgba(231, 74, 59, 0.08);">
                    <div class="wm-card-title" style="color: #e74a3b;">
                        <i class="fas fa-heartbeat"></i> Data Medis Darurat
                    </div>
                </div>
                <div class="wm-card-body">
                    <div class="wm-form-group">
                        <label class="wm-label" style="color: #e74a3b80;">Golongan Darah</label>
                        <select class="wm-select" style="background: rgba(231,74,59,0.05); border-color: rgba(231,74,59,0.25);">
                            <option value="A" selected>A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                    <div class="wm-form-group">
                        <label class="wm-label" style="color: #e74a3b80;">Alergi (Obat/Makanan)</label>
                        <textarea class="wm-textarea" style="background: rgba(231,74,59,0.05); border-color: rgba(231,74,59,0.25);">Alergi obat Amoxicillin</textarea>
                    </div>
                    <button class="wm-btn danger" style="width:100%;" onclick="showToast('Data medis diperbarui!')">
                        <i class="fas fa-shield-alt"></i> Update Data Medis
                    </button>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    // Highlight baris tabel berdasarkan label
    document.querySelectorAll('#historyTable tbody tr').forEach(row => {
        const label = row.getAttribute('data-label');
        if (label === 'green') row.style.borderLeft = '4px solid #1cc88a';
        if (label === 'yellow') row.style.borderLeft = '4px solid #f6c23e';
    });

    // Mulai edit catatan inline
    function startEditNote(viewEl) {
        const td = viewEl.closest('td');
        const editEl = td.querySelector('.note-edit-active');
        const input = editEl.querySelector('input');
        input.value = viewEl.querySelector('.note-text').textContent;
        viewEl.style.display = 'none';
        editEl.classList.add('show');
        input.focus();
    }

    // Simpan catatan
    function saveNote(btn) {
        const td = btn.closest('td');
        const editEl = btn.closest('.note-edit-active');
        const viewEl = td.querySelector('.note-view');
        const input = editEl.querySelector('input');
        viewEl.querySelector('.note-text').textContent = input.value;
        editEl.classList.remove('show');
        viewEl.style.display = '';
        showToast('Catatan berhasil diperbarui!');
    }

    // Batal edit
    function cancelNote(btn) {
        const td = btn.closest('td');
        td.querySelector('.note-edit-active').classList.remove('show');
        td.querySelector('.note-view').style.display = '';
    }

    // Quick nav ke profil / medis
    document.getElementById('navProfileLink').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('sectionProfil').scrollIntoView({ behavior: 'smooth' });
    });
    document.getElementById('navMedisLink').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('sectionMedis').scrollIntoView({ behavior: 'smooth' });
    });
</script>
@endpush
