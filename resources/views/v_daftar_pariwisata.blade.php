@extends('theme.wisatawan')

@section('content')
    @include('theme.navbar')

    <div class="bg-hnb-navy page-registration">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7 animate-fade-up">

                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold text-white teks-judul" id="formTitle">Profil Singkat Pariwisata</h3>
                        <p class="text-white-50 small teks-subjudul" id="formSubtitle">Langkah 1 dari 4</p>

                        <div class="progress mt-3 radius-hnb shadow-sm" style="height: 6px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-hnb-orange transition-all" id="formProgress" role="progressbar" style="width: 25%;"></div>
                        </div>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <form id="wizardForm" action="#" method="POST">

                                <div id="step1" class="step-content">
                                    <div class="row">
                                        <div class="col-md-7 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Nama Wisata</label>
                                            <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: Pantai Kuta..." required>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Kategori</label>
                                            <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                                                <option value="" disabled selected>Pilih Kategori...</option>
                                                <option value="Alam">Wisata Alam</option>
                                                <option value="Budaya">Wisata Budaya</option>
                                                <option value="Buatan">Wisata Buatan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Deskripsi Singkat</label>
                                        <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="4" placeholder="Ceritakan keunikan tempat wisata ini..." required></textarea>
                                    </div>
                                </div>

                                <div id="step2" class="step-content d-none">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Alamat Lengkap</label>
                                        <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="2" placeholder="Jl. Raya Kuta No. 1..." required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Titik Peta</label>
                                            <button type="button" class="btn btn-outline-hnb-orange w-100 radius-hnb py-3 font-weight-bold">
                                                <i class="fas fa-map-marker-alt mr-2"></i> Pilih Koordinat
                                            </button>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Patokan Terdekat</label>
                                            <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Depan tugu patung...">
                                        </div>
                                    </div>
                                </div>

                                <div id="step3" class="step-content d-none">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Telepon Darurat</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0 text-white font-weight-bold" style="border-color: rgba(255,255,255,0.1) !important;">+62</span>
                                                </div>
                                                <input type="number" class="form-control form-control-dark border-left-0 radius-hnb py-4 px-3 input-dark" placeholder="8123xxx" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Jam Buka</label>
                                            <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="08:00 - 17:00" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Status Saat Ini</label>
                                        <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                                            <option value="Buka">Buka / Aktif</option>
                                            <option value="Tutup Sementara">Tutup Sementara</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="step4" class="step-content d-none">
                                    <div class="upload-box-premium mb-4">
                                        <i class="fas fa-image fa-2x text-hnb-orange mb-2"></i>
                                        <p class="text-white small font-weight-bold mb-1">Unggah Foto Utama</p>
                                        <input type="file" class="form-control-file small text-white-50">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Email Admin Wisata</label>
                                        <input type="email" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="admin@wisata.com" required>
                                    </div>
                                </div>

                                <div id="step5" class="step-content d-none text-center py-4">
                                    <div class="icon-kesehatan mx-auto mb-4 d-flex align-items-center justify-content-center animasi-jantung" style="width: 100px; height: 100px; border-color: #28a745; background: rgba(40, 167, 69, 0.1);">
                                        <i class="fas fa-check fa-3x text-success"></i>
                                    </div>
                                    <h4 class="font-weight-bold text-white mb-2">Pariwisata Terdaftar!</h4>
                                    <p class="text-white-50 small mb-0">Tim WanderMed akan memverifikasi destinasi Anda segera.</p>
                                </div>

                                <div class="mt-5 d-flex justify-content-between" id="navButtons">
                                    <button type="button" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold" id="btnPrev" style="min-width: 140px;">
                                        <i class="fas fa-chevron-left mr-2"></i> Kembali
                                    </button>
                                    <button type="button" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold" id="btnNext" style="min-width: 140px;">
                                        Lanjut <i class="fas fa-chevron-right ml-2"></i>
                                    </button>
                                </div>

                                <div class="mt-5 d-none flex-column" id="navFinish">
                                    <a href="/" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-100 mb-3">Selesai & Ke Beranda</a>
                                    <a href="/daftar/pariwisata" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold w-100">Daftar Lagi</a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('theme.footer')
    <script src="{{ asset('js/wizard-pariwisata.js') }}"></script>
@endsection
