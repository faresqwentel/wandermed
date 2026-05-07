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
                            @if($errors->any())
                            <div class="alert alert-danger mb-4" style="border-radius: 10px; background: rgba(231,74,59,0.15); border: 1px solid rgba(231,74,59,0.4); color: #ff6b6b; font-size: 13px;">
                                <i class="fas fa-exclamation-circle mr-2"></i> <strong>Terjadi kesalahan:</strong>
                                <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                            @endif

                            <form id="wizardForm" action="{{ route('register.wisatawan') }}" method="POST">
                                @csrf
                                <div id="step1" class="step-content">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Nama Lengkap</label>
                                        <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="Masukkan nama sesuai KTP..." required style="font-size: 1.05rem;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-4">
                                            <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Nomor WhatsApp (Kontak Darurat)</label>
                                            <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-phone-alt"></i></span>
                                                </div>
                                                <input type="tel" name="kontak_darurat" value="{{ old('kontak_darurat') }}" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="0812xxx" required style="font-size: 1.05rem;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="step2" class="step-content d-none">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Golongan Darah</label>
                                            <select name="gol_darah" class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;">
                                                <option value="" selected>- Pilih -</option>
                                                <option value="A" {{ old('gol_darah') == 'A' ? 'selected' : '' }}>A</option>
                                                <option value="B" {{ old('gol_darah') == 'B' ? 'selected' : '' }}>B</option>
                                                <option value="AB" {{ old('gol_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                                <option value="O" {{ old('gol_darah') == 'O' ? 'selected' : '' }}>O</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Alergi atau Riwayat Penyakit</label>
                                        <textarea name="riwayat_alergi" class="form-control form-control-dark radius-hnb p-3 input-dark" rows="3" placeholder="Contoh: Alergi kacang, riwayat asma, dsb...">{{ old('riwayat_alergi') }}</textarea>
                                    </div>
                                </div>

                                <div id="step3" class="step-content d-none">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Email Aktif</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="nama@email.com" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Buat Kata Sandi</label>
                                            <div class="pass-group-wizard">
                                                <input type="password" name="password" id="inputPass" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Minimal 8 karakter..." required>
                                                <i class="fas fa-eye btn-toggle-pass" id="btnTogglePass"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Konfirmasi Sandi</label>
                                            <div class="pass-group-wizard">
                                                <input type="password" name="password_confirmation" id="inputPassConfirm" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Ulangi sandi..." required>
                                            </div>
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

                    <div class="text-center mt-4 animate-fade-up">
                        <p class="text-white-50 small">
                            Sudah punya akun? 
                            <a href="/login" class="text-hnb-orange font-weight-bold text-decoration-none ml-1">
                                Masuk Sekarang <i class="fas fa-sign-in-alt ml-1"></i>
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @include('theme.footer')
    <script src="{{ asset('js/wizard-wisatawan.js') }}"></script>
@endsection
