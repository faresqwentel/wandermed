@extends('theme.wisatawan')

@section('content')
    <div class="bg-hnb-navy d-flex flex-column p-4 position-relative" style="min-height: 100vh;">

        <div class="mt-5 mb-4 text-center">
            <h4 class="font-weight-bold text-white mb-1" id="formTitle">Profil Fasilitas Kesehatan</h4>
            <p class="text-white-50 small" id="formSubtitle">Langkah 1 dari 5</p>
        </div>

        <div class="progress mb-4 radius-hnb" style="height: 8px; background: rgba(255,255,255,0.1);">
            <div class="progress-bar bg-hnb-orange" id="formProgress" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <form id="wizardForm" action="#" method="POST" class="d-flex flex-column flex-grow-1">

            <div id="step1" class="step-content">
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Nama Fasilitas Kesehatan</label>
                    <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: RSUD Subang..." required>
                </div>
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Kategori Faskes</label>
                    <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                        <option value="" disabled selected class="text-dark">Pilih Kategori...</option>
                        <option value="Rumah Sakit" class="text-dark">Rumah Sakit</option>
                        <option value="Klinik" class="text-dark">Klinik Umum / Spesialis</option>
                        <option value="Puskesmas" class="text-dark">Puskesmas</option>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label class="text-white-50 small font-weight-bold ml-1">Deskripsi & Layanan Utama</label>
                    <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="3" placeholder="Contoh: Melayani poli gigi, bedah ringan, dll..." required></textarea>
                </div>
            </div>

            <div id="step2" class="step-content d-none">
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Alamat Lengkap</label>
                    <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="3" placeholder="Jl. Raya Kesehatan No. 99..." required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Titik Koordinat (Map)</label>
                    <button type="button" class="btn btn-outline-hnb-orange w-100 radius-hnb py-3 font-weight-bold">
                        <i class="fas fa-map-marker-alt mr-2"></i> Pilih dari Peta
                    </button>
                </div>
                <div class="form-group mb-4">
                    <label class="text-white-50 small font-weight-bold ml-1">Penjelasan Patokan</label>
                    <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: Sebelah alun-alun kota...">
                </div>
            </div>

            <div id="step3" class="step-content d-none">
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Nomor Telepon Darurat / Ambulans</label>

                    <div class="d-flex align-items-center form-control-dark radius-hnb px-3">
                        <span class="font-weight-bold text-white mr-1">+62</span>
                        <input type="tel" class="form-control border-0 bg-transparent input-dark text-white py-4 px-2 shadow-none" placeholder="8123456789" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6 pr-2">
                        <label class="text-white-50 small font-weight-bold ml-1">Menerima BPJS?</label>
                        <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                            <option value="Ya" class="text-dark">Ya, Menerima</option>
                            <option value="Tidak" class="text-dark">Tidak (Umum)</option>
                        </select>
                    </div>
                    <div class="col-6 pl-2">
                        <label class="text-white-50 small font-weight-bold ml-1">Layanan UGD</label>
                        <select id="selectUGD" class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                            <option value="24 Jam" class="text-dark">Buka 24 Jam</option>
                            <option value="Terbatas" class="text-dark">Jam Terbatas</option>
                        </select>
                    </div>
                </div>

                <div id="ugdTimeForm" class="row mb-4 d-none p-3 radius-hnb mx-0" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.2);">
                    <div class="col-12 mb-2 px-0">
                        <span class="text-hnb-orange small font-weight-bold"><i class="far fa-clock mr-1"></i> Atur Jam UGD</span>
                    </div>
                    <div class="col-6 pl-0 pr-2">
                        <label class="text-white-50 small ml-1">Jam Buka</label>
                        <input type="time" class="form-control form-control-dark radius-hnb input-dark px-2" style="height: 45px;">
                    </div>
                    <div class="col-6 pr-0 pl-2">
                        <label class="text-white-50 small ml-1">Jam Tutup</label>
                        <input type="time" class="form-control form-control-dark radius-hnb input-dark px-2" style="height: 45px;">
                    </div>
                </div>
            </div>

            <div id="step4" class="step-content d-none">
                <div class="form-group mb-4 text-center p-4 radius-hnb box-upload">
                    <i class="fas fa-hospital-alt fa-3x text-white-50 mb-3"></i>
                    <h6 class="text-white font-weight-bold mb-1">Unggah Foto Faskes</h6>
                    <p class="text-white-50 small mb-3" style="font-size: 11px;">Tampak depan bangunan (JPG/PNG)</p>
                    <input type="file" class="form-control-file text-white-50 mx-auto" style="max-width: 250px;" required>
                </div>

                <hr style="border-color: rgba(255,255,255,0.1);" class="my-4">

                <div class="form-group mb-4">
                    <label class="text-white-50 small font-weight-bold ml-1">Email Resmi / Admin</label>
                    <div class="d-flex align-items-center form-control-dark radius-hnb px-3 mb-3">
                        <i class="fas fa-envelope text-white-50 mr-2"></i>
                        <input type="email" class="form-control border-0 bg-transparent input-dark text-white py-4 px-2 shadow-none" placeholder="Contoh: admin@rsud.com" required>
                    </div>
                </div>
            </div>

            <div id="step5" class="step-content d-none">
                <div class="d-flex align-items-start p-3 radius-hnb alert-hnb mb-4">
                    <i class="fas fa-user-shield text-hnb-orange mt-1 mr-3" style="font-size: 16px;"></i>
                    <p class="text-white-50 mb-0" style="font-size: 12px; line-height: 1.6;">
                        <strong class="text-hnb-orange">Akun Dashboard Faskes:</strong> Akun ini digunakan untuk Login dan memperbarui status faskes Anda (seperti Tutup Darurat/Kapasitas Penuh) secara <strong class="text-white">Real-Time</strong>.
                    </p>
                </div>

                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Username (ID Faskes)</label>
                    <div class="d-flex align-items-center form-control-dark radius-hnb px-3">
                        <i class="fas fa-user text-white-50 mr-2"></i>
                        <input type="text" class="form-control border-0 bg-transparent input-dark text-white py-4 px-2 shadow-none" placeholder="Contoh: klinik_sehat123" required>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="text-white-50 small font-weight-bold ml-1">Buat Kata Sandi</label>
                    <div class="d-flex align-items-center form-control-dark radius-hnb px-3">
                        <i class="fas fa-lock text-white-50 mr-2"></i>
                        <input type="password" id="inputPass" class="form-control border-0 bg-transparent input-dark text-white py-4 px-2 shadow-none" placeholder="Minimal 8 karakter..." required>
                        <i class="fas fa-eye text-white-50 ml-2" id="btnTogglePass" style="cursor: pointer;"></i>
                    </div>
                </div>
            </div>

            <div id="step6" class="step-content d-none text-center py-5">
                <div class="d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 100px; height: 100px; background: rgba(25, 135, 84, 0.2); border-radius: 50%; border: 3px solid #198754;">
                    <i class="fas fa-check fa-3x text-success"></i>
                </div>
                <h4 class="font-weight-bold text-white mb-2">Akun Berhasil Dibuat!</h4>
                <p class="text-white-50 small mb-5">Terima kasih. Faskes Anda sedang diverifikasi. Anda dapat login ke Dashboard setelah mendapat email persetujuan.</p>
            </div>

            <div class="mt-auto pt-3 d-flex justify-content-between" id="navButtons">
                <button type="button" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold w-45" id="btnPrev">Kembali</button>
                <button type="button" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-45" id="btnNext">Lanjut</button>
            </div>

            <div class="mt-auto pt-3 d-none flex-column" id="navFinish">
                <a href="/login" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-100 mb-2">Login ke Dashboard Faskes <i class="fas fa-sign-in-alt ml-1"></i></a>
                <a href="/" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold w-100">Kembali ke Beranda</a>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/wizard-faskes.js') }}"></script>
@endsection
