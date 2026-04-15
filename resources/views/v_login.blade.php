@extends('theme.wisatawan')

@section('content')
    @include('theme.navbar')

    <div class="bg-hnb-navy page-login">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 animate-fade-up">

                    <div class="text-center mb-4">
                        <div class="icon-kesehatan mx-auto mb-3 d-flex align-items-center justify-content-center animasi-jantung" style="width: 70px; height: 70px;">
                            <i class="fas fa-heartbeat fa-2x text-hnb-orange"></i>
                        </div>
                        <h3 class="font-weight-bold text-white teks-judul">WanderMed Login</h3>
                        <p class="text-white-50 small teks-subjudul">Sistem Informasi Mitra Faskes & Pariwisata</p>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden login-box-premium">
                        <div class="card-body p-4 p-md-5">

                            {{-- Tampilkan pesan error dari AuthController --}}
                            @if(session('error'))
                                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 radius-hnb"
                                     style="background: rgba(231,74,59,0.15); border: 1px solid rgba(231,74,59,0.4); color: #ff6b6b; font-size: 13px;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{-- Tampilkan pesan sukses (mis. setelah daftar) --}}
                            @if(session('success'))
                                <div class="alert d-flex align-items-center gap-2 mb-4 radius-hnb"
                                     style="background: rgba(28,200,138,0.15); border: 1px solid rgba(28,200,138,0.4); color: #1cc88a; font-size: 13px;">
                                    <i class="fas fa-check-circle"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            {{-- Form Login — POST ke AuthController@processLogin --}}
                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf

                                <div class="form-group mb-4">
                                    <label class="text-white font-weight-bold ml-1 small opacity-75">Email Pengguna</label>
                                    <div class="input-group-custom">
                                        <i class="fas fa-envelope icon-field"></i>
                                        <input type="email"
                                               name="email"
                                               class="form-control-transparent @error('email') is-invalid @enderror"
                                               placeholder="nama@email.com"
                                               value="{{ old('email') }}"
                                               required autocomplete="email">
                                    </div>
                                    @error('email')
                                        <small class="text-danger ml-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-5">
                                    <label class="text-white font-weight-bold ml-1 small opacity-75">Kata Sandi</label>
                                    <div class="input-group-custom">
                                        <i class="fas fa-lock icon-field"></i>
                                        <input type="password"
                                               name="password"
                                               id="inputPassword"
                                               class="form-control-transparent"
                                               placeholder="••••••••"
                                               required autocomplete="current-password">
                                        <button type="button" id="btnToggle" class="btn-toggle-pass">
                                            <i class="fas fa-eye" id="ikonMata"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <small class="text-danger ml-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex flex-column gap-3">
                                    <button type="submit" class="btn btn-hnb-orange radius-hnb py-3 font-weight-bold shadow-sm mb-3">
                                        Masuk Sekarang <i class="fas fa-sign-in-alt ml-2"></i>
                                    </button>

                                    <a href="/" class="btn btn-link text-white-50 text-decoration-none small text-center hover-orange">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <p class="text-white-50 mb-0" style="font-size: 11px; letter-spacing: 1px;">
                            DIKEMBANGKAN OLEH <br>
                            <span class="font-weight-bold text-white">HEAR & BUILD STUDIO</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('theme.footer')
@endsection
