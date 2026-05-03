

<nav class="navbar navbar-expand-lg navbar-desktop-premier fixed-top w-100" id="mainNavbar">
    <div class="container px-4">

        <a class="navbar-brand d-flex align-items-center text-white font-weight-bold scroll-link" href="#page-top" style="letter-spacing: 1px; font-size: 24px;">
            <i class="fas fa-heartbeat text-hnb-orange mr-2" style="font-size: 28px;"></i>
            <span>WanderMed</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarNav">
            <i class="fas fa-bars text-white-50"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto align-items-center">
                <li class="nav-item"><a class="nav-link nav-pill-link scroll-link" href="/#page-top">Beranda</a></li>
                <li class="nav-item"><a class="nav-link nav-pill-link scroll-link" href="/#tentang">Tentang</a></li>
                <li class="nav-item"><a class="nav-link nav-pill-link scroll-link" href="/#panduan">Panduan</a></li>
                <li class="nav-item"><a class="nav-link nav-pill-link scroll-link" href="/#mitra">Mitra</a></li>
                <li class="nav-item">
                    <a class="nav-link nav-pill-link" href="https://wa.me/6287775733922" target="_blank">Kontak CS</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item">
                    <div class="nav-action-box shadow-sm">
                        @if(session('auth_user'))
                            <a href="{{ url('/login') }}" class="btn btn-sm text-white font-weight-bold px-3 py-2 border-0" style="font-size: 13px;">
                                Enter <i class="fas fa-door-open ml-1 text-hnb-orange"></i>
                            </a>
                        @else
                            <a href="/login" class="btn btn-sm text-white font-weight-bold px-3 py-2 border-0" style="font-size: 13px;">
                                Login <i class="fas fa-sign-in-alt ml-1 text-hnb-orange"></i>
                            </a>
                        @endif
                        <div class="v-separator"></div>
                        <a href="#" class="btn btn-sm text-danger font-weight-bold px-3 py-2 border-0" data-toggle="modal" data-target="#reportModal" style="font-size: 13px;">
                            <i class="fas fa-flag mr-1"></i> Lapor
                        </a>
                    </div>
                </li>
                <li class="nav-item ml-3">
                    <a href="#" id="themeToggle" class="btn btn-sm text-white d-flex align-items-center justify-content-center shadow-sm" style="background: rgba(128,128,128,0.2); border-radius: 50%; width: 40px; height: 40px;">
                        <i class="fas fa-sun" id="themeIcon" style="font-size: 18px;"></i>
                    </a>
                </li>
            </ul>

        </div>
    </div>
</nav>

<div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">
        <div class="modal-content radius-hnb border-0 shadow-lg">
            <div class="modal-header bg-hnb-navy text-white radius-hnb" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-info-circle text-hnb-orange mr-2"></i> Cara Penggunaan</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light text-hnb-navy">
                <ol class="pl-3 mb-0 font-weight-bold" style="font-size: 14px; line-height: 1.8;">
                    <li class="mb-2">Cari fasilitas kesehatan lewat kolom pencarian.</li>
                    <li class="mb-2">Gunakan filter BPJS jika diperlukan.</li>
                    <li class="mb-2">Klik tombol "Lapor" jika menemukan data tidak akurat.</li>
                </ol>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-hnb-orange radius-hnb w-100 font-weight-bold" data-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 450px;">
        <div class="modal-content radius-hnb border-0 shadow-lg">
            <div class="modal-header bg-danger text-white radius-hnb" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <h6 class="modal-title font-weight-bold mb-0"><i class="fas fa-exclamation-circle mr-2"></i> Laporkan Masalah</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formLaporMasalah" action="{{ route('lapor.masalah') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light text-left">
                    <div class="form-group mb-3">
                        <label class="text-hnb-navy font-weight-bold small">Kategori Masalah</label>
                        <select name="subjek" class="form-control radius-hnb shadow-sm border-0" required>
                            <option value="" disabled selected>Pilih Kategori...</option>
                            <option value="Data Salah">Informasi Faskes Salah</option>
                            <option value="Lokasi">Titik Lokasi Tidak Akurat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-hnb-navy font-weight-bold small">Deskripsi Masalah</label>
                        <textarea name="deskripsi" class="form-control radius-hnb shadow-sm border-0" rows="4" placeholder="Ceritakan detail masalah..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light pb-4">
                    <button type="submit" id="btnSubmitLaporan" class="btn btn-danger radius-hnb w-100 font-weight-bold shadow">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formLapor = document.getElementById('formLaporMasalah');
    if (formLapor) {
        formLapor.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitLaporan');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            btn.disabled = true;

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    $('#reportModal').modal('hide');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terkirim!',
                            text: data.message,
                            confirmButtonColor: '#38a169'
                        });
                    } else {
                        alert(data.message);
                    }
                    formLapor.reset();
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message || 'Gagal mengirim laporan.',
                            confirmButtonColor: '#e53e3e'
                        });
                    } else {
                        alert(data.message || 'Gagal mengirim laporan.');
                    }
                }
            })
            .catch(err => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Sistem',
                        text: 'Gagal terhubung ke server. Silakan periksa koneksi internet Anda.',
                        confirmButtonColor: '#e53e3e'
                    });
                } else {
                    alert('Gagal terhubung ke server.');
                }
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});
</script>
