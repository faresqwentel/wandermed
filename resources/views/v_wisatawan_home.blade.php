@extends('theme.wisatawan')

@section('content')
    @include('theme.navbar')

    <style>
        .custom-control-input:checked ~ .custom-control-label::before {
            border-color: #00A651 !important;
            background-color: #00A651 !important;
        }
        .custom-control-input:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 0.2rem rgba(0, 166, 81, 0.25) !important;
        }
    </style>

    <div id="page-top"></div>

    <section class="hero-slanted section-scroll">
        <div class="container px-4">
            <div class="row align-items-center">

                <div class="col-lg-6 text-left mb-5 mb-lg-0">
                    <h1 class="font-weight-bold text-white mb-3" style="font-size: 3.5rem; line-height: 1.2;">
                        Kesehatan Anda,<br>
                        <span class="text-hnb-orange">Prioritas Kami.</span>
                    </h1>
                    <p class="text-white-50 mb-5" style="font-size: 1.1rem; max-width: 90%;">
                        Sistem pemetaan pintar yang membantu wisatawan menemukan UGD, Klinik, atau Apotek terdekat dalam hitungan detik..
                    </p>

                    <form action="#" method="GET" class="w-100 pr-lg-4">
                        <div class="glass-premier radius-hnb p-3 mb-3 w-100" style="border-radius: 12px !important;">
                            <p class="text-white-50 mb-2 font-weight-bold" style="font-size: 11px; text-transform: uppercase;">Cari Faskes Terdekat</p>
                            <div class="d-flex align-items-center bg-transparent border-bottom pb-2" style="border-color: rgba(255,255,255,0.15) !important;">
                                <i class="fas fa-search text-hnb-orange mr-2"></i>
                                <input type="text" class="form-control border-0 bg-transparent input-dark text-white shadow-none p-0" placeholder="Ketik nama klinik atau apotek..." style="font-size: 14px;">
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <button type="submit" class="btn btn-hnb-orange radius-hnb font-weight-bold shadow-lg mr-3 px-4 py-3" style="font-size: 15px;">
                                Cari Bantuan
                            </button>

                            <div class="glass-premier rounded-pill px-3 py-2 d-inline-flex align-items-center shadow-sm">
                                <i class="fas fa-shield-alt mr-2" style="color: #00A651;"></i>
                                <span class="text-white font-weight-bold mr-3" style="font-size: 12px;">BPJS</span>
                                <div class="custom-control custom-switch m-0" style="padding-left: 2.2rem !important;">
                                    <input type="checkbox" class="custom-control-input" id="bpjsSwitch">
                                    <label class="custom-control-label" for="bpjsSwitch">&nbsp;</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                    <div class="icon-kesehatan d-flex align-items-center justify-content-center animasi-jantung glass-premier" style="width: 350px; height: 350px; border-width: 5px;">
                        <img src="{{ asset('img/wdm.png') }}" alt="WanderMed Logo" class="logo-for-light-bg">
                        <img src="{{ asset('img/wdmlight.png') }}" alt="WanderMed Logo" class="logo-for-dark-bg">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="tentang" class="py-5 section-scroll">
        <div class="container px-4 mt-5 mb-5">

            <div class="text-center mb-5 pb-4">
                <h2 class="teks-judul font-weight-bold mb-3 text-white">At Your Service</h2>
                <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                <p class="teks-subjudul mx-auto text-white-50">Mengenal lebih dekat visi dan misi kami untuk pariwisata sehat.</p>
            </div>

            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0 px-3">
                    <div class="mb-4"><i class="fas fa-gem fa-4x text-hnb-orange"></i></div>
                    <h5 class="text-white font-weight-bold mb-3">Tujuan Utama</h5>
                    <p class="text-white-50 mb-0" style="font-size: 14.5px; line-height: 1.8;">Menjadi jembatan digital yang menghubungkan wisatawan dengan Faskes terdekat.</p>
                </div>

                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0 px-3">
                    <div class="mb-4"><i class="fas fa-laptop-code fa-4x text-hnb-orange"></i></div>
                    <h5 class="text-white font-weight-bold mb-3">Sistem Terpadu</h5>
                    <p class="text-white-50 mb-0" style="font-size: 14.5px; line-height: 1.8;">Smart Mapping yang menampilkan rute darurat tercepat secara <i>real-time</i>.</p>
                </div>

                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0 px-3">
                    <div class="mb-4"><i class="fas fa-globe fa-4x text-hnb-orange"></i></div>
                    <h5 class="text-white font-weight-bold mb-3">Solusi Instan</h5>
                    <p class="text-white-50 mb-0" style="font-size: 14.5px; line-height: 1.8;">Temukan UGD 24 Jam atau faskes BPJS dalam hitungan detik tanpa panik.</p>
                </div>

                <div class="col-lg-3 col-md-6 px-3">
                    <div class="mb-4"><i class="fas fa-heart fa-4x text-hnb-orange"></i></div>
                    <h5 class="text-white font-weight-bold mb-3">Made with Love</h5>
                    <p class="text-white-50 mb-0" style="font-size: 14.5px; line-height: 1.8;">Mendukung pariwisata berstandar keselamatan tinggi dengan penuh dedikasi.</p>
                </div>
            </div>

        </div>
    </section>

    <section id="panduan" class="py-5 section-scroll">
        <div class="container px-4 my-5 d-flex flex-column align-items-center">

            <div class="text-center mb-5 pb-2">
                <h2 class="teks-judul font-weight-bold mb-3 text-white">Buku Panduan</h2>
                <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                <p class="teks-subjudul mx-auto text-white-50">Langkah-langkah menggunakan aplikasi WanderMed.</p>
            </div>

            <div class="w-100" style="max-width: 800px;">
                <div class="glass-premier radius-hnb p-5 text-center d-flex flex-column justify-content-center align-items-center shadow-sm" style="min-height: 400px; border: 2px dashed rgba(255,255,255,0.2);">
                    <i class="fas fa-tools fa-3x text-white-50 mb-4"></i>
                    <h5 class="text-white font-weight-bold mb-2">Area Konten Panduan</h5>
                    <p class="text-white-50 mb-0">Tutorial penggunaan dan infografis akan segera ditambahkan di sini secara lengkap.</p>
                </div>
            </div>

        </div>
    </section>

    <section id="mitra" class="py-5 section-scroll">
        <div class="container px-4 my-5">

            <div class="text-center mb-5">
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
                        <a href="/login" class="btn btn-light text-hnb-navy radius-hnb px-5 py-3 font-weight-bold" style="font-size: 15px; min-width: 200px;">Login <i class="fas fa-sign-in-alt ml-1 text-hnb-orange"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="row mt-5 pt-4">
        <div class="col-12 px-3">
            <div class="glass-premier radius-hnb p-4 p-md-5 text-center shadow-sm border-0"
                 style="background: rgba(255, 122, 0, 0.03) !important; border: 1px dashed rgba(255, 122, 0, 0.2) !important;">

                <h5 class="text-hnb-orange font-weight-bold mb-3">Special Thanks</h5>
                <p class="text-white-50 mx-auto mb-4" style="max-width: 700px; line-height: 1.8;">
                    Terima kasih telah mempercayakan perjalanan Anda kepada <strong>WanderMed</strong>. Proyek ini adalah dedikasi kami untuk menciptakan ekosistem pariwisata Indonesia yang lebih aman, sehat, dan terintegrasi bagi setiap petualang.
                </p>

                <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 20px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-heart text-hnb-orange mr-2"></i>
                        <span class="text-white small font-weight-bold opacity-75">Developed by Hear & Build Studio</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-hnb-navy pt-5 pb-4 mt-5">
        @include('theme.footer')
    </section>

@endsection
