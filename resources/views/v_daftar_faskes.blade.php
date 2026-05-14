@extends('theme.wisatawan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="{{ asset('css/daftar-faskes.css') }}">
@endpush


@section('content')
    @include('theme.navbar')

    {{-- Modal Panduan Koordinat --}}
    <div class="modal fade" id="modalKoordinat" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 580px;">
            <div class="modal-content" style="background: #1a2035; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; color: white;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding: 20px 24px;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-map-marker-alt text-hnb-orange mr-2"></i>Cara Mendapatkan Koordinat</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" style="padding: 20px 24px;">
                    <div style="margin-bottom: 16px;">
                        <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:10px; background: rgba(255,165,0,0.08); border-radius:10px; padding:12px;">
                            <div style="background: var(--hnb-orange,#ff6b35); border-radius:50%; width:28px; height:28px; min-width:28px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">1</div>
                            <p class="mb-0 small">Buka <strong>Google Maps</strong> di browser atau HP Anda</p>
                        </div>
                        <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:10px; background: rgba(255,165,0,0.08); border-radius:10px; padding:12px;">
                            <div style="background: var(--hnb-orange,#ff6b35); border-radius:50%; width:28px; height:28px; min-width:28px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">2</div>
                            <p class="mb-0 small">Cari lokasi faskes Anda, lalu <strong>klik kanan</strong> tepat di titiknya</p>
                        </div>
                        <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:10px; background: rgba(255,165,0,0.08); border-radius:10px; padding:12px;">
                            <div style="background: var(--hnb-orange,#ff6b35); border-radius:50%; width:28px; height:28px; min-width:28px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">3</div>
                            <p class="mb-0 small">Dua angka muncul di bagian atas menu. <strong>Klik angka tersebut</strong> untuk menyalin</p>
                        </div>
                        <div style="display:flex; align-items:flex-start; gap:12px; background: rgba(255,165,0,0.08); border-radius:10px; padding:12px;">
                            <div style="background: var(--hnb-orange,#ff6b35); border-radius:50%; width:28px; height:28px; min-width:28px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">4</div>
                            <p class="mb-0 small">Angka pertama = <strong>Latitude</strong>, angka kedua = <strong>Longitude</strong></p>
                        </div>
                    </div>
                    <div style="background: rgba(78,115,223,0.12); border: 1px solid rgba(78,115,223,0.3); border-radius: 10px; padding: 12px; font-size: 12px; margin-bottom:16px;">
                        <i class="fas fa-info-circle text-primary mr-1"></i>
                        <strong>Contoh RSUD Subang:</strong> <code style="color:#f6c23e;">-6.571800, 107.760000</code><br>
                        <small class="text-white-50">→ Latitude: <strong>-6.5718</strong> &nbsp;|&nbsp; Longitude: <strong>107.7600</strong></small>
                    </div>

                    <p class="small font-weight-bold mb-2"><i class="fas fa-crosshairs text-hnb-orange mr-1"></i> Atau klik langsung di peta mini ini:</p>
                    <div id="miniMap"></div>
                    <div style="background: rgba(0,0,0,0.3); padding: 10px 14px; border-radius: 0 0 10px 10px; font-size: 12px; display:flex; gap:10px; align-items:center; justify-content:space-between;">
                        <span>Lat: <strong id="previewLat" style="color:#f6c23e;">-</strong></span>
                        <span>Lng: <strong id="previewLng" style="color:#f6c23e;">-</strong></span>
                        <button type="button" id="btnApplyCoord" class="btn btn-sm btn-hnb-orange py-1 px-3 radius-hnb" onclick="applyPickedCoord()" disabled>
                            <i class="fas fa-check mr-1"></i> Gunakan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="hero-slanted" style="min-height: 100vh; display: flex; align-items: center; padding-top: 50px; padding-bottom: 50px;">
        <div class="container px-4" style="position: relative; z-index: 5;">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7 animate-fade-up">

                    @if($errors->any())
                    <div class="alert alert-danger mb-4" style="border-radius: 10px; background: rgba(231,74,59,0.15); border: 1px solid rgba(231,74,59,0.4); color: #ff6b6b; font-size: 13px;">
                        <i class="fas fa-exclamation-circle mr-2"></i> <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold text-white teks-judul pb-1" id="formTitle">Profil Fasilitas Kesehatan</h3>
                        <p class="text-white-50 teks-subjudul" style="font-size: 0.95rem;" id="formSubtitle">Langkah 1 dari 5</p>
                        <div class="progress mt-3 radius-hnb shadow-sm" style="height: 6px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar transition-all" id="formProgress" role="progressbar" style="width: 20%; background-color: var(--hnb-orange);"></div>
                        </div>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden" style="border: 1px solid rgba(255,255,255,0.1); border-top: 4px solid var(--hnb-orange); border-radius: 16px;">
                        <div class="card-body p-4 p-md-5">
                            <form id="wizardForm" action="{{ route('register.mitra') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="jenis_mitra" value="faskes">
                                <input type="hidden" id="hiddenLat" name="latitude" value="">
                                <input type="hidden" id="hiddenLng" name="longitude" value="">

                                {{-- STEP 1: Info Dasar --}}
                                <div id="step1" class="step-content">
                                    <div class="row">
                                        <div class="col-md-7 mb-4">
                                            <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Nama Faskes</label>
                                            <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-hospital-alt"></i></span>
                                                </div>
                                                <input type="text" name="nama_faskes" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="Contoh: RSUD Subang" required maxlength="100" style="font-size: 1.05rem;">
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Kategori</label>
                                            <select name="jenis_faskes" class="form-control form-control-dark radius-hnb px-3 input-dark" style="height:50px;" required>
                                                <option value="" disabled selected>Pilih Kategori...</option>
                                                <option value="Rumah Sakit">Rumah Sakit</option>
                                                <option value="Klinik">Klinik Umum</option>
                                                <option value="Puskesmas">Puskesmas</option>
                                                <option value="Apotek">Apotek</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Informasi Layanan Utama</label>
                                        <textarea name="pengumuman" class="form-control form-control-dark radius-hnb p-3 input-dark" rows="3" placeholder="Contoh: Melayani poli gigi, bedah ringan, dll..." required maxlength="200"></textarea>
                                    </div>
                                </div>

                                {{-- STEP 2: Lokasi & Peta --}}
                                <div id="step2" class="step-content d-none">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Alamat Lengkap</label>
                                        <textarea name="alamat" class="form-control form-control-dark radius-hnb p-3 input-dark" rows="2" placeholder="Jl. Raya Kesehatan No. 99..." required maxlength="200"></textarea>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75 mb-0 mr-2">Koordinat Lokasi di Peta</label>
                                        <button type="button" class="btn btn-sm btn-link text-hnb-orange p-0"
                                            data-toggle="modal" data-target="#modalKoordinat" style="font-size:12px; text-decoration:none;">
                                            <i class="fas fa-question-circle mr-1"></i>Cara mendapatkan koordinat?
                                        </button>
                                    </div>
                                    <div class="p-3 mb-3 d-flex" style="background: rgba(78,115,223,0.1); border: 1px solid rgba(78,115,223,0.2); border-radius:8px;">
                                        <i class="fas fa-info-circle text-primary mr-2 mt-1"></i>
                                        <p class="text-white-50 small mb-0">Klik <strong class="text-white">"Pilih di Peta"</strong> untuk memilih lokasi secara visual, atau isi manual.</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Latitude (Lintang)</label>
                                            <input type="number" step="any" id="inputLat" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="-6.5718" oninput="syncCoord()">
                                            <small class="text-white-50 ml-1">Contoh: -6.571800</small>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Longitude (Bujur)</label>
                                            <input type="number" step="any" id="inputLng" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="107.7600" oninput="syncCoord()">
                                            <small class="text-white-50 ml-1">Contoh: 107.760000</small>
                                        </div>
                                        <div class="col-md-2 mb-3 d-flex align-items-center">
                                            <button type="button" class="btn btn-outline-hnb-orange radius-hnb py-3 w-100 font-weight-bold"
                                                data-toggle="modal" data-target="#modalKoordinat" style="font-size:11px;">
                                                <i class="fas fa-map-pin d-block fa-lg mb-1"></i>Pilih Peta
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 3: Kontak --}}
                                <div id="step3" class="step-content d-none">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Telepon / Kontak Darurat</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0 text-white font-weight-bold" style="border-color: rgba(255,255,255,0.1) !important;">+62</span>
                                            </div>
                                            <input type="tel" name="no_telp" class="form-control form-control-dark border-left-0 radius-hnb py-4 px-3 input-dark" placeholder="8123xxx" required maxlength="15">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Terima BPJS?</label>
                                            <select name="dukungan_bpjs" class="form-control form-control-dark radius-hnb px-3 input-dark" style="height:50px;" required>
                                                <option value="1">Ya, Menerima</option>
                                                <option value="0">Tidak (Umum)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Layanan UGD</label>
                                            <select name="layanan_ugd" class="form-control form-control-dark radius-hnb px-3 input-dark" style="height:50px;" required>
                                                <option value="24 Jam">Buka 24 Jam</option>
                                                <option value="Terbatas">Tidak Ada (Klinik Biasa)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 4: Dokumen & PJ --}}
                                <div id="step4" class="step-content d-none">
                                    <div class="upload-box-premium mb-4">
                                        <i class="fas fa-file-medical fa-2x text-hnb-orange mb-2"></i>
                                        <p class="text-white small font-weight-bold mb-1">Unggah Surat Izin Operasional / SK</p>
                                        <small class="text-white-50 d-block mb-2">Format: PDF, JPG, PNG — Maks 5MB</small>
                                        <input type="file" name="dokumen_izin" class="form-control-file small text-white-50 mx-auto" accept=".pdf,.jpg,.jpeg,.png">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Nama Penanggung Jawab / Pimpinan</label>
                                        <input type="text" name="nama_penanggung_jawab" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Contoh: dr. Andi Susanto" required maxlength="100">
                                    </div>
                                </div>

                                {{-- STEP 5: Akun --}}
                                <div id="step5" class="step-content d-none">
                                    <div class="p-3 mb-4 d-flex" style="background: rgba(255,107,53,0.08); border: 1px solid rgba(255,107,53,0.2); border-radius:8px;">
                                        <i class="fas fa-info-circle text-hnb-orange mr-3 mt-1"></i>
                                        <p class="text-white-50 small mb-0">Akun ini digunakan untuk Login Dashboard & memperbarui status faskes secara <strong class="text-white">Real-Time</strong>.</p>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Email Akun Login</label>
                                        <input type="email" name="email" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="klinik@email.com" required maxlength="100">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Sandi Akses</label>
                                        <div class="pass-group-wizard">
                                            <input type="password" name="password" id="inputPass" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Minimal 8 karakter..." required maxlength="50">
                                            <i class="fas fa-eye btn-toggle-pass" id="btnTogglePass"></i>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Konfirmasi Sandi</label>
                                        <div class="pass-group-wizard">
                                            <input type="password" name="password_confirmation" class="form-control form-control-dark radius-hnb py-4 px-3 input-dark" placeholder="Ketik ulang sandi..." required maxlength="50">
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 6: Sukses --}}
                                <div id="step6" class="step-content d-none text-center py-4">
                                    <div class="icon-kesehatan mx-auto mb-4 d-flex align-items-center justify-content-center animasi-jantung"
                                        style="width:100px; height:100px; border-color:#28a745; background:rgba(40,167,69,0.1);">
                                        <i class="fas fa-check fa-3x text-success"></i>
                                    </div>
                                    <h4 class="font-weight-bold text-white mb-2">Pendaftaran Faskes Terkirim!</h4>
                                    <p class="text-white-50 small mb-0">Tim Admin WanderMed akan meninjau. Akun aktif setelah diverifikasi.</p>
                                </div>

                                <div class="mt-5 d-flex justify-content-between" id="navButtons">
                                    <button type="button" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold" id="btnPrev" style="min-width:140px;">
                                        <i class="fas fa-chevron-left mr-2"></i> Kembali
                                    </button>
                                    <button type="button" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold" id="btnNext" style="min-width:140px;">
                                        Lanjut <i class="fas fa-chevron-right ml-2"></i>
                                    </button>
                                </div>
                                <div class="mt-5 d-none flex-column" id="navFinish">
                                    <a href="/login" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-100 mb-3">Login ke Dashboard <i class="fas fa-sign-in-alt ml-2"></i></a>
                                    <a href="/" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold w-100">Kembali ke Beranda</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                    </div>
                    <div class="text-center mt-4">
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
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/wizard-faskes.js') }}"></script>
<script>
(function() {
    var pickedLat = null, pickedLng = null;
    var miniMapInstance = null, pickedMarker = null;

    window.syncCoord = function() {
        var lat = document.getElementById('inputLat').value;
        var lng = document.getElementById('inputLng').value;
        document.getElementById('hiddenLat').value = lat;
        document.getElementById('hiddenLng').value = lng;
    };

    // Init mini-map setelah modal terbuka penuh (timeout 300ms agar animasi Bootstrap selesai)
    $('#modalKoordinat').on('shown.bs.modal', function() {
        setTimeout(function() {
            if (!miniMapInstance) {
                miniMapInstance = L.map('miniMap').setView([-6.5718, 107.7600], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(miniMapInstance);

                miniMapInstance.on('click', function(e) {
                    pickedLat = e.latlng.lat.toFixed(6);
                    pickedLng = e.latlng.lng.toFixed(6);

                    if (pickedMarker) miniMapInstance.removeLayer(pickedMarker);
                    pickedMarker = L.marker(e.latlng)
                        .addTo(miniMapInstance)
                        .bindPopup('<b>📍 Lokasi Dipilih</b><br>Lat: ' + pickedLat + '<br>Lng: ' + pickedLng)
                        .openPopup();

                    document.getElementById('previewLat').textContent = pickedLat;
                    document.getElementById('previewLng').textContent = pickedLng;
                    document.getElementById('btnApplyCoord').disabled = false;
                });
            } else {
                // Penting: panggil invalidateSize agar tile ter-render ulang
                miniMapInstance.invalidateSize();
            }
        }, 300);
    });

    window.applyPickedCoord = function() {
        if (!pickedLat || !pickedLng) return;
        document.getElementById('inputLat').value = pickedLat;
        document.getElementById('inputLng').value = pickedLng;
        document.getElementById('hiddenLat').value = pickedLat;
        document.getElementById('hiddenLng').value = pickedLng;
        $('#modalKoordinat').modal('hide');
    };
})();
</script>
@endpush
