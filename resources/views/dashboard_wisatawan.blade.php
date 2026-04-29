{{-- ============================================================
     Dashboard Wisatawan – WanderMed
     Layout: theme/dashboard_layout.blade.php (Kustom, no SB Admin)
     ============================================================ --}}
@extends('theme.dashboard_layout')

@section('page_title', 'Dashboard Saya')
@section('badge_role', 'Wisatawan')
@section('user_name', $user->name)
@section('user_role', 'Wisatawan Umum')
@section('user_initial', substr($user->name, 0, 1))
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
    <a href="/logout" class="wm-nav-link">
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
                <div class="wm-stat-value">{{ $totalKunjungan }}</div>
                <div class="wm-stat-label">Faskes Dikunjungi</div>
            </div>
        </div>
        <div class="wm-stat-card yellow">
            <div class="wm-stat-icon"><i class="fas fa-star"></i></div>
            <div>
                <div class="wm-stat-value">{{ $rekomendasiCount }}</div>
                <div class="wm-stat-label">Sangat Direkomendasikan</div>
            </div>
        </div>
        <div class="wm-stat-card orange">
            <div class="wm-stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="wm-stat-value">{{ $kunjunganBulan }}</div>
                <div class="wm-stat-label">Kunjungan Bulan Ini</div>
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
                            @forelse($riwayats as $r)
                            <tr data-label="{{ $r->label_warna }}">
                                <td class="bold">{{ $r->faskes ? $r->faskes->nama_faskes : 'Faskes Dihapus' }}</td>
                                <td>{{ $r->tanggal_kunjungan->format('d M Y') }}</td>
                                <td>
                                    <span class="wm-badge {{ $r->label_warna }}">
                                        @if($r->label_warna == 'green') <i class="fas fa-star"></i>
                                        @elseif($r->label_warna == 'yellow') <i class="fas fa-check"></i>
                                        @else <i class="fas fa-times"></i> @endif
                                        {{ $r->labelTeks }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Mode View -->
                                    <div class="note-view" onclick="startEditNote(this)">
                                        <span class="note-text">{{ $r->catatan_pribadi ?? 'Belum ada catatan...' }}</span>
                                        <i class="fas fa-pencil-alt note-edit-icon"></i>
                                    </div>
                                    <!-- Mode Edit -->
                                    <div class="note-edit-active">
                                        <input type="text" value="{{ $r->catatan_pribadi }}" data-id="{{ $r->id }}">
                                        <button class="wm-btn success sm" onclick="saveNote(this)"><i class="fas fa-check"></i></button>
                                        <button class="wm-btn ghost sm" onclick="cancelNote(this)"><i class="fas fa-times"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center" style="padding: 20px; color: #888;">Belum ada riwayat kunjungan.</td>
                            </tr>
                            @endforelse
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
                    <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                        @csrf
                        <div class="wm-form-group">
                            <label class="wm-label">Nama Lengkap</label>
                            <input type="text" name="name" class="wm-input" value="{{ $user->name }}" required>
                        </div>
                        <div class="wm-form-group">
                            <label class="wm-label">Email</label>
                            <input type="email" class="wm-input" value="{{ $user->email }}" disabled style="background:#f1f3f5;">
                        </div>
                        <div class="wm-form-group">
                            <label class="wm-label">Password Baru</label>
                            <input type="password" name="password" class="wm-input" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <button type="submit" class="wm-btn orange" style="width:100%;">
                            <i class="fas fa-save"></i> Simpan Profil
                        </button>
                    </form>
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
                    <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <div class="wm-form-group">
                            <label class="wm-label" style="color: #e74a3b80;">Golongan Darah</label>
                            <select name="gol_darah" class="wm-select" style="background: rgba(231,74,59,0.05); border-color: rgba(231,74,59,0.25);">
                                <option value="">Belum Memilih</option>
                                <option value="A" {{ $user->gol_darah == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ $user->gol_darah == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ $user->gol_darah == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ $user->gol_darah == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>
                        <div class="wm-form-group">
                            <label class="wm-label" style="color: #e74a3b80;">Alergi (Obat/Makanan)</label>
                            <textarea name="riwayat_alergi" class="wm-textarea" style="background: rgba(231,74,59,0.05); border-color: rgba(231,74,59,0.25);">{{ $user->riwayat_alergi }}</textarea>
                        </div>
                        <div class="wm-form-group">
                            <label class="wm-label" style="color: #e74a3b80;">Kontak Darurat</label>
                            <input type="text" name="kontak_darurat" class="wm-input" style="background: rgba(231,74,59,0.05); border-color: rgba(231,74,59,0.25);" value="{{ $user->kontak_darurat }}">
                        </div>
                        <button type="submit" class="wm-btn danger" style="width:100%;">
                            <i class="fas fa-shield-alt"></i> Update Data Medis
                        </button>
                    </form>
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

    // Simpan catatan (Fetch API)
    function saveNote(btn) {
        const td = btn.closest('td');
        const editEl = btn.closest('.note-edit-active');
        const viewEl = td.querySelector('.note-view');
        const input = editEl.querySelector('input');
        
        const noteId = input.getAttribute('data-id');
        const newText = input.value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Tampilkan loading state jika mau
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/wisatawan/catatan/${noteId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ catatan_pribadi: newText })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                viewEl.querySelector('.note-text').textContent = newText || 'Belum ada catatan...';
                editEl.classList.remove('show');
                viewEl.style.display = '';
                showToast(data.message);
                setTimeout(() => location.reload(), 1500);
            }
            btn.innerHTML = '<i class="fas fa-check"></i>';
        })
        .catch(err => {
            console.error(err);
            btn.innerHTML = '<i class="fas fa-check"></i>';
            showToast('Terjadi kesalahan saat menyimpan.');
        });
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
