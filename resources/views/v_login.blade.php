@extends('theme.wisatawan')

@section('content')
    @include('theme.navbar')

    <section class="hero-slanted" style="min-height: 100vh; display: flex; align-items: center; padding-top: 50px; padding-bottom: 50px;">
        <div class="container px-4" style="position: relative; z-index: 5;">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8 col-sm-10 animate-fade-up">

                    <div class="text-center mb-4">
                        <div class="icon-kesehatan mx-auto mb-3 d-flex align-items-center justify-content-center animasi-jantung" style="width: 80px; height: 80px;">
                            <i class="fas fa-heartbeat fa-2x text-hnb-orange"></i>
                        </div>
                        <h3 class="font-weight-bold text-white teks-judul pb-1">Selamat Datang Kembali</h3>
                        <p class="text-white-50 teks-subjudul" style="font-size: 0.95rem;">WanderMed - Sistem Manajemen Ekosistem Sehat</p>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden" style="border: 1px solid rgba(255,255,255,0.1); border-top: 4px solid var(--hnb-orange); border-radius: 16px;">
                        <div class="card-body p-4 p-md-5">

                            @if(session('error'))
                                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4"
                                     style="border-radius: 10px; background: rgba(231,74,59,0.15); border: 1px solid rgba(231,74,59,0.4); color: #ff6b6b; font-size: 13px;">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert d-flex align-items-center gap-2 mb-4"
                                     style="border-radius: 10px; background: rgba(28,200,138,0.15); border: 1px solid rgba(28,200,138,0.4); color: #1cc88a; font-size: 13px;">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf

                                <div class="form-group mb-4">
                                    <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Email Akun</label>
                                    <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" name="email" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4 @error('email') is-invalid @enderror" placeholder="Masukkan email aktif Anda..." value="{{ old('email') }}" required autocomplete="email" style="font-size: 1.05rem;">
                                    </div>
                                    @error('email')
                                        <small class="text-danger ml-1 mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-5">
                                    <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Kata Sandi</label>
                                    <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="password" id="inputPassword" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="Ketik kata sandi Anda..." required autocomplete="current-password" style="font-size: 1.05rem;">
                                        <div class="input-group-append">
                                            <button type="button" id="btnToggle" class="btn border-0 text-white-50 shadow-none px-3" style="background: transparent;">
                                                <i class="fas fa-eye" id="ikonMata"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <small class="text-danger ml-1 mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex flex-column gap-3 mt-2">
                                    <button type="submit" class="btn btn-hnb-orange py-3 font-weight-bold shadow-lg mb-3" style="border-radius: 10px; font-size: 1.1rem;">
                                        Masuk <i class="fas fa-sign-in-alt ml-2"></i>
                                    </button>

                                    <a href="/" class="btn btn-link text-white-50 text-decoration-none text-center hover-orange" style="font-size: 0.9rem;">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                         <p class="text-white-50 mb-0" style="font-size: 11px; letter-spacing: 1.5px;">
                            DIKEMBANGKAN OLEH <br>
                            <span class="font-weight-bold text-white">HEAR & BUILD STUDIO</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @include('theme.footer')
@endsection
