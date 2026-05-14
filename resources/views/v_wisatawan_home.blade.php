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
                <h2 class="teks-judul font-weight-bold mb-3 text-white">Layanan & Tujuan Kami</h2>
                <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                <p class="teks-subjudul mx-auto text-white-50">Mengenal lebih dekat visi kami dalam mengintegrasikan pariwisata dengan fasilitas kesehatan yang tanggap darurat.</p>
            </div>

            <style>
                .hnb-bento-card { transition: all 0.3s ease; }
                .hnb-bento-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.2) !important; }
            </style>
            <div class="row g-4 justify-content-center">
                <!-- Card 1: Utama (Besar) -->
                <div class="col-lg-5 mb-4 px-3" data-aos="fade-right" data-aos-delay="0" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-5 h-100 shadow-sm position-relative overflow-hidden hnb-bento-card" style="border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid var(--hnb-orange); background: linear-gradient(145deg, rgba(255,122,0,0.08) 0%, transparent 100%);">
                        <div class="position-absolute" style="top: -20px; right: -20px; font-size: 160px; opacity: 0.04; color: white;">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 14px; background: rgba(255,122,0,0.15); border: 1px solid rgba(255,122,0,0.2);">
                            <i class="fas fa-bullseye fa-lg text-hnb-orange"></i>
                        </div>
                        <h3 class="text-white font-weight-bold mb-3" style="font-size: 1.7rem;">Tujuan Utama</h3>
                        <p class="text-white-50 mb-0" style="font-size: 1.05rem; line-height: 1.8;">
                            Menjadi jembatan digital yang andal untuk menghubungkan wisatawan dengan layanan kesehatan terdekat di momen genting, memberikan rasa aman selama berwisata di Subang.
                        </p>
                    </div>
                </div>

                <!-- Card 2: 3 Cards Kecil di kanan -->
                <div class="col-lg-7">
                    <div class="row h-100 align-content-stretch">
                        <div class="col-sm-6 mb-4 px-3" data-aos="fade-down" data-aos-delay="100" data-aos-duration="600">
                            <div class="glass-premier radius-hnb p-4 h-100 shadow-sm hnb-bento-card" style="border: 1px solid rgba(28,200,138,0.15);">
                                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(28,200,138,0.1);">
                                    <i class="fas fa-satellite-dish text-success" style="font-size: 1.2rem;"></i>
                                </div>
                                <h5 class="text-white font-weight-bold mb-2">Sistem Terpadu</h5>
                                <p class="text-white-50 mb-0" style="font-size: 0.9rem; line-height: 1.6;">Smart Mapping menampilkan lokasi dan ketersediaan layanan faskes secara <strong class="text-white-50">real-time</strong>.</p>
                            </div>
                        </div>

                        <div class="col-sm-6 mb-4 px-3" data-aos="fade-down" data-aos-delay="200" data-aos-duration="600">
                            <div class="glass-premier radius-hnb p-4 h-100 shadow-sm hnb-bento-card" style="border: 1px solid rgba(231,74,59,0.15);">
                                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(231,74,59,0.1);">
                                    <i class="fas fa-first-aid text-danger" style="font-size: 1.2rem;"></i>
                                </div>
                                <h5 class="text-white font-weight-bold mb-2">Solusi Instan</h5>
                                <p class="text-white-50 mb-0" style="font-size: 0.9rem; line-height: 1.6;">Temukan <strong>UGD 24 Jam</strong> atau faskes BPJS terdekat dalam hitungan detik saat darurat.</p>
                            </div>
                        </div>

                        <div class="col-12 mb-4 px-3" data-aos="fade-up" data-aos-delay="300" data-aos-duration="600">
                            <div class="glass-premier radius-hnb p-4 h-100 shadow-sm d-flex flex-column flex-sm-row align-items-start align-items-sm-center hnb-bento-card" style="border: 1px solid rgba(54,185,204,0.15); gap: 20px;">
                                <div class="d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 14px; background: rgba(54,185,204,0.1); border: 1px solid rgba(54,185,204,0.2); flex-shrink: 0;">
                                    <i class="fas fa-shield-alt fa-lg text-info"></i>
                                </div>
                                <div>
                                    <h5 class="text-white font-weight-bold mb-2">Standar Keamanan Tinggi</h5>
                                    <p class="text-white-50 mb-0" style="font-size: 0.95rem; line-height: 1.6;">Mendukung terciptanya ekosistem pariwisata yang tidak hanya indah, tapi juga terjamin keselamatan medisnya untuk semua wisatawan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="panduan" class="py-5 section-scroll">
        <div class="container px-4 my-5 d-flex flex-column align-items-center">
            <div class="text-center mb-5 pb-2" data-aos="fade-up" data-aos-duration="600">
                <h2 class="teks-judul font-weight-bold mb-3 text-white">Panduan Penggunaan</h2>
                <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                <p class="teks-subjudul mx-auto text-white-50">3 Langkah mudah menemukan fasilitas kesehatan dan destinasi yang tepat.</p>
            </div>
            <div class="row w-100 justify-content-center" style="max-width: 1000px; gap: 20px 0;">

                {{-- Card 1 --}}
                <div class="col-md-4 mb-4 px-3" data-aos="fade-up" data-aos-delay="0" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-4 h-100 text-center shadow-sm" style="border-top: 4px solid var(--hnb-orange);">
                        <div class="mb-4 mt-2">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,122,0,0.15); color: var(--hnb-orange); font-size: 1.5rem; font-weight: 800;">1</span>
                        </div>
                        <h5 class="text-white font-weight-bold mb-3">Akses Peta Pintar</h5>
                        <p class="text-white-50 mb-0" style="font-size: 14px; line-height: 1.6;">Klik tombol <strong>Buka Peta Faskes</strong> untuk langsung memuat sistem pemetaan digital interaktif pada layar perangkat Anda.</p>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div class="col-md-4 mb-4 px-3" data-aos="fade-up" data-aos-delay="150" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-4 h-100 text-center shadow-sm" style="border-top: 4px solid var(--hnb-orange);">
                        <div class="mb-4 mt-2">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,122,0,0.15); color: var(--hnb-orange); font-size: 1.5rem; font-weight: 800;">2</span>
                        </div>
                        <h5 class="text-white font-weight-bold mb-3">Gunakan Pencarian</h5>
                        <p class="text-white-50 mb-0" style="font-size: 14px; line-height: 1.6;">Gunakan fitur *filter* dan kolom pencarian untuk memilah destinasi pariwisata, apotek, klinik, maupun rumah sakit.</p>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div class="col-md-4 mb-4 px-3" data-aos="fade-up" data-aos-delay="300" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-4 h-100 text-center shadow-sm" style="border-top: 4px solid var(--hnb-orange);">
                        <div class="mb-4 mt-2">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,122,0,0.15); color: var(--hnb-orange); font-size: 1.5rem; font-weight: 800;">3</span>
                        </div>
                        <h5 class="text-white font-weight-bold mb-3">Dapatkan Navigasi</h5>
                        <p class="text-white-50 mb-0" style="font-size: 14px; line-height: 1.6;">Klik titik lokasi di peta untuk melihat kontak informasi layanan, serta panduan arah rute tercepat ke lokasi darurat.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section id="mitra" class="py-5 section-scroll">
        <div class="container px-4 my-5">
            <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="600">
                <h2 class="teks-judul font-weight-bold mb-3 text-white">Peluang Kemitraan</h2>
                <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                <p class="teks-subjudul mx-auto text-white-50 mb-0">Jadilah bagian dari ekosistem digital untuk pariwisata sehat dan tanggap darurat.</p>
            </div>
            <div class="row align-items-stretch justify-content-center" style="gap: 20px 0; max-width: 1000px; margin: 0 auto;">
                
                <div class="col-md-6 mb-4 px-3" data-aos="fade-right" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-4 p-md-5 h-100 shadow-sm d-flex flex-column justify-content-center">
                        <h5 class="text-white font-weight-bold mb-4 border-bottom pb-3" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="fas fa-handshake text-hnb-orange mr-2"></i> Mengapa Bergabung?
                        </h5>
                        <ul class="text-white-50 pl-4 mb-0" style="font-size: 14.5px; line-height: 1.9;">
                            <li class="mb-3"><strong>Visibilitas Tinggi:</strong> Lokasi Faskes atau Pariwisata Anda akan terdaftar eksklusif di peta digital WanderMed.</li>
                            <li class="mb-3"><strong>Manajemen Cepat:</strong> Perbarui status layanan, fasilitas, dan jadwal praktik secara *real-time* lewat dashboard admin.</li>
                            <li><strong>Bantuan Darurat:</strong> Membantu wisatawan mendapatkan tindakan medis yang cepat di saat-saat genting.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 mb-4 px-3" data-aos="fade-left" data-aos-duration="600">
                    <div class="glass-premier radius-hnb p-4 p-md-5 h-100 text-center shadow-sm d-flex flex-column justify-content-center align-items-center" style="border-color: var(--hnb-orange) !important; background: rgba(255, 122, 0, 0.05) !important;">
                        <i class="fas fa-user-plus fa-3x text-hnb-orange mb-3 opacity-75"></i>
                        <h4 class="text-white font-weight-bold mb-3">Siap Berkolaborasi?</h4>
                        <p class="text-white-50 mb-4" style="font-size: 14px;">Tingkatkan mutu keselamatan pelayanan pariwisata bersama kami hari ini juga.</p>
                        
                        <div class="w-100" style="display:grid; gap: 12px;">
                            <a href="/daftar" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold shadow-sm w-100">
                                <i class="fas fa-clipboard-list mr-2"></i> Mulai Pendaftaran
                            </a>
                            @if(session('auth_user'))
                                <a href="{{ url('/login') }}" class="btn btn-light text-hnb-navy radius-hnb px-4 py-3 font-weight-bold w-100">
                                    <i class="fas fa-sign-in-alt mr-2 text-hnb-orange"></i> Lanjut ke Dashboard
                                </a>
                            @else
                                <a href="/login" class="btn btn-light text-hnb-navy radius-hnb px-4 py-3 font-weight-bold w-100">
                                    <i class="fas fa-sign-in-alt mr-2 text-hnb-orange"></i> Login Mitra
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-5 mb-4">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-lg-10 px-3" data-aos="zoom-in" data-aos-duration="800">
                    <div class="glass-premier position-relative radius-hnb p-5 overflow-hidden shadow-lg border-0 text-center"
                         style="background: linear-gradient(135deg, rgba(20, 25, 40, 0.8) 0%, rgba(10, 14, 25, 0.9) 100%); border-top: 1px solid rgba(255,255,255,0.08) !important;">
                        
                        <!-- Decorative Elements -->
                        <div class="position-absolute" style="top: -60px; left: -60px; width: 250px; height: 250px; background: radial-gradient(circle, rgba(255,122,0,0.12) 0%, transparent 70%); border-radius: 50%;"></div>
                        <div class="position-absolute" style="bottom: -80px; right: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(78,115,223,0.1) 0%, transparent 70%); border-radius: 50%;"></div>

                        <div class="position-relative" style="z-index: 2;">
                            <div class="mb-4">
                                <i class="fas fa-quote-left text-hnb-orange opacity-50 fa-2x"></i>
                            </div>
                            
                            <h4 class="text-white font-weight-bold mb-4" style="letter-spacing: 1px;">Sebuah Dedikasi</h4>
                            
                            <p class="text-white-50 mx-auto mb-5" style="max-width: 750px; font-size: 1.15rem; line-height: 1.8; font-style: italic;">
                                "Terima kasih telah mempercayakan perjalanan Anda kepada <strong>WanderMed</strong>. Kami membangun platform ini dengan visi sederhana: merangkai ekosistem pariwisata yang lebih peduli, responsif, dan terintegrasi demi keamanan setiap langkah Anda."
                            </p>

                            <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 15px;">
                                <div class="d-flex align-items-center px-4 py-2 hover-translate" style="background: rgba(255,255,255,0.03); border-radius: 50px; border: 1px solid rgba(255,255,255,0.05); transition: transform 0.3s;">
                                    <img src="{{ asset('img/wdm.png') }}" class="logo-for-light-bg mr-3" style="width: 24px; height: 24px; object-fit: contain;">
                                    <img src="{{ asset('img/wdmlight.png') }}" class="logo-for-dark-bg mr-3" style="width: 24px; height: 24px; object-fit: contain;">
                                    <span class="text-white font-weight-bold" style="font-size: 0.9rem; letter-spacing: 0.5px;">HEAR & BUILD STUDIO</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('theme.footer')

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/wisatawan-home.js') }}"></script>

@endpush
