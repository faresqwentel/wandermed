@extends('theme.wisatawan')

@section('content')
    <style>
        .input-dark::placeholder { color: rgba(255, 255, 255, 0.5); }
        .input-dark:focus { background-color: rgba(255,255,255,0.1) !important; color: #ffffff !important; border-color: #FF7A00 !important; box-shadow: none !important; }
        .form-control-dark { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; }

        body.light-mode .form-control-dark { background: #ffffff !important; border-color: #dee2e6 !important; color: #112240 !important; }
        body.light-mode .form-control-dark::placeholder { color: #a0aec0 !important; }
    </style>

    <div class="bg-hnb-navy d-flex flex-column p-4 position-relative" style="min-height: 100vh;">

        <div class="mt-5 mb-4 text-center">
            <h4 class="font-weight-bold text-white mb-1" id="formTitle">Profil Singkat Pariwisata</h4>
            <p class="text-white-50 small" id="formSubtitle">Langkah 1 dari 4</p>
        </div>

        <div class="progress mb-4 radius-hnb" style="height: 8px; background: rgba(255,255,255,0.1);">
            <div class="progress-bar bg-hnb-orange" id="formProgress" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <form id="wizardForm" action="#" method="POST" class="d-flex flex-column flex-grow-1">

            <div id="step1" class="step-content">
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Nama Wisata</label>
                    <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: Pantai Kuta..." required>
                </div>
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Kategori</label>
                    <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                        <option value="" disabled selected class="text-dark">Pilih Kategori...</option>
                        <option value="Alam" class="text-dark">Wisata Alam</option>
                        <option value="Budaya" class="text-dark">Wisata Budaya / Sejarah</option>
                        <option value="Buatan" class="text-dark">Wisata Buatan / Taman Bermain</option>
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label class="text-white-50 small font-weight-bold ml-1">Deskripsi Singkat</label>
                    <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="4" placeholder="Ceritakan keunikan tempat wisata ini..." required></textarea>
                </div>
            </div>

            <div id="step2" class="step-content d-none">
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Alamat Lengkap</label>
                    <textarea class="form-control form-control-dark radius-hnb p-3 input-dark" rows="3" placeholder="Jl. Raya Kuta No. 1..." required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Titik Koordinat (Map)</label>
                    <button type="button" class="btn btn-outline-hnb-orange w-100 radius-hnb py-3 font-weight-bold">
                        <i class="fas fa-map-marker-alt mr-2"></i> Pilih dari Peta
                    </button>
                </div>
                <div class="form-group mb-4">
                    <label class="text-white-50 small font-weight-bold ml-1">Penjelasan Patokan</label>
                    <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: Depan tugu patung kuda...">
                </div>
            </div>

            <div id="step3" class="step-content d-none">

                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Nomor Telepon Darurat / Info</label>

                    <div class="d-flex align-items-center form-control-dark radius-hnb px-3">
                        <span class="font-weight-bold text-white mr-1">+62</span>
                        <input type="number" class="form-control border-0 bg-transparent input-dark text-white py-4 px-2 shadow-none" placeholder="8123456789" required>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="text-white-50 small font-weight-bold ml-1">Jam Operasional</label>
                    <input type="text" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: 08:00 - 17:00 (Setiap Hari)" required>
                </div>
                <div class="form-group mb-4">
                    <label class="text-white-50 small font-weight-bold ml-1">Status Operasional Saat Ini</label>
                    <select class="form-control form-control-dark radius-hnb px-3 input-dark" style="height: 50px;" required>
                        <option value="Buka" class="text-dark">Buka / Aktif</option>
                        <option value="Tutup Sementara" class="text-dark">Tutup Sementara (Renovasi/Libur)</option>
                    </select>
                </div>
            </div>

            <div id="step4" class="step-content d-none">

                <div class="form-group mb-4 text-center p-4 radius-hnb" style="border: 2px dashed rgba(255,255,255,0.2); background: rgba(255,255,255,0.02);">
                    <i class="fas fa-cloud-upload-alt fa-3x text-white-50 mb-3"></i>
                    <p class="text-white small mb-3">Unggah foto utama tempat wisata Anda di sini.</p>
                    <input type="file" class="form-control-file text-white-50 mx-auto" style="max-width: 250px;">
                </div>

                <div class="form-group mb-4">
                    <label class="text-white-50 small font-weight-bold ml-1">Email Penanggung Jawab</label>
                    <input type="email" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: admin@wisata.com" required>
                    <small class="text-white-50 ml-1 mt-2 d-block" style="font-size: 11px; line-height: 1.5;">
                        <i class="fas fa-info-circle mr-1 text-hnb-orange"></i>
                        Penting: Email ini digunakan Admin WanderMed untuk mengirimkan status persetujuan, hasil revisi, dan info masuknya destinasi ke Peta Digital.
                    </small>
                </div>

            </div>

            <div id="step5" class="step-content d-none text-center py-5">
                <div class="d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 100px; height: 100px; background: rgba(25, 135, 84, 0.2); border-radius: 50%; border: 3px solid #198754;">
                    <i class="fas fa-check fa-3x text-success"></i>
                </div>
                <h4 class="font-weight-bold text-white mb-2">Pariwisata Terdaftar!</h4>
                <p class="text-white-50 small mb-5">Terima kasih. Destinasi Anda kini terhubung dengan jaringan keselamatan WanderMed.</p>
            </div>

            <div class="mt-auto pt-3 d-flex justify-content-between" id="navButtons">
                <button type="button" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold w-45" id="btnPrev">Kembali</button>
                <button type="button" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-45" id="btnNext">Lanjut</button>
            </div>

            <div class="mt-auto pt-3 d-none flex-column" id="navFinish">
                <a href="/" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-100 mb-2">Kembali ke Beranda</a>
                <a href="/daftar/pariwisata" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold w-100">Daftar Lagi</a>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/wizard-pariwisata.js') }}"></script>

@endsection
