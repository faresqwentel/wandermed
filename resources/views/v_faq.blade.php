@extends('theme.wisatawan')

@section('title', 'FAQ Kemitraan - WanderMed')

@section('content')
    @include('theme.navbar')

    <section class="hero-slanted" style="min-height: 40vh; display: flex; align-items: center; padding-top: 120px; padding-bottom: 100px;">
        <div class="container px-4" style="position: relative; z-index: 5;">
            <div class="text-center animate-fade-up">
                <h2 class="font-weight-bold text-white teks-judul">Frequently Asked Questions</h2>
                <div style="width: 50px; height: 3px; background-color: var(--hnb-orange); margin: 15px auto; border-radius: 2px;"></div>
                <p class="text-white-50 teks-subjudul mx-auto">Pertanyaan umum seputar pendaftaran Mitra Faskes & Pariwisata.</p>
            </div>
        </div>
    </section>

    <section class="py-5" style="margin-top: -50px; position: relative; z-index: 10;">
        <div class="container px-4">
            <div class="row justify-content-center animate-fade-up delay-1">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">

                        {{-- FAQ 1 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Apakah mendaftar sebagai mitra WanderMed berbayar?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    <strong>Tidak.</strong> Pendaftaran keanggotaan sebagai mitra Fasilitas Kesehatan maupun Destinasi Pariwisata di ekosistem WanderMed adalah 100% gratis. Tidak ada biaya langganan bulanan maupun potongan biaya untuk pendaftaran awal.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 2 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Apa syarat utama untuk mendaftar sebagai Mitra Faskes?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    Anda wajib menyiapkan:
                                    <ul class="mt-2 mb-0">
                                        <li>Izin Operasional / Surat Izin Praktik fasilitas kesehatan yang masih berlaku.</li>
                                        <li>Identitas penanggung jawab yang sah (KTP).</li>
                                        <li>Koordinat lokasi Faskes di Google Maps.</li>
                                    </ul>
                                    Semua data pendaftaran nantinya akan ditinjau secara manual oleh Admin untuk mencegah penipuan faskes fiktif.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 3 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Bagaimana kalau klinik saya tidak beroperasi 24 Jam?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    Tidak masalah! Kami mengizinkan semua jenis fasilitas kesehatan (buka 24 jam maupun terbatas). Di *Dashboard Mitra*, Anda disediakan tombol saklar (**Toggle Status Operasional**) yang dapat Anda ubah kapanpun menjadi 'Buka' atau 'Tutup'. Perubahan ini akan langsung diperbarui (*real-time*) di peta para wisatawan.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 4 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingFour">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Apakah saya bisa memperbarui jadwal dokter praktik sendiri?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    Tentu. Melalui <i>Dashboard Mitra Faskes</i>, Anda bisa mengelola daftar fasilitas (seperti UGD, ambulans, apotek) serta jadwal detail dokter yang sedang praktik. Wisatawan bahkan dapat melihat tanda waktu pembaruan (<i>Last Updated</i>) kapan Anda terakhir mengubah jadwal tersebut.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 5 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingFive">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Bagaimana proses persetujuan (approval) setelah mendaftar?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    Setelah Anda mengisi formulir pendaftaran, tim Admin kami akan memverifikasi data dan kelengkapan dokumen Anda. Proses ini biasanya memakan waktu maksimal 1x24 jam kerja. Jika seluruh data valid, akun Anda akan diaktifkan secara otomatis.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 6 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingSix">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Bagaimana cara mendaftarkan Destinasi Pariwisata ke WanderMed?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    Pilih opsi "Destinasi Pariwisata" pada halaman Pilihan Pendaftaran. Anda hanya perlu menyertakan nama tempat wisata, deskripsi singkat, alamat lengkap, dan menitik koordinat lintang/bujur pada peta yang disediakan agar dapat terintegrasi dengan akurat.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 7 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingSeven">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Apakah wisatawan dapat memberikan ulasan (review) untuk fasilitas saya?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    Ya, WanderMed memiliki sistem ulasan interaktif. Wisatawan yang sudah terdaftar dapat memberikan <i>rating</i> bintang dan komentar berdasarkan pengalaman kunjungan mereka. Ini sangat berguna untuk membangun reputasi dan kredibilitas fasilitas Anda.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 8 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingEight">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Bisakah saya mengubah titik koordinat lokasi di kemudian hari?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    Tentu bisa. Anda dapat memperbaruinya secara mandiri kapanpun tanpa harus menghubungi admin. Cukup masuk ke <i>Dashboard Mitra</i>, buka menu Profil, dan perbarui <i>Latitude</i> serta <i>Longitude</i>. Perubahan tersebut akan langsung disinkronisasi pada Peta Utama.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 9 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingNine">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Apakah WanderMed terhubung langsung dengan layanan ambulans?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    WanderMed berfungsi sebagai pusat informasi respons cepat. Kami menyediakan navigasi rute darurat dan nomor kontak faskes/ambulans di panel detail peta, sehingga wisatawan bisa menelepon fasilitas Anda hanya dengan satu klik dari <i>smartphone</i> mereka.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 10 --}}
                        <div class="card glass-premier border-0 shadow-sm mb-3">
                            <div class="card-header border-0 bg-transparent" id="headingTen">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-white font-weight-bold text-decoration-none collapsed" type="button" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen" style="font-size: 16px;">
                                        <i class="fas fa-question-circle text-hnb-orange mr-2"></i> Bagaimana keamanan data operasional fasilitas kami?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#faqAccordion">
                                <div class="card-body text-white-50 pt-0 pb-4 px-4" style="font-size: 14.5px; line-height: 1.8;">
                                    Kami menerapkan standar perlindungan keamanan yang ketat. Seluruh kata sandi dienkripsi, dan pengaturan operasional hanya dapat diubah oleh Anda yang terautentikasi (login). Data yang dipublikasikan di peta hanyalah informasi publik yang memang ditujukan untuk kebutuhan wisatawan.
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="text-center mt-5">
                        <a href="/" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold shadow-sm mr-2 mb-2">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                        </a>
                        <a href="/daftar" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold shadow-sm mb-2">
                            Mulai Pendaftaran Mitra <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="bg-hnb-navy pt-5 pb-4 mt-5">
        @include('theme.footer')
    </section>
@endsection
