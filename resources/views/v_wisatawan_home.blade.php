@extends('theme.wisatawan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link href="{{ asset('css/wisatawan-home.css') }}" rel="stylesheet">
@endpush

@section('content')
    @include('theme.navbar')

    <div id="page-top"></div>

    <section class="hero-slanted section-scroll" style="position:relative;">

        {{-- Layer 0: Peta Leaflet sebagai Background --}}
        <div id="hero-bg-map"></div>

        {{-- Layer 1: Dark Gradient Overlay --}}
        <div class="hero-map-overlay"></div>

        {{-- Badge Live Map --}}
        <div class="hero-map-badge">
            <span class="dot-live"></span> Live Map · Subang
        </div>

        {{-- Layer 2: Konten Utama --}}
        <div class="container px-4" style="position:relative; z-index:2;">
            <div class="row align-items-center">

                <div class="col-lg-7 text-left mb-5 mb-lg-0" data-aos="fade-right" data-aos-duration="700">
                    <div class="hero-content-box">
                        <div class="mb-4" style="display:inline-flex;align-items:center;gap:10px;background:rgba(255,122,0,0.12);border:1px solid rgba(255,122,0,0.3);border-radius:20px;padding:6px 16px;">
                            <i class="fas fa-map-marked-alt text-hnb-orange" style="font-size:13px;"></i>
                            <span class="hero-tag-text">SISTEM PEMETAAN MEDIS WISATAWAN</span>
                        </div>
                        <h1 class="font-weight-bold text-white mb-3" style="font-size: 3.5rem; line-height: 1.15;">
                            Kesehatan Anda,<br>
                            <span class="text-hnb-orange">Prioritas Kami.</span>
                        </h1>
                        <p class="text-white-50 mb-5" style="font-size: 1.1rem; max-width: 540px; line-height: 1.75;">
                            Temukan UGD, Klinik, dan Apotek terdekat di Subang secara <strong class="hero-strong">real-time</strong> langsung dari peta interaktif kami — dalam hitungan detik.
                        </p>

                        <div class="d-flex flex-wrap align-items-center" style="gap:14px;">
                            <a href="/peta-faskes" class="btn btn-hnb-orange radius-hnb font-weight-bold shadow-lg py-3 px-5" style="border-radius:12px;font-size:1.1rem;display:inline-flex;align-items:center;">
                                <i class="fas fa-map-marked-alt mr-3" style="font-size:1.2rem;"></i> Buka Peta Faskes
                            </a>
                            <a href="#tentang" class="btn hero-btn-outline">
                                Pelajari Lebih Lanjut <i class="fas fa-arrow-down ml-2"></i>
                            </a>
                        </div>

                        <div class="mt-4 d-flex flex-wrap" style="gap:20px;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-check-circle text-success"></i>
                                <span class="hero-check-text">Mendukung BPJS & UGD 24 Jam</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <i class="fas fa-check-circle text-success"></i>
                                <span class="hero-check-text">Gratis untuk Wisatawan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-flex justify-content-center" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200">
                    <div class="d-flex align-items-center justify-content-center animasi-jantung hero-logo-box">
                        <img src="{{ asset('img/wdm.png') }}" alt="WanderMed Logo" class="logo-for-light-bg" style="width: 200px; height: 200px; object-fit: contain;">
                        <img src="{{ asset('img/wdmlight.png') }}" alt="WanderMed Logo" class="logo-for-dark-bg" style="width: 200px; height: 200px; object-fit: contain;">
                    </div>
                </div>

            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="scroll-indicator" id="heroScrollIndicator"
             onclick="if(typeof $ !== 'undefined'){$('html,body').animate({scrollTop:window.innerHeight*0.85},800);}else{window.scrollBy({top:window.innerHeight*0.85,behavior:'smooth'});}">
            <span>Scroll</span>
            <i class="fas fa-chevron-down text-hnb-orange" style="font-size:18px;"></i>
        </div>
    </section>

    <section id="tentang" class="py-5 section-scroll">
        <div class="container px-4 mt-5 mb-5">

            <div class="text-center mb-5 pb-4" data-aos="fade-up" data-aos-duration="600">
                <h2 class="teks-judul font-weight-bold mb-3 text-white">At Your Service</h2>
                <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                <p class="teks-subjudul mx-auto text-white-50">Mengenal lebih dekat visi dan misi kami untuk pariwisata sehat.</p>
            </div>

            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0 px-3" data-aos="fade-up" data-aos-delay="0" data-aos-duration="600">
                    <div class="mb-4"><i class="fas fa-gem fa-4x text-hnb-orange"></i></div>
                    <h5 class="text-white font-weight-bold mb-3">Tujuan Utama</h5>
                    <p class="text-white-50 mb-0" style="font-size: 14.5px; line-height: 1.8;">Menjadi jembatan digital yang menghubungkan wisatawan dengan Faskes terdekat.</p>
                </div>

                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0 px-3" data-aos="fade-up" data-aos-delay="100" data-aos-duration="600">
                    <div class="mb-4"><i class="fas fa-laptop-code fa-4x text-hnb-orange"></i></div>
                    <h5 class="text-white font-weight-bold mb-3">Sistem Terpadu</h5>
                    <p class="text-white-50 mb-0" style="font-size: 14.5px; line-height: 1.8;">Smart Mapping yang menampilkan rute darurat tercepat secara <i>real-time</i>.</p>
                </div>

                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0 px-3" data-aos="fade-up" data-aos-delay="200" data-aos-duration="600">
                    <div class="mb-4"><i class="fas fa-globe fa-4x text-hnb-orange"></i></div>
                    <h5 class="text-white font-weight-bold mb-3">Solusi Instan</h5>
                    <p class="text-white-50 mb-0" style="font-size: 14.5px; line-height: 1.8;">Temukan UGD 24 Jam atau faskes BPJS dalam hitungan detik tanpa panik.</p>
                </div>

                <div class="col-lg-3 col-md-6 px-3" data-aos="fade-up" data-aos-delay="300" data-aos-duration="600">
                    <div class="mb-4 d-flex justify-content-center">
                        <img src="{{ asset('img/wdm.png') }}" alt="Logo" class="logo-for-light-bg" style="width: 80px; height: 80px; object-fit: contain;">
                        <img src="{{ asset('img/wdmlight.png') }}" alt="Logo" class="logo-for-dark-bg" style="width: 80px; height: 80px; object-fit: contain;">
                    </div>
                    <h5 class="text-white font-weight-bold mb-3">Made with Love</h5>
                    <p class="text-white-50 mb-0" style="font-size: 14.5px; line-height: 1.8;">Mendukung pariwisata berstandar keselamatan tinggi dengan penuh dedikasi.</p>
                </div>
            </div>

        </div>
    </section>

    <section id="panduan" class="py-5 section-scroll">
        <div class="container px-4 my-5 d-flex flex-column align-items-center">
            <div class="text-center mb-5 pb-2" data-aos="fade-up" data-aos-duration="600">
                <h2 class="teks-judul font-weight-bold mb-3 text-white">Panduan Penggunaan</h2>
                <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                <p class="teks-subjudul mx-auto text-white-50">3 Langkah mudah berburu faskes dan destinasi dengan WanderMed.</p>
            </div>
            <div class="row w-100 justify-content-center" style="max-width: 1000px; gap: 20px;">

                {{-- Card 1 --}}
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="0" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-4 h-100 text-center shadow-sm" style="border-top: 4px solid var(--hnb-orange);">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255,122,0,0.1); color: var(--hnb-orange); font-size: 1.5rem; font-weight: bold;">1</span>
                        </div>
                        <h5 class="text-white font-weight-bold mb-3">Buka Peta Digital</h5>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem;">Klik tombol "Cari Faskes" di halaman utama untuk memuat peta pintar responsif di layar perangkat Anda.</p>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="150" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-4 h-100 text-center shadow-sm" style="border-top: 4px solid var(--hnb-orange);">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255,122,0,0.1); color: var(--hnb-orange); font-size: 1.5rem; font-weight: bold;">2</span>
                        </div>
                        <h5 class="text-white font-weight-bold mb-3">Atur Filter</h5>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem;">Pilih apakah Anda ingin mencari "Semua Destinasi", "Faskes khusus", atau "Pariwisata". Peta akan menyesuaikan diri.</p>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-4 h-100 text-center shadow-sm" style="border-top: 4px solid var(--hnb-orange);">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255,122,0,0.1); color: var(--hnb-orange); font-size: 1.5rem; font-weight: bold;">3</span>
                        </div>
                        <h5 class="text-white font-weight-bold mb-3">Rute Navigasi</h5>
                        <p class="text-white-50 mb-0" style="font-size: 0.95rem;">Klik lokasi target di peta dan otomatis dapatkan panduan rute (Google Maps) dan nomor penting yang bisa dihubungi.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section id="mitra" class="py-5 section-scroll">
        <div class="container px-4 my-5">
            <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="600">
                <h2 class="teks-judul font-weight-bold mb-3 text-white">Gabung Kemitraan</h2>
                <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                <p class="teks-subjudul mx-auto text-white-50 mb-0">Jadilah bagian dari ekosistem pariwisata sehat.</p>
            </div>
            <div class="mx-auto" style="max-width: 800px;">
                <div class="glass-premier radius-hnb p-4 p-md-5 mb-4 shadow-sm">
                    <h5 class="text-white font-weight-bold mb-4 text-center border-bottom pb-3" style="border-color: rgba(255,255,255,0.1) !important;">Keuntungan Menjadi Mitra</h5>
                    <ul class="text-white-50 pl-4 mb-0" style="font-size: 15px; line-height: 2;">
                        <li class="mb-3"><strong>Visibilitas Tinggi:</strong> Destinasi atau faskes Anda akan muncul di peta digital WanderMed.</li>
                        <li class="mb-3"><strong>Manajemen Real-Time:</strong> Kelola operasional dan status layanan melalui dashboard admin khusus.</li>
                        <li><strong>Dukungan Keselamatan:</strong> Membantu wisatawan dalam situasi darurat medis secara lebih cepat.</li>
                    </ul>
                </div>
                <div class="glass-premier radius-hnb p-4 text-center shadow-sm" style="border-color: var(--hnb-orange) !important; background: rgba(255, 122, 0, 0.05) !important;">
                    <p class="text-white font-weight-bold mb-4" style="font-size: 16px;">Sudah siap bergabung bersama kami?</p>
                    <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 15px;">
                        <a href="/daftar" class="btn btn-hnb-orange radius-hnb px-5 py-3 font-weight-bold shadow-lg" style="font-size: 15px; min-width: 200px;">Mendaftar Sekarang</a>
                        @if(session('auth_user'))
                            <a href="{{ url('/login') }}" class="btn btn-light text-hnb-navy radius-hnb px-5 py-3 font-weight-bold" style="font-size: 15px; min-width: 200px;">
                                Enter <i class="fas fa-door-open ml-1 text-hnb-orange"></i>
                            </a>
                        @else
                            <a href="/login" class="btn btn-light text-hnb-navy radius-hnb px-5 py-3 font-weight-bold" style="font-size: 15px; min-width: 200px;">
                                Login <i class="fas fa-sign-in-alt ml-1 text-hnb-orange"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container px-4">
            <div class="row">
                <div class="col-12 px-3">
                    <div class="glass-premier radius-hnb p-4 p-md-5 text-center shadow-sm border-0"
                         style="background: rgba(255, 122, 0, 0.03) !important; border: 1px dashed rgba(255, 122, 0, 0.2) !important;">

                        <h5 class="text-hnb-orange font-weight-bold mb-3">Special Thanks</h5>
                        <p class="text-white-50 mx-auto mb-4" style="max-width: 700px; line-height: 1.8;">
                            Terima kasih telah mempercayakan perjalanan Anda kepada <strong>WanderMed</strong>. Proyek ini adalah dedikasi kami untuk menciptakan ekosistem pariwisata Indonesia yang lebih aman, sehat, dan terintegrasi bagi setiap petualang.
                        </p>

                        <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 20px;">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('img/wdm.png') }}" class="logo-for-light-bg mr-2" style="width: 30px; height: 30px; object-fit: contain;">
                                <img src="{{ asset('img/wdmlight.png') }}" class="logo-for-dark-bg mr-2" style="width: 30px; height: 30px; object-fit: contain;">
                                <span class="text-white small font-weight-bold opacity-75">Developed by Hear & Build Studio</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-hnb-navy pt-5 pb-4 mt-5">
        @include('theme.footer')
    </section>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/wisatawan-home.js') }}"></script>

@endpush
