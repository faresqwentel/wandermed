@extends('theme.wisatawan')

@section('content')
    @include('theme.navbar')

    <div class="page-registration">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">

                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold text-white teks-judul" id="formTitle">Profil Wisatawan</h3>
                        <p class="text-white-50 small teks-subjudul" id="formSubtitle">Langkah 1 dari 3</p>
                        <div class="progress mt-3 radius-hnb" style="height: 6px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-hnb-orange" id="formProgress" style="width: 33%;"></div>
                        </div>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <form id="wizardForm" action="#" method="POST">

                                <div id="step1" class="step-content">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Nama Lengkap</label>
                                        <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Masukkan nama sesuai KTP..." required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Nomor WhatsApp</label>
                                            <input type="tel" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="0812xxx" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Kota Asal</label>
                                            <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: Bekasi" required>
                                        </div>
                                    </div>
                                </div>

                                <div id="step2" class="step-content d-none">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Golongan Darah</label>
                                            <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;">
                                                <option value="-">- Pilih -</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="AB">AB</option>
                                                <option value="O">O</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Gunakan BPJS?</label>
                                            <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;">
                                                <option value="Ya">Ya</option>
                                                <option value="Tidak">Tidak</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Alergi atau Riwayat Penyakit</label>
                                        <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="3" placeholder="Contoh: Alergi kacang, riwayat asma, dsb..."></textarea>
                                    </div>
                                </div>

                                <div id="step3" class="step-content d-none">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Email Aktif</label>
                                        <input type="email" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="nama@email.com" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Buat Kata Sandi</label>
                                        <div class="pass-group-wizard">
                                            <input type="password" id="inputPass" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Minimal 8 karakter..." required>
                                            <i class="fas fa-eye btn-toggle-pass" id="btnTogglePass"></i>
                                        </div>
                                    </div>
                                </div>

                                <div id="step4" class="step-content d-none text-center py-4">
                                    <div class="d-flex align-items-center justify-content-center mx-auto mb-4 animasi-jantung"
                                        style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid #28a745; background: rgba(40, 167, 69, 0.1); box-shadow: 0 0 20px rgba(40, 167, 69, 0.2);">
                                        <i class="fas fa-user-check fa-3x text-success"></i>
                                    </div>
                                    <h4 class="font-weight-bold text-white mb-2">Selamat Datang, Traveler!</h4>
                                    <p class="text-white-50 small mb-0">Akun WanderMed kamu siap digunakan untuk perjalanan yang lebih aman.</p>
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
                                    <a href="/" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-100 mb-3">Jelajahi Faskes Sekarang</a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('theme.footer')
    <script src="{{ asset('js/wizard-wisatawan.js') }}"></script>
@endsection
