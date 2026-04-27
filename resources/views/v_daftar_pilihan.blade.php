@extends('theme.wisatawan')

@section('content')

    @include('theme.navbar')

    <section class="hero-slanted" style="min-height: 100vh; display: flex; align-items: center; padding-top: 50px; padding-bottom: 50px;">
        <div class="container px-5" style="position: relative; z-index: 5;">

            <div class="text-center mb-5 animate-fade-up">
                <h2 class="font-weight-bold text-white teks-judul">Mulai Langkahmu Bersama WanderMed</h2>
                <div style="width: 50px; height: 3px; background-color: var(--hnb-orange); margin: 15px auto; border-radius: 2px;"></div>
                <p class="text-white-50 teks-subjudul mx-auto">Pilih peran Anda untuk mendaftar</p>
            </div>

            <div class="search-registration animate-fade-up delay-1">
                <div class="glass-premier radius-hnb p-2 shadow-sm">
                    <div class="input-group align-items-center px-3">
                        <i class="fas fa-search text-white-50"></i>
                        <input type="text" class="form-control border-0 bg-transparent input-dark text-white py-3 shadow-none" placeholder="Cari peran..." style="font-size: 14px;">
                    </div>
                </div>
            </div>

            <div class="row animate-fade-up delay-2 justify-content-center">
                <div class="col-lg-4 mb-4">
                    <a href="/daftar/pariwisata" class="card glass-premier card-opsi-landscape text-decoration-none shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="icon-kesehatan"><i class="fas fa-mountain fa-2x text-hnb-orange"></i></div>
                            <h6 class="font-weight-bold text-white mb-2">Destinasi Pariwisata</h6>
                            <p class="text-white-50 small mb-0">Daftarkan lokasi wisata Anda.</p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 mb-4">
                    <a href="/daftar/faskes" class="card glass-premier card-opsi-landscape text-decoration-none shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="icon-kesehatan"><i class="fas fa-hospital fa-2x text-hnb-orange"></i></div>
                            <h6 class="font-weight-bold text-white mb-2">Mitra Faskes</h6>
                            <p class="text-white-50 small mb-0">Daftarkan klinik/rumah sakit.</p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 mb-4">
                    <a href="/daftar/wisatawan" class="card glass-premier card-opsi-landscape text-decoration-none shadow-sm">
                        <div class="card-body p-4 text-center">
                            <div class="icon-kesehatan"><i class="fas fa-user fa-2x text-hnb-orange"></i></div>
                            <h6 class="font-weight-bold text-white mb-2">Wisatawan Biasa</h6>
                            <p class="text-white-50 small mb-0">Buat akun profil wisatawan.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="text-center mt-4 animate-fade-up delay-3">
                <a href="/" class="btn btn-outline-light radius-hnb px-5 py-3 font-weight-bold shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
            </div>

        </div>
    </section>

    <section class="bg-hnb-navy pt-5 pb-4 mt-5">
        @include('theme.footer')
    </section>

@endsection
