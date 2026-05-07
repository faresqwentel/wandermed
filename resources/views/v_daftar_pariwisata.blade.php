@extends('theme.wisatawan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="{{ asset('css/daftar-pariwisata.css') }}">
@endpush


@section('content')
    @include('theme.navbar')

    {{-- Modal Panduan Koordinat --}}
    <div class="modal fade" id="modalKoordinatPariwisata" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 580px;">
            <div class="modal-content" style="background: #1a2035; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; color: white;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding: 20px 24px;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-map-marker-alt text-hnb-orange mr-2"></i>Cara Mendapatkan Koordinat</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" style="padding: 20px 24px;">
                    <div style="margin-bottom: 16px;">
                        <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:10px; background: rgba(255,165,0,0.08); border-radius:10px; padding:12px;">
                            <div style="background:var(--hnb-orange,#ff6b35); border-radius:50%; width:28px; height:28px; min-width:28px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">1</div>
                            <p class="mb-0 small">Buka <strong>Google Maps</strong> di browser atau HP</p>
                        </div>
                        <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:10px; background: rgba(255,165,0,0.08); border-radius:10px; padding:12px;">
                            <div style="background:var(--hnb-orange,#ff6b35); border-radius:50%; width:28px; height:28px; min-width:28px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">2</div>
                            <p class="mb-0 small">Cari lokasi destinasi wisata, lalu <strong>klik kanan</strong> tepat di titiknya</p>
                        </div>
                        <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:10px; background: rgba(255,165,0,0.08); border-radius:10px; padding:12px;">
                            <div style="background:var(--hnb-orange,#ff6b35); border-radius:50%; width:28px; height:28px; min-width:28px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">3</div>
                            <p class="mb-0 small">Dua angka muncul di bagian atas menu. <strong>Klik angka tersebut</strong> untuk menyalin</p>
                        </div>
                        <div style="display:flex; align-items:flex-start; gap:12px; background: rgba(255,165,0,0.08); border-radius:10px; padding:12px;">
                            <div style="background:var(--hnb-orange,#ff6b35); border-radius:50%; width:28px; height:28px; min-width:28px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px;">4</div>
                            <p class="mb-0 small">Angka pertama = <strong>Latitude</strong>, angka kedua = <strong>Longitude</strong></p>
                        </div>
                    </div>
                    <div style="background:rgba(78,115,223,0.12); border:1px solid rgba(78,115,223,0.3); border-radius:10px; padding:12px; font-size:12px; margin-bottom:16px;">
                        <i class="fas fa-info-circle text-primary mr-1"></i>
                        <strong>Contoh (Subang):</strong> <code style="color:#f6c23e;">-6.571800, 107.760000</code>
                    </div>
                    <p class="small font-weight-bold mb-2"><i class="fas fa-crosshairs text-hnb-orange mr-1"></i> Atau klik langsung di peta:</p>
                    <div id="miniMapPariwisata"></div>
                    <div style="background:rgba(0,0,0,0.3); padding:10px 14px; border-radius:0 0 10px 10px; font-size:12px; display:flex; gap:10px; align-items:center; justify-content:space-between;">
                        <span>Lat: <strong id="previewLatP" style="color:#f6c23e;">-</strong></span>
                        <span>Lng: <strong id="previewLngP" style="color:#f6c23e;">-</strong></span>
                        <button type="button" id="btnApplyP" class="btn btn-sm btn-hnb-orange py-1 px-3 radius-hnb" onclick="applyPickedCoordP()" disabled>
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

                    {{-- Flash sukses --}}
                    @if(session('success'))
                    <div class="alert mb-4 d-flex align-items-start" style="border-radius: 10px; background:rgba(40,167,69,0.15); border:1px solid rgba(40,167,69,0.4); color:#6fcf97;">
                        <i class="fas fa-check-circle mr-3 mt-1 fa-lg"></i>
                        <div class="small">{!! session('success') !!}</div>
                    </div>
                    @endif

                    {{-- Flash error validasi --}}
                    @if($errors->any())
                    <div class="alert mb-4" style="border-radius: 10px; background:rgba(231,74,59,0.15); border:1px solid rgba(231,74,59,0.4); color:#ff6b6b; font-size:13px;">
                        <i class="fas fa-exclamation-circle mr-2"></i> <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <div class="text-center mb-4">
                        <div style="background:rgba(78,115,223,0.12); border:1px solid rgba(78,115,223,0.3); border-radius:10px; padding:10px 16px; margin-bottom:16px; font-size:12px; color:#a0aec0;">
                            <i class="fas fa-info-circle text-primary mr-1"></i>
                            Form ini <strong class="text-white">tidak memerlukan akun</strong>. Setelah terverifikasi, Admin akan menghubungi Anda melalui email yang dicantumkan.
                        </div>
                        <h3 class="font-weight-bold text-white teks-judul pb-1" id="formTitle">Profil Destinasi Wisata</h3>
                        <p class="text-white-50 teks-subjudul" style="font-size: 0.95rem;" id="formSubtitle">Langkah 1 dari 4</p>
                        <div class="progress mt-3 radius-hnb shadow-sm" style="height:6px; background:rgba(255,255,255,0.1);">
                            <div class="progress-bar transition-all" id="formProgress" role="progressbar" style="width:25%; background-color: var(--hnb-orange);"></div>
                        </div>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden" style="border: 1px solid rgba(255,255,255,0.1); border-top: 4px solid var(--hnb-orange); border-radius: 16px;">
                        <div class="card-body p-4 p-md-5">
                            <form id="wizardForm" action="{{ route('register.pariwisata') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="hiddenLatP" name="latitude" value="">
                                <input type="hidden" id="hiddenLngP" name="longitude" value="">

                                {{-- STEP 1: Info Destinasi --}}
                                <div id="step1" class="step-content">
                                    <div class="row">
                                        <div class="col-md-7 mb-4">
                                            <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Nama Destinasi Wisata</label>
                                            <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-mountain"></i></span>
                                                </div>
                                                <input type="text" name="nama_wisata" value="{{ old('nama_wisata') }}" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="Contoh: Curug Cijalu" required style="font-size: 1.05rem;">
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Kategori</label>
                                            <select name="kategori" class="form-control form-control-dark radius-hnb px-3 input-dark" style="height:50px;" required>
                                                <option value="" disabled selected>Pilih Kategori...</option>
                                                <option value="Alam" {{ old('kategori')=='Alam'?'selected':'' }}>🌿 Wisata Alam</option>
                                                <option value="Budaya" {{ old('kategori')=='Budaya'?'selected':'' }}>🎭 Wisata Budaya</option>
                                                <option value="Buatan" {{ old('kategori')=='Buatan'?'selected':'' }}>🎡 Wisata Buatan</option>
                                                <option value="Kuliner" {{ old('kategori')=='Kuliner'?'selected':'' }}>🍜 Wisata Kuliner</option>
                                                <option value="Petualangan" {{ old('kategori')=='Petualangan'?'selected':'' }}>🧗 Petualangan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Deskripsi & Daya Tarik Utama</label>
                                        <textarea name="deskripsi" class="form-control form-control-dark radius-hnb p-3 input-dark" rows="4"
                                            placeholder="Ceritakan keunikan destinasi ini, apa yang bisa dinikmati wisatawan...">{{ old('deskripsi') }}</textarea>
                                    </div>
                                </div>

                                {{-- STEP 2: Lokasi + Koordinat --}}
                                <div id="step2" class="step-content d-none">
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Alamat Lengkap</label>
                                        <textarea name="alamat" class="form-control form-control-dark radius-hnb p-3 input-dark" rows="2"
                                            placeholder="Desa Cibadak, Kec. Cisalak, Kab. Subang..." required>{{ old('alamat') }}</textarea>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75 mb-0 mr-2">Koordinat di Peta <span class="text-white-50">(opsional tapi disarankan)</span></label>
                                        <button type="button" class="btn btn-sm btn-link text-hnb-orange p-0"
                                            data-toggle="modal" data-target="#modalKoordinatPariwisata" style="font-size:12px; text-decoration:none;">
                                            <i class="fas fa-question-circle mr-1"></i>Cara dapatkan koordinat?
                                        </button>
                                    </div>
                                    <div class="p-3 mb-3 d-flex" style="background:rgba(78,115,223,0.1); border:1px solid rgba(78,115,223,0.2); border-radius:8px;">
                                        <i class="fas fa-info-circle text-primary mr-2 mt-1"></i>
                                        <p class="text-white-50 small mb-0">Klik <strong class="text-white">"Pilih di Peta"</strong> untuk menentukan titik lokasi secara visual — lebih mudah dan akurat.</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Latitude</label>
                                            <input type="number" step="any" id="inputLatP"
                                                class="form-control form-control-dark radius-hnb py-4 px-3 input-dark"
                                                placeholder="-6.5718" value="{{ old('latitude') }}" oninput="syncCoordP()">
                                            <small class="text-white-50 ml-1">Contoh: -6.571800</small>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Longitude</label>
                                            <input type="number" step="any" id="inputLngP"
                                                class="form-control form-control-dark radius-hnb py-4 px-3 input-dark"
                                                placeholder="107.7600" value="{{ old('longitude') }}" oninput="syncCoordP()">
                                            <small class="text-white-50 ml-1">Contoh: 107.760000</small>
                                        </div>
                                        <div class="col-md-2 mb-3 d-flex align-items-center">
                                            <button type="button" class="btn btn-outline-hnb-orange radius-hnb py-3 w-100 font-weight-bold"
                                                data-toggle="modal" data-target="#modalKoordinatPariwisata" style="font-size:11px;">
                                                <i class="fas fa-map-pin d-block fa-lg mb-1"></i>Pilih Peta
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 3: Operasional --}}
                                <div id="step3" class="step-content d-none">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">No. Telepon / WhatsApp</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0 text-white font-weight-bold" style="border-color:rgba(255,255,255,0.1)!important;">+62</span>
                                                </div>
                                                <input type="tel" name="no_telp" value="{{ old('no_telp') }}"
                                                    class="form-control form-control-dark border-left-0 radius-hnb py-4 px-3 input-dark"
                                                    placeholder="8123xxx" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="text-white font-weight-bold small ml-1 opacity-75">Harga Tiket Masuk</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0 text-white font-weight-bold" style="border-color:rgba(255,255,255,0.1)!important;">Rp</span>
                                                </div>
                                                <input type="number" name="harga_tiket" value="{{ old('harga_tiket', 0) }}"
                                                    class="form-control form-control-dark border-left-0 radius-hnb py-4 px-3 input-dark"
                                                    placeholder="0 = Gratis" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Unggah Foto Destinasi / Dokumen Pendukung</label>
                                        <div class="upload-box-premium mt-2">
                                            <i class="fas fa-image fa-2x text-hnb-orange mb-2"></i>
                                            <p class="text-white small font-weight-bold mb-1">Format: JPG, PNG, PDF — Maks 5MB</p>
                                            <input type="file" name="foto_path" class="form-control-file small text-white-50 mx-auto" accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                    </div>
                                </div>

                                {{-- STEP 4: Data Pengelola (Kontak Admin) --}}
                                <div id="step4" class="step-content d-none">
                                    <div class="p-3 mb-4 d-flex" style="background:rgba(255,107,53,0.08); border:1px solid rgba(255,107,53,0.2); border-radius:8px;">
                                        <i class="fas fa-envelope text-hnb-orange mr-3 mt-1"></i>
                                        <p class="text-white-50 small mb-0">
                                            Isi data pengelola di bawah. Tim Admin WanderMed akan mengirimkan
                                            <strong class="text-white">konfirmasi keputusan</strong> ke email yang Anda cantumkan.
                                            <strong class="text-white"> Tidak ada akun atau password yang dibuat.</strong>
                                        </p>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">Nama Pengelola / Penanggung Jawab</label>
                                        <input type="text" name="nama_pengelola" value="{{ old('nama_pengelola') }}"
                                            class="form-control form-control-dark radius-hnb py-4 px-3 input-dark"
                                            placeholder="Contoh: Bapak Dede Suherman" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="text-white font-weight-bold small ml-1 opacity-75">
                                            Email Kontak <span class="text-hnb-orange">*</span>
                                        </label>
                                        <input type="email" name="email_kontak" value="{{ old('email_kontak') }}"
                                            class="form-control form-control-dark radius-hnb py-4 px-3 input-dark"
                                            placeholder="pengelola@destinasi.com" required>
                                        <small class="text-white-50 ml-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Admin akan mengirim keputusan verifikasi ke email ini
                                        </small>
                                    </div>
                                </div>

                                {{-- STEP 5: Sukses (ditampilkan setelah redirect dengan flash) --}}
                                <div id="step5" class="step-content d-none text-center py-4">
                                    <div class="icon-kesehatan mx-auto mb-4 d-flex align-items-center justify-content-center animasi-jantung"
                                        style="width:100px; height:100px; border-color:#28a745; background:rgba(40,167,69,0.1);">
                                        <i class="fas fa-paper-plane fa-3x text-success"></i>
                                    </div>
                                    <h4 class="font-weight-bold text-white mb-2">Pendaftaran Terkirim! 🌄</h4>
                                    <p class="text-white-50 small">Admin WanderMed akan meninjau dan mengirimkan konfirmasi ke email Anda.</p>
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
                                    <a href="/daftar/pariwisata" class="btn btn-hnb-orange radius-hnb px-4 py-3 font-weight-bold w-100 mb-3">Daftar Destinasi Lain</a>
                                    <a href="/" class="btn btn-outline-light radius-hnb px-4 py-3 font-weight-bold w-100">Kembali ke Beranda</a>
                                </div>
                            </form>
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
<script src="{{ asset('js/wizard-pariwisata.js') }}"></script>
<script>
(function() {
    var pickedLatP = null, pickedLngP = null;
    var miniMapP = null, pickedMarkerP = null;

    window.syncCoordP = function() {
        var lat = document.getElementById('inputLatP').value;
        var lng = document.getElementById('inputLngP').value;
        document.getElementById('hiddenLatP').value = lat;
        document.getElementById('hiddenLngP').value = lng;
    };

    $('#modalKoordinatPariwisata').on('shown.bs.modal', function() {
        setTimeout(function() {
            if (!miniMapP) {
                miniMapP = L.map('miniMapPariwisata').setView([-6.5718, 107.7600], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(miniMapP);

                miniMapP.on('click', function(e) {
                    pickedLatP = e.latlng.lat.toFixed(6);
                    pickedLngP = e.latlng.lng.toFixed(6);
                    if (pickedMarkerP) miniMapP.removeLayer(pickedMarkerP);
                    pickedMarkerP = L.marker(e.latlng).addTo(miniMapP)
                        .bindPopup('<b>📍 Lokasi Dipilih</b><br>Lat: ' + pickedLatP + '<br>Lng: ' + pickedLngP)
                        .openPopup();
                    document.getElementById('previewLatP').textContent = pickedLatP;
                    document.getElementById('previewLngP').textContent = pickedLngP;
                    document.getElementById('btnApplyP').disabled = false;
                });
            } else {
                miniMapP.invalidateSize();
            }
        }, 300);
    });

    window.applyPickedCoordP = function() {
        if (!pickedLatP || !pickedLngP) return;
        document.getElementById('inputLatP').value = pickedLatP;
        document.getElementById('inputLngP').value = pickedLngP;
        document.getElementById('hiddenLatP').value = pickedLatP;
        document.getElementById('hiddenLngP').value = pickedLngP;
        $('#modalKoordinatPariwisata').modal('hide');
    };
})();
</script>
@endpush
