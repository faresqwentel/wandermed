{{-- ============================================================
     Halaman Peta Faskes Wisatawan – WanderMed
     Dirombak total: UI friendly, bottom sheet informatif,
     marker custom, filter visual, dan info fasilitas lengkap.
     ============================================================ --}}
@extends('theme.wisatawan')

@push('styles')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    {{-- Peta Page CSS --}}
    <link rel="stylesheet" href="{{ asset('css/peta.css') }}" />
@endpush

@section('content')
    @include('theme.navbar')

    <div class="map-page-wrapper">

        {{-- ========= OVERLAY CENTER-TOP PANEL ========= --}}
        <div class="map-overlay-panel">

            {{-- BARIS 1: Search + Lokasi + BPJS --}}
            <div style="position: relative;">
                <div class="unified-nav-box">
                    <div class="search-input-group">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama faskes, klinik, apotek, atau wisata..." autocomplete="off">
                    </div>
                    <div class="v-separator-map"></div>
                    <button class="btn-my-location" id="btnMyLocation" title="Gunakan lokasi saya">
                        <i class="fas fa-crosshairs"></i>
                        <span>Lokasi Saya</span>
                    </button>
                    <div class="v-separator-map"></div>
                    <button class="bpjs-chip" id="bpjsChip">
                        <i class="fas fa-shield-alt"></i>
                        BPJS
                    </button>
                </div>
                
                {{-- Dropdown Hasil Pencarian --}}
                <div id="searchResultsDropdown" class="search-dropdown-list" style="display: none;"></div>
            </div>

            {{-- BARIS 2: Filter Destinasi + Sub-kategori Faskes --}}
            <div class="filter-bar">
                <span class="filter-bar-label"><i class="fas fa-layer-group"></i> Filter:</span>
                <select id="masterFilter" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; color: #fff; font-family: 'Poppins',sans-serif; font-size: 12px; padding: 5px 10px; cursor: pointer; flex-shrink: 0;">
                    <option value="All">Semua</option>
                    <option value="Faskes">Faskes</option>
                    <option value="Pariwisata">Pariwisata</option>
                </select>
                <div class="v-separator-map"></div>
                <div class="filter-chips-row" id="faskesSubFilter">
                    <div class="filter-chip active" data-type="AllFaskes">
                        <i class="fas fa-th-large"></i> Semua
                    </div>
                    <div class="filter-chip" data-type="Rumah Sakit">
                        <i class="fas fa-hospital-alt" style="color:#e74a3b;"></i> RS
                    </div>
                    <div class="filter-chip" data-type="Klinik">
                        <i class="fas fa-clinic-medical" style="color:#4e73df;"></i> Klinik
                    </div>
                    <div class="filter-chip" data-type="Apotek">
                        <i class="fas fa-pills" style="color:#1cc88a;"></i> Apotek
                    </div>
                    <div class="filter-chip" data-type="Puskesmas">
                        <i class="fas fa-heartbeat" style="color:#f6c23e;"></i> Puskesmas
                    </div>
                </div>
            </div>
        </div>

        {{-- Badge Jumlah Hasil Pencarian --}}
        <div class="result-count-badge" id="resultCountBadge">
            <i class="fas fa-map-marker-alt" style="color: var(--hnb-orange);"></i>
            <span>Menampilkan <strong id="resultCount">4</strong> lokasi</span>
        </div>

        {{-- Peta Leaflet --}}
        <div id="map"></div>

        {{-- ========= BOTTOM SHEET DETAIL FASKES ========= --}}
        <div id="faskesDetailPanel" class="faskes-detail-panel">
            <div class="panel-handle"></div>

            {{-- Header --}}
            <div class="panel-header">
                <div style="flex: 1;">
                    <div class="panel-faskes-name" id="detailName">Nama Faskes</div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                        <div class="panel-faskes-type" id="detailType" style="margin-bottom: 0;">Jenis Faskes</div>
                        <div id="detailRatingContainer" style="display: none; align-items: center; gap: 4px; font-size: 11.5px; background: rgba(255,193,7,0.1); color: #ffc107; padding: 2px 8px; border-radius: 12px; border: 1px solid rgba(255,193,7,0.3);">
                            <i class="fas fa-star" style="font-size: 10px;"></i> <span id="detailRatingAvg" style="font-weight: 700;">0.0</span> 
                            <span id="detailRatingCount" style="color: inherit; opacity: 0.7; font-size: 10.5px;">(0)</span>
                        </div>
                    </div>
                </div>
                <button class="panel-close-btn" onclick="closeDetail()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Status Chips --}}
            <div class="panel-badges" id="detailBadges">
                {{-- Diisi oleh JS --}}
            </div>

            {{-- Body Scrollable --}}
            {{-- ===== BODY SCROLLABLE ===== --}}
            <div class="panel-body">
                
                {{-- KONTEN DETAIL (Informasi Faskes) --}}
                <div id="detailContentBlock">
                    {{-- Modul Foto --}}
                    <div id="headerPhotoContainer" style="display: none; padding: 0 0 15px 0;">
                        <img id="detailPhoto" src="" alt="Foto Lokasi" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    </div>

                    <div class="panel-section">
                        <div class="panel-section-title">Informasi Lokasi</div>
                        <div class="info-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="info-row-content">
                                <div class="info-row-label">Alamat</div>
                                <div class="info-row-value" id="detailAddress">—</div>
                            </div>
                        </div>
                        <div class="info-row jam-open" id="rowJam">
                            <i class="fas fa-clock"></i>
                            <div class="info-row-content">
                                <div class="info-row-label">Jam Operasional</div>
                                <div class="info-row-value" id="detailJam">—</div>
                            </div>
                        </div>
                        <div class="info-row" id="rowPhone">
                            <i class="fas fa-phone-alt"></i>
                            <div class="info-row-content">
                                <div class="info-row-label">Telepon</div>
                                <div class="info-row-value" id="detailPhone">—</div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-section" id="sectionFasilitas">
                        <div class="panel-section-title">Fasilitas Tersedia</div>
                        <div class="facilities-grid" id="detailFacilities"></div>
                    </div>

                    <div class="panel-section" id="rowNotes" style="display:none;">
                        <div class="panel-section-title">Catatan Penting</div>
                        <div class="info-row" style="background: rgba(246,194,62,0.07); border-color: rgba(246,194,62,0.2);">
                            <i class="fas fa-exclamation-circle" style="color: #f6c23e;"></i>
                            <div class="info-row-content">
                                <div class="info-row-value" id="detailNotes" style="color: #f6c23e;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KONTEN REVIEW (Form Ulasan) --}}
                <div id="reviewContentBlock" style="display:none;">
                    <div class="panel-section">
                        {{-- Profil Reviewer --}}
                        @if(session('auth_user'))
                        @php $authUser = \App\Models\User::find(session('auth_user.id')); @endphp
                        <div style="background:#fff8f0; border:1px solid rgba(255,122,0,0.2); border-radius:12px; padding:12px; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
                            <div style="width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#ff7a00,#e65c00); display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; font-size:16px;">
                                {{ strtoupper(substr(session('auth_user.name','?'), 0, 1)) }}
                            </div>
                            <div style="flex:1;">
                                <div style="font-weight:700; font-size:14px; color:#333;">{{ session('auth_user.name') }}</div>
                                <div style="font-size:11px; color:#666;">Member WanderMed</div>
                            </div>
                        </div>
                        @endif

                        {{-- Input Bintang --}}
                        <div class="review-rating-box">
                            <div id="starRating" style="display:flex; justify-content:center; gap:15px; margin-bottom: 10px;">
                                <i class="fas fa-star review-star" data-val="1"></i>
                                <i class="fas fa-star review-star" data-val="2"></i>
                                <i class="fas fa-star review-star" data-val="3"></i>
                                <i class="fas fa-star review-star" data-val="4"></i>
                                <i class="fas fa-star review-star" data-val="5"></i>
                            </div>
                            <div id="starLabel" style="font-size:15px; font-weight:700; color:#ffc107; height:20px; text-transform:uppercase;"></div>
                            <input type="hidden" id="reviewRating" value="0">
                        </div>

                        {{-- Input Teks --}}
                        <div class="panel-section-title">Ceritakan Pengalaman Anda</div>
                        <textarea id="reviewKomentar" class="review-textarea" rows="5" 
                            placeholder="Ketik ulasan Anda di sini..."></textarea>
                    </div>
                </div>

            </div>

            {{-- ===== TOMBOL AKSI ===== --}}
            <div class="panel-actions">
                
                {{-- Aksi Detail --}}
                <div id="detailActions" style="display:flex; flex-direction:column; gap:10px;">
                    <button id="btnDirection" class="btn btn-hnb-orange w-100 py-3 font-weight-bold shadow-lg" style="border-radius:14px; font-size:1.1rem;">
                        <i class="fas fa-location-arrow mr-2"></i> Mulai Rute Navigasi
                    </button>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                        <a id="btnCall" href="tel:+62" class="btn btn-outline-secondary py-2" style="border-radius:10px;">
                            <i class="fas fa-phone-alt mr-1"></i> Hubungi
                        </a>
                        <button class="btn btn-outline-secondary py-2" onclick="shareLocation()" style="border-radius:10px;">
                            <i class="fas fa-share-alt mr-1"></i> Bagikan
                        </button>
                    </div>
                    <a id="btnJadwal" href="#" class="btn btn-outline-primary py-2" style="border-radius:10px; display:none; border-style: dashed;">
                        <i class="fas fa-user-md mr-1"></i> Lihat Jadwal Praktik
                    </a>
                    <button id="btnDeteksiFaskes" class="btn btn-danger py-2 mt-1" style="border-radius:10px; display:none; border: none; background: linear-gradient(135deg, #e74a3b, #c0392b); color: #fff; box-shadow: 0 4px 15px rgba(231,74,59,0.3);">
                        <i class="fas fa-heartbeat mr-1"></i> Deteksi Faskes Terdekat
                    </button>
                </div>

                {{-- Aksi Review --}}
                <div id="reviewActions" style="display:none; flex-direction:column; gap:10px;">
                    <button onclick="submitReview()" id="btnSubmitReview" class="btn btn-success w-100 py-3 font-weight-bold shadow" style="border-radius:14px; font-size:1.1rem;">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Ulasan
                    </button>
                    <button onclick="backToDetail()" class="btn btn-outline-secondary w-100 py-2" style="border-radius:10px;">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail
                    </button>
                </div>

            </div>
        </div>



        {{-- ========= GEOFENCE MODAL ========= --}}
        <div id="geofenceModal" class="geofence-backdrop">
            <div class="geofence-modal">
                <button class="gm-close" onclick="closeGeofenceModal()"><i class="fas fa-times"></i></button>
                <div class="gm-header">
                    <div class="gm-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="gm-title">Terdeteksi di Area Wisata!</div>
                    <div class="gm-subtitle">Anda sedang berada di <br><span id="gfWisataName">Nama Wisata</span>.<br>Membutuhkan bantuan medis terdekat?</div>
                </div>
                <div class="gm-list" id="gfFaskesList">
                    {{-- Diisi oleh JS --}}
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.WanderMed = {
        isLoggedIn: @json(session()->has('auth_user')),
        isWisatawan: @json(session('auth_user.role') === 'wisatawan'),
        csrfToken: "{{ csrf_token() }}",
        faskesDataRaw: @json($daftarFaskes ?? []),
        pariwisataDataRaw: @json($daftarPariwisata ?? [])
    };
</script>
<script src="{{ asset('js/v_peta_faskes.js') }}"></script>
@endpush
