@extends('theme.wisatawan')

@section('content')
    @include('theme.navbar')

    <div class="bg-hnb-navy page-registration">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7 animate-fade-up">

                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold text-white teks-judul" id="formTitle">Profil Fasilitas Kesehatan</h3>
                        <p class="text-white-50 small teks-subjudul" id="formSubtitle">Langkah 1 dari 5</p>

                        <div class="progress mt-3 radius-hnb shadow-sm" style="height: 6px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-hnb-orange transition-all" id="formProgress" role="progressbar" style="width: 20%;"></div>
                        </div>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <form id="wizardForm" action="#" method="POST">

                                <div id="step1" class="step-content">
                                    <div class="row">
                                        <div class="col-md-7 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Nama Faskes</label>
                                            <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: RSUD Subang..." required>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Kategori</label>
                                            <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                                                <option value="" disabled selected>Pilih Kategori...</option>
                                                <option value="Rumah Sakit">Rumah Sakit</option>
                                                <option value="Klinik">Klinik Umum</option>
                                                <option value="Puskesmas">Puskesmas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Layanan Utama</label>
                                        <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="3" placeholder="Contoh: Melayani poli gigi, bedah ringan, dll..." required></textarea>
                                    </div>
                                </div>

                                <div id="step2" class="step-content d-none">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Alamat Lengkap</label>
                                        <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="2" placeholder="Jl. Raya Kesehatan No. 99..." required></textarea>
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
                                            <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Sebelah alun-alun...">
                                        </div>
                                    </div>
                                </div>

                                <div id="step3" class="step-content d-none">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Telepon Ambulans / Darurat</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 text-white font-weight-bold" style="border-color: rgba(255,255,255,0.1) !important;">+62</span>
                                            </div>
                                            <input type="tel" class="form-control form-control-dark border-left-0 radius-hnb py-4 px-3 input-dark" placeholder="8123xxx" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Terima BPJS?</label>
                                            <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                                                <option value="Ya">Ya, Menerima</option>
                                                <option value="Tidak">Tidak (Umum)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Layanan UGD</label>
                                            <select id="selectUGD" class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                                                <option value="24 Jam">Buka 24 Jam</option>
                                                <option value="Terbatas">Jam Terbatas</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="ugdTimeForm" class="ugd-time-container d-none p-3 radius-hnb">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="text-white-50 small ml-1">Buka</label>
                                                <input type="time" class="form-control form-control-dark radius-hnb input-dark">
                                            </div>
                                            <div class="col-6">
                                                <label class="text-white-50 small ml-1">Tutup</label>
                                                <input type="time" class="form-control form-control-dark radius-hnb input-dark">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="step4" class="step-content d-none">
                                    <div class="upload-box-premium mb-4">
                                        <i class="fas fa-hospital-alt fa-2x text-hnb-orange mb-2"></i>
                                        <p class="text-white small font-weight-bold mb-1">Unggah Foto Gedung Faskes</p>
                                        <input type="file" class="form-control-file small text-white-50 mx-auto">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Email Resmi Admin Faskes</label>
                                        <input type="email" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="admin@faskes.com" required>
                                    </div>
                                </div>

                                <div id="step5" class="step-content d-none">
                                    <div class="alert-hnb p-3 mb-4 d-flex">
                                        <i class="fas fa-info-circle text-hnb-orange mr-3 mt-1"></i>
                                        <p class="text-white-50 small mb-0">Akun ini untuk Login Dashboard & update status faskes secara <strong class="text-white">Real-Time</strong>.</p>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Username (ID Faskes)</label>
                                        <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: rs_subang_123" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Buat Sandi Akses</label>
                                        <div class="pass-group-wizard">
                                            <input type="password" id="inputPass" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Minimal 8 karakter..." required>
                                            <i class="fas fa-eye btn-toggle-pass" id="btnTogglePass"></i>
                                        </div>
                                    </div>
                                </div>

                                <div id="step6" class="step-content d-none text-center py-4">
                                    <div class="icon-kesehatan mx-auto mb-4 d-flex align-items-center justify-content-center animasi-jantung" style="width: 100px; height: 100px; border-color: #28a745; background: rgba(40, 167, 69, 0.1);">
                                        <i class="fas fa-check fa-3x text-success"></i>
                                    </div>
                                    <h4 class="font-weight-bold text-white mb-2">Pendaftaran Faskes Terkirim!</h4>
                                    <p class="text-white-50 small mb-0">Tim kami akan meninjau data Anda. Mohon cek email secara berkala.</p>
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
                                    <a href="/login" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-100 mb-3">Login ke Dashboard <i class="fas fa-sign-in-alt ml-2"></i></a>
                                    <a href="/" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold w-100">Kembali ke Beranda</a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('theme.footer')
    <script src="{{ asset('js/wizard-faskes.js') }}"></script>
@endsection
