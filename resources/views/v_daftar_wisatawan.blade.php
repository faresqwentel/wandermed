@extends('theme.wisatawan')

@section('content')
    @include('theme.navbar')

    <section class="hero-slanted" style="min-height: 100vh; display: flex; align-items: center; padding-top: 50px; padding-bottom: 50px;">
        <div class="container px-4" style="position: relative; z-index: 5;">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">

                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold text-white teks-judul pb-1" id="formTitle">Profil Wisatawan</h3>
                        <p class="text-white-50 teks-subjudul" style="font-size: 0.95rem;" id="formSubtitle">Langkah 1 dari 3</p>
                        <div class="progress mt-3 radius-hnb shadow-sm" style="height: 6px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar transition-all" id="formProgress" style="width: 33%; background-color: var(--hnb-orange);"></div>
                        </div>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden" style="border: 1px solid rgba(255,255,255,0.1); border-top: 4px solid var(--hnb-orange); border-radius: 16px;">
                        <div class="card-body p-4 p-md-5">
                            <form id="wizardForm" action="#" method="POST">

                                <div id="step1" class="step-content">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Nama Lengkap</label>
                                        <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="Masukkan nama sesuai KTP..." required style="font-size: 1.05rem;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Nomor WhatsApp</label>
                                            <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-phone-alt"></i></span>
                                                </div>
                                                <input type="tel" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="0812xxx" required style="font-size: 1.05rem;">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Kota Asal</label>
                                            <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-map-marker-alt"></i></span>
                                                </div>
                                                <input type="text" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="Contoh: Bekasi" required style="font-size: 1.05rem;">
                                            </div>
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
    </section>

    @include('theme.footer')
    <script src="{{ asset('js/wizard-wisatawan.js') }}"></script>
@endsection
