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
            <div class="unified-nav-box">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama faskes, klinik, apotek...">
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
                <div>
                    <div class="panel-faskes-name" id="detailName">Nama Fasilitas</div>
                    <div class="panel-faskes-type" id="detailType">Tipe Faskes</div>
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
                        <div style="text-align:center; padding: 20px 0; background: #fafafa; border-radius: 12px; border: 1px dashed #ddd; margin-bottom: 20px;">
                            <div id="starRating" style="display:flex; justify-content:center; gap:15px; margin-bottom: 10px;">
                                <i class="fas fa-star review-star" data-val="1" style="font-size:42px; color:#ddd; cursor:pointer;"></i>
                                <i class="fas fa-star review-star" data-val="2" style="font-size:42px; color:#ddd; cursor:pointer;"></i>
                                <i class="fas fa-star review-star" data-val="3" style="font-size:42px; color:#ddd; cursor:pointer;"></i>
                                <i class="fas fa-star review-star" data-val="4" style="font-size:42px; color:#ddd; cursor:pointer;"></i>
                                <i class="fas fa-star review-star" data-val="5" style="font-size:42px; color:#ddd; cursor:pointer;"></i>
                            </div>
                            <div id="starLabel" style="font-size:15px; font-weight:700; color:#ffc107; height:20px; text-transform:uppercase;"></div>
                            <input type="hidden" id="reviewRating" value="0">
                        </div>

                        {{-- Input Teks --}}
                        <div class="panel-section-title">Ceritakan Pengalaman Anda</div>
                        <textarea id="reviewKomentar" rows="5" 
                            style="width:100%; border:2px solid #eee; border-radius:12px; padding:15px; font-size:14px; resize:none; background:#fff; color:#333; outline:none; transition: border-color 0.3s;"
                            placeholder="Ketik ulasan Anda di sini..."
                            onfocus="this.style.borderColor='#ff7a00'"
                            onblur="this.style.borderColor='#eee'"></textarea>
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
document.addEventListener("DOMContentLoaded", function() {

    var isLoggedIn = @json(session()->has('auth_user'));
    var isWisatawan = @json(session('auth_user.role') === 'wisatawan');
    var csrfToken = "{{ csrf_token() }}";
    var currentFaskesId = null;
    var globalUserLocation = null;

    // =========================================================
    // DATA MARKER
    // Diisi langsung dari Controller via compact()
    // =========================================================
    var faskesDataRaw = @json($daftarFaskes ?? []);
    var pariwisataDataRaw = @json($daftarPariwisata ?? []);

    var faskesData = [];
    if (faskesDataRaw.length > 0) {
        faskesData = faskesDataRaw.map(f => ({
            id:       f.id,
            name:     f.name,
            lat:      f.lat,
            lng:      f.lng,
            type:     f.type,
            bpjs:     f.bpjs,
            status:   f.status,
            address:  f.address,
            phone:    f.phone,
            jam:      f.status === 'open' ? 'Sedang Buka' : 'Sedang Tutup',
            notes:    f.notes,
            facilities: (f.facilities || []).map(label => ({
                icon:  getFacilityIcon(label),
                ico:   getFacilityFaIcon(label),
                label: label
            }))
        }));
    } else {
        faskesData = getDemoData();
    }

    var pariwisataData = pariwisataDataRaw;
    var wisataMarkers  = [];
    var activeMarkers  = [];

    // =========================================================
    // MARKER ICON untuk Pariwisata (ungu)
    // =========================================================
    function createWisataMarkerIcon() {
        return L.divIcon({
            className: '',
            html: `
                <div style="
                    width: 38px; height: 38px;
                    background: linear-gradient(135deg, #8B5CF6, #6D28D9);
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    box-shadow: 0 4px 14px rgba(109,40,217,0.5);
                    border: 3px solid rgba(255,255,255,0.4);
                ">
                    <i class="fas fa-mountain" style="color: #fff; font-size: 13px;"></i>
                </div>`,
            iconSize: [38, 38],
            iconAnchor: [19, 19],
            popupAnchor: [0, -24]
        });
    }

    // Inisialisasi Peta
    var map = L.map('map', { zoomControl: false }).setView([-6.5710, 107.7587], 14);
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // --- Helper: Mapping nama layanan ke class ikon faskes-grid ---
    function getFacilityIcon(label) {
        const map = {
            'UGD 24 Jam': 'fac-icon red', 'Ambulans': 'fac-icon blue',
            'Rawat Inap': 'fac-icon teal', 'Apotek': 'fac-icon green',
            'Laboratorium': 'fac-icon yellow', 'Dok. Spesialis': 'fac-icon orange',
            'Poli Anak': 'fac-icon purple', 'Poli Gigi': 'fac-icon blue',
            'Poli Umum': 'fac-icon green', 'Imunisasi': 'fac-icon teal',
        };
        return map[label] || 'fac-icon orange';
    }
    function getFacilityFaIcon(label) {
        const map = {
            'UGD 24 Jam': 'fas fa-ambulance', 'Ambulans': 'fas fa-car',
            'Rawat Inap': 'fas fa-bed', 'Apotek': 'fas fa-pills',
            'Laboratorium': 'fas fa-flask', 'Dok. Spesialis': 'fas fa-user-md',
            'Poli Anak': 'fas fa-baby', 'Poli Gigi': 'fas fa-tooth',
            'Poli Umum': 'fas fa-stethoscope', 'Imunisasi': 'fas fa-syringe',
        };
        return map[label] || 'fas fa-clinic-medical';
    }

    // --- Data Demo (fallback saat database masih kosong) ---
    function getDemoData() {
        return [
            { id:1, name:"RSUD Subang", lat:-6.5710, lng:107.7600, type:"Rumah Sakit",
              bpjs:true, status:"open", address:"Jl. Brigjen Katamso No.37, Subang",
              phone:"(0260) 411421", jam:"24 Jam (UGD & Layanan Darurat)",
              notes:"Antrean poli umum buka pukul 07.00 WIB. Parkiran tersedia luas.",
              facilities:[
                {icon:"fac-icon red",ico:"fas fa-ambulance",label:"UGD 24 Jam"},
                {icon:"fac-icon blue",ico:"fas fa-car",label:"Ambulans"},
                {icon:"fac-icon teal",ico:"fas fa-bed",label:"Rawat Inap"},
                {icon:"fac-icon green",ico:"fas fa-pills",label:"Apotek"},
              ]},
            { id:2, name:"Klinik Pratama Cibogo", lat:-6.5650, lng:107.7500, type:"Klinik",
              bpjs:true, status:"open", address:"Jl. Raya Cibogo No.12, Subang",
              phone:"(0260) 422111", jam:"Senin – Sabtu, 08.00 – 20.00 WIB", notes:null,
              facilities:[
                {icon:"fac-icon green",ico:"fas fa-stethoscope",label:"Poli Umum"},
                {icon:"fac-icon green",ico:"fas fa-pills",label:"Apotek"},
              ]},
            { id:3, name:"Apotek Kimia Farma Subang", lat:-6.5730, lng:107.7560, type:"Apotek",
              bpjs:true, status:"open", address:"Jl. Otista No.4, Subang",
              phone:"(0260) 417080", jam:"Setiap Hari, 07.00 – 22.00 WIB", notes:null,
              facilities:[
                {icon:"fac-icon green",ico:"fas fa-pills",label:"Obat Keras"},
                {icon:"fac-icon teal",ico:"fas fa-vials",label:"Alat Kesehatan"},
              ]},
            { id:4, name:"Puskesmas Subang", lat:-6.5690, lng:107.7640, type:"Puskesmas",
              bpjs:true, status:"closed", address:"Jl. Kapeh Jaya No.1, Subang",
              phone:"(0260) 411009", jam:"Senin – Jumat, 07.30 – 14.00 WIB",
              notes:"Sedang tutup sementara untuk renovasi.",
              facilities:[
                {icon:"fac-icon green",ico:"fas fa-stethoscope",label:"Poli Umum"},
                {icon:"fac-icon teal",ico:"fas fa-syringe",label:"Imunisasi"},
              ]},
        ];
    }

    // =========================================================
    // CUSTOM MARKER ICON per kategori Faskes
    // =========================================================
    function createMarkerIcon(type) {
        const colors = {
            "Rumah Sakit": { bg: "#e74a3b", icon: "fa-hospital-alt" },
            "Klinik":      { bg: "#4e73df", icon: "fa-clinic-medical" },
            "Apotek":      { bg: "#1cc88a", icon: "fa-pills" },
            "Puskesmas":   { bg: "#f6c23e", icon: "fa-heartbeat" },
        };
        const c = colors[type] || { bg: "#ff7a00", icon: "fa-map-marker-alt" };

        return L.divIcon({
            className: '',
            html: `
                <div style="
                    width: 40px; height: 40px;
                    background: ${c.bg};
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    display: flex; align-items: center; justify-content: center;
                    box-shadow: 0 4px 14px rgba(0,0,0,0.4);
                    border: 3px solid rgba(255,255,255,0.3);
                ">
                    <i class="fas ${c.icon}" style="transform: rotate(45deg); color: #fff; font-size: 14px;"></i>
                </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -45]
        });
    }

    // =========================================================
    // RENDER MARKER & FILTERING
    // =========================================================
    var activeMarkers = [];

    function updateMarkers() {
        // Bersihkan marker sebelumnya
        activeMarkers.forEach(m => map.removeLayer(m));
        activeMarkers = [];
        wisataMarkers.forEach(m => map.removeLayer(m));
        wisataMarkers = [];

        const masterFilter = document.getElementById('masterFilter').value; // 'All', 'Faskes', 'Pariwisata'
        const activeChip = document.querySelector('.filter-chip.active');
        const typeFilter  = activeChip ? activeChip.dataset.type : 'AllFaskes';
        const bpjsActive  = document.getElementById('bpjsChip').classList.contains('active');
        const searchQuery = document.getElementById('searchInput').value.toLowerCase().trim();

        // Toggle subfilter UI
        const faskesSubFilter = document.getElementById('faskesSubFilter');
        if (masterFilter === 'Pariwisata') {
            faskesSubFilter.style.display = 'none';
        } else {
            faskesSubFilter.style.display = 'flex';
        }

        let count = 0;
        let bounds = [];

        // 1. Render Faskes
        if (masterFilter === 'All' || masterFilter === 'Faskes') {
            faskesData.forEach(f => {
                const matchType   = (typeFilter === 'AllFaskes' || f.type === typeFilter);
                const matchBPJS   = (!bpjsActive || f.bpjs);
                const matchSearch = !searchQuery || f.name.toLowerCase().includes(searchQuery) || f.address.toLowerCase().includes(searchQuery);

                if (matchType && matchBPJS && matchSearch) {
                    const marker = L.marker([f.lat, f.lng], { icon: createMarkerIcon(f.type) }).addTo(map);

                    marker.bindTooltip(`<b style="font-size:13px;">${f.name}</b><br><small style="color:rgba(0,0,0,0.5)">${f.type}</small>`, {
                        direction: 'top', offset: [0, -45], className: 'wm-tooltip'
                    });

                    marker.on('click', function () {
                        showDetail(f);
                        map.flyTo([f.lat, f.lng], 16, { duration: 1 });
                    });

                    activeMarkers.push(marker);
                    bounds.push([f.lat, f.lng]);
                    count++;
                }
            });
        }

        // 2. Render Pariwisata
        if (masterFilter === 'All' || masterFilter === 'Pariwisata') {
            pariwisataData.forEach(w => {
                if (!w.lat || !w.lng) return;
                
                const matchSearch = !searchQuery || w.name.toLowerCase().includes(searchQuery) || (w.kategori && w.kategori.toLowerCase().includes(searchQuery));
                
                if (matchSearch) {
                    const marker = L.marker([w.lat, w.lng], { icon: createWisataMarkerIcon() }).addTo(map);

                    marker.bindTooltip(`<b style="font-size:13px;">${w.name}</b><br><small style="color:#8B5CF6;">${w.kategori}</small>`, {
                        direction: 'top', offset: [0, -24], className: 'wm-tooltip'
                    });

                    marker.on('click', function() {
                        map.flyTo([w.lat, w.lng], 14, { duration: 1 });
                        showWisataDetail(w);
                    });

                    wisataMarkers.push(marker);
                    bounds.push([w.lat, w.lng]);
                    count++;
                }
            });
        }

        document.getElementById('resultCount').textContent = count;
        
        // Auto-zoom jika kategori Pariwisata dipilih (Berdasarkan instruksi)
        if (masterFilter === 'Pariwisata' && bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50], maxZoom: 14 });
        }
    }

    // Listener Master Filter
    document.getElementById('masterFilter').addEventListener('change', function() {
        closeDetail();
        updateMarkers();
    });

    // =========================================================
    // TAMPILKAN DETAIL FASKES DI BOTTOM SHEET
    // =========================================================
    window.showDetail = function(data) {
        // Isi data dasar
        document.getElementById('detailName').textContent    = data.name;
        document.getElementById('detailType').textContent    = data.type;
        document.getElementById('detailAddress').textContent = data.address;
        document.getElementById('detailJam').textContent     = data.jam;
        document.getElementById('detailPhone').textContent   = data.phone;

        // Status visual jam
        const rowJam = document.getElementById('rowJam');
        if (data.status === 'open') {
            rowJam.classList.add('jam-open');
            rowJam.querySelector('i').className = 'fas fa-clock';
        } else {
            rowJam.classList.remove('jam-open');
        }

        // Badges
        const badgesEl = document.getElementById('detailBadges');
        const statusChip = data.status === 'open'
            ? `<span class="info-chip status-open"><i class="fas fa-circle" style="font-size:8px;"></i> Buka Sekarang</span>`
            : `<span class="info-chip status-closed"><i class="fas fa-circle" style="font-size:8px;"></i> Sedang Tutup</span>`;
        const bpjsChip = data.bpjs
            ? `<span class="info-chip bpjs-yes"><i class="fas fa-shield-alt"></i> Terima BPJS</span>`
            : `<span class="info-chip bpjs-no"><i class="fas fa-times-circle"></i> Non-BPJS</span>`;
            
        let distText = "Jarak tidak diketahui";
        if (globalUserLocation) {
            const distKm = getDistanceFromLatLonInKm(globalUserLocation.lat, globalUserLocation.lng, data.lat, data.lng);
            distText = distKm < 1 ? `~${Math.round(distKm * 1000)}m dari Anda` : `~${distKm.toFixed(1)}km dari Anda`;
        }
        badgesEl.innerHTML = statusChip + bpjsChip + `<span class="info-chip chip-distance"><i class="fas fa-route"></i> ${distText}</span>`;

        // Fasilitas
        document.getElementById('detailFacilities').innerHTML = data.facilities.map(f => `
            <div class="facility-item">
                <div class="${f.icon}"><i class="${f.ico}"></i></div>
                ${f.label}
            </div>`).join('');

        // Jadwal & Kontak
        const btnJadwal = document.getElementById('btnJadwal');
        btnJadwal.style.display = 'block';
        btnJadwal.href = `/faskes/${data.id}/jadwal`;
        document.getElementById('btnCall').href = `tel:${data.phone.replace(/\D/g, '')}`;

        // Notes
        const notesSection = document.getElementById('rowNotes');
        if (data.notes) {
            notesSection.style.display = 'block';
            document.getElementById('detailNotes').textContent = data.notes;
        } else {
            notesSection.style.display = 'none';
        }

        // Navigasi
        document.getElementById('btnDirection').onclick = function(e) {
            e.preventDefault();
            openSmartNavigation(data.lat, data.lng, data.id, data.name, data.type);
        };

        // Reset ke Mode Detail
        backToDetail();

        // Buka panel
        document.getElementById('faskesDetailPanel').classList.add('active');
    };

    window.closeDetail = function() {
        document.getElementById('faskesDetailPanel').classList.remove('active');
    };

    // =========================================================
    // SMART NAVIGATION & REVIEW TRANSITION
    // =========================================================
    window.openSmartNavigation = function(destLat, destLng, id, name, type) {
        const btn = document.getElementById('btnDirection');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Membuka Rute...';

        const mapUrl = `https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}&travelmode=motorcycle`;

        // Redirect & Switch Mode
        window.open(mapUrl, '_blank');
        
        setTimeout(function() {
            btn.innerHTML = originalText;
            switchToReviewMode(id, name);
        }, 800);
    };

    function switchToReviewMode(id, name) {
        currentFaskesId = id;
        document.getElementById('detailContentBlock').style.display = 'none';
        document.getElementById('detailActions').style.display = 'none';
        document.getElementById('reviewContentBlock').style.display = 'block';
        document.getElementById('reviewActions').style.display = 'flex';
        
        // Reset form
        document.getElementById('reviewRating').value = '0';
        document.getElementById('reviewKomentar').value = '';
        setStarColor(0);
        
        // Scroll body ke atas agar form terlihat
        document.querySelector('.panel-body').scrollTop = 0;
    }

    window.backToDetail = function() {
        document.getElementById('detailContentBlock').style.display = 'block';
        document.getElementById('detailActions').style.display = 'flex';
        document.getElementById('reviewContentBlock').style.display = 'none';
        document.getElementById('reviewActions').style.display = 'none';
    };

    // =========================================================
    // REVIEW SYSTEM LOGIC
    // =========================================================
    const RATING_LABELS = ['', 'Sangat Buruk 😞', 'Buruk 😕', 'Cukup 😐', 'Baik 😊', 'Sangat Baik ⭐'];

    function setStarColor(val) {
        document.querySelectorAll('.review-star').forEach(function(s) {
            var sv = parseInt(s.getAttribute('data-val'));
            if (sv <= val) {
                s.style.color = '#ffc107';
                s.style.transform = 'scale(1.2)';
            } else {
                s.style.color = '#ddd';
                s.style.transform = 'scale(1)';
            }
        });
        var lbl = document.getElementById('starLabel');
        if (lbl) lbl.textContent = RATING_LABELS[val] || '';
    }

    document.querySelectorAll('.review-star').forEach(function(star) {
        star.addEventListener('click', function() {
            var val = parseInt(this.getAttribute('data-val'));
            document.getElementById('reviewRating').value = val;
            setStarColor(val);
        });
        star.addEventListener('mouseenter', function() {
            setStarColor(parseInt(this.getAttribute('data-val')));
        });
        star.addEventListener('mouseleave', function() {
            setStarColor(parseInt(document.getElementById('reviewRating').value) || 0);
        });
    });

    window.submitReview = function() {
        if (!isLoggedIn) {
            Swal.fire({
                title: 'Login Diperlukan',
                text: 'Silakan login sebagai wisatawan untuk memberikan ulasan.',
                icon: 'warning',
                confirmButtonText: 'Login Sekarang',
                showCancelButton: true,
                confirmButtonColor: '#ff7a00'
            }).then(function(r) { if (r.isConfirmed) window.location.href = '/login'; });
            return;
        }
        if (!isWisatawan) {
            Swal.fire({
                title: 'Akses Ditolak',
                text: 'Hanya akun wisatawan yang dapat memberikan ulasan.',
                icon: 'info',
                confirmButtonColor: '#ff7a00'
            });
            return;
        }

        var rating = parseInt(document.getElementById('reviewRating').value);
        var komentar = document.getElementById('reviewKomentar').value.trim();

        if (rating < 1) {
            Swal.fire('Rating Kosong', 'Silakan klik pada bintang untuk memberi skor.', 'warning');
            return;
        }
        if (!komentar) {
            Swal.fire('Ulasan Kosong', 'Silakan tuliskan komentar pengalaman Anda.', 'warning');
            return;
        }

        var btn = document.getElementById('btnSubmitReview');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        btn.disabled = true;

        fetch('/faskes/' + currentFaskesId + '/ulasan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ rating: rating, komentar: komentar })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil! 🎉',
                    text: 'Terima kasih atas ulasan Anda.',
                    icon: 'success',
                    confirmButtonColor: '#28a745'
                }).then(function() { closeDetail(); });
            } else {
                Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Ulasan';
                btn.disabled = false;
            }
        })
        .catch(function() {
            Swal.fire('Error', 'Masalah koneksi database.', 'error');
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Ulasan';
            btn.disabled = false;
        });
    };

    // =========================================================
    // BAGIKAN LOKASI (Web Share API)
    // =========================================================
    window.shareLocation = function() {
        const name = document.getElementById('detailName').textContent;
        const link = document.getElementById('btnDirection').href;
        if (navigator.share) {
            navigator.share({ title: name, text: `Cek faskes ini di WanderMed: ${name}`, url: link });
        } else {
            navigator.clipboard.writeText(link).then(() => alert('Link lokasi berhasil disalin!'));
        }
    };

    // =========================================================
    // FILTER EVENT LISTENERS
    // =========================================================
    // Filter chip kategori
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            document.querySelector('.filter-chip.active').classList.remove('active');
            this.classList.add('active');
            closeDetail();
            updateMarkers();
        });
    });

    // Chip BPJS toggle
    document.getElementById('bpjsChip').addEventListener('click', function() {
        this.classList.toggle('active');
        this.classList.toggle('inactive');
        closeDetail();
        updateMarkers();
    });
    // Set BPJS chip awalnya inactive
    document.getElementById('bpjsChip').classList.add('inactive');

    // Input pencarian
    document.getElementById('searchInput').addEventListener('input', function() {
        closeDetail();
        updateMarkers();
    });

    // =========================================================
    // GEOFENCING LOGIC (HAVERSINE)
    // =========================================================
    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2-lat1);
        var dLon = deg2rad(lon2-lon1); 
        var a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2); 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        var d = R * c; // Distance in km
        return d;
    }

    function deg2rad(deg) {
        return deg * (Math.PI/180);
    }

    window.closeGeofenceModal = function() {
        document.getElementById('geofenceModal').classList.remove('active');
    };

    function checkGeofence(userLat, userLng) {
        if (pariwisataData.length === 0) return;

        let nearestWisata = null;
        let minWisataDist = Infinity;

        // 1. Cari wisata terdekat
        pariwisataData.forEach(w => {
            if(!w.lat || !w.lng) return;
            let dist = getDistanceFromLatLonInKm(userLat, userLng, w.lat, w.lng);
            if(dist < minWisataDist) {
                minWisataDist = dist;
                nearestWisata = w;
            }
        });

        // 2. Jika dalam radius 1km (1.0), trigger modal
        if (nearestWisata && minWisataDist <= 1.0) {
            document.getElementById('gfWisataName').textContent = nearestWisata.name;
            
            // 3. Cari 3 faskes terdekat dari user/wisata
            let faskesWithDist = faskesData.map(f => {
                return {
                    ...f,
                    dist: getDistanceFromLatLonInKm(userLat, userLng, f.lat, f.lng)
                };
            }).filter(f => f.lat && f.lng); // pastikan ada kordinat
            
            // Sort dari yang terdekat
            faskesWithDist.sort((a, b) => a.dist - b.dist);
            
            // Ambil top 3
            let top3 = faskesWithDist.slice(0, 3);
            
            // Render HTML List Faskes
            let listHtml = '';
            top3.forEach(f => {
                let distMeters = Math.round(f.dist * 1000);
                let distText = distMeters > 1000 ? (f.dist).toFixed(1) + ' km' : distMeters + ' m';
                
                let mapsLink = `https://www.google.com/maps/dir/?api=1&destination=${f.lat},${f.lng}`;

                listHtml += `
                    <div class="gm-item">
                        <div class="gm-item-icon">
                            <i class="fas ${f.type === 'Rumah Sakit' ? 'fa-hospital-alt' : (f.type === 'Apotek' ? 'fa-pills' : 'fa-clinic-medical')}"></i>
                        </div>
                        <div class="gm-item-info">
                            <div class="gm-item-name">${f.name}</div>
                            <div class="gm-item-dist"><i class="fas fa-route"></i> ${distText}</div>
                        </div>
                        <a href="${mapsLink}" onclick="event.preventDefault(); openSmartNavigation(${f.lat}, ${f.lng});" class="gm-item-btn">Rute</a>
                    </div>
                `;
            });

            document.getElementById('gfFaskesList').innerHTML = listHtml;

            // Munculkan Modal
            setTimeout(() => {
                document.getElementById('geofenceModal').classList.add('active');
            }, 800);
        }
    }

    // =========================================================
    // TOMBOL LOKASI SAYA (Geolocation) & AUTO CHECK
    // =========================================================
    function locateUser(isAuto = false) {
        if (!navigator.geolocation) {
            if(!isAuto) alert('Browser Anda tidak mendukung geolocation.');
            return;
        }
        
        const btn = document.getElementById('btnMyLocation');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        
        navigator.geolocation.getCurrentPosition(
            pos => {
                const { latitude: lat, longitude: lng } = pos.coords;
                globalUserLocation = { lat, lng };
                map.flyTo([lat, lng], 15, { duration: 1.5 });

                // Tandai posisi pengguna
                L.circleMarker([lat, lng], {
                    radius: 10, color: '#ff7a00', fillColor: '#ff7a00', fillOpacity: 0.5, weight: 3
                }).addTo(map).bindTooltip('<b>📍 Lokasi Anda</b>', { permanent: false });

                btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span>Lokasi Saya</span>';

                // Jalankan Geofencing Check
                checkGeofence(lat, lng);
            },
            err => {
                if(!isAuto) alert('Gagal mendapatkan lokasi. Pastikan izin lokasi browser aktif.');
                btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span>Lokasi Saya</span>';
            }
        );
    }

    document.getElementById('btnMyLocation').addEventListener('click', function() {
        locateUser(false);
    });

    // =========================================================
    // INISIALISASI PERTAMA
    // =========================================================
    updateMarkers();
    
    // Auto trigger pencarian lokasi saat web dibuka
    setTimeout(() => {
        locateUser(true);
    }, 500);

    // Tampilkan panel detail wisata (reuse bottom sheet atau popup sederhana)
    function showWisataDetail(w) {
        // Pakai bottom panel yang sama dengan faskes tapi header ungu
        const name    = document.getElementById('detailName');
        const type    = document.getElementById('detailType');
        const address = document.getElementById('detailAddress');
        const phone   = document.getElementById('detailPhone');
        const badges  = document.getElementById('detailBadges');
        const fac     = document.getElementById('detailFacilities');
        const notes   = document.getElementById('rowNotes');

        name.textContent    = w.name;
        type.textContent    = '🏔 ' + w.kategori;
        address.textContent = w.alamat;
        phone.textContent   = w.telp || '-';
        document.getElementById('detailJam').textContent = 'Harga tiket: ' + (w.tiket ? 'Rp ' + parseInt(w.tiket).toLocaleString('id-ID') : 'Gratis');

        // Status UI
        document.getElementById('rowJam').classList.remove('jam-open');
        document.getElementById('rowJam').querySelector('i').className = 'fas fa-ticket-alt';

        const badgesEl = document.getElementById('detailBadges');
        badgesEl.innerHTML = `<span class="info-chip" style="background:rgba(139, 92, 246, 0.2); color:#C4B5FD;"><i class="fas fa-camera"></i> Pariwisata</span>`;

        // Modul Foto
        const photoContainer = document.getElementById('headerPhotoContainer');
        const photoImg = document.getElementById('detailPhoto');
        if (w.foto) {
             photoContainer.style.display = 'block';
             photoImg.src = w.foto;
        } else {
             photoContainer.style.display = 'none';
        }
        
        // Sembunyikan Fasilitas
        document.getElementById('sectionFasilitas').style.display = 'none';

        if (w.deskripsi) {
            notes.style.display = 'block';
            document.getElementById('detailNotes').textContent = w.deskripsi;
        } else {
            notes.style.display = 'none';
        }

        document.getElementById('detailInfoSection').style.display = 'block';
        document.getElementById('reviewFormSection').style.display = 'none';
        document.getElementById('detailActionSection').style.display = 'flex';
        document.getElementById('reviewActionSection').style.display = 'none';
        document.getElementById('btnJadwal').style.display = 'none';

        document.getElementById('btnDirection').href = `https://www.google.com/maps/dir/?api=1&destination=${w.lat},${w.lng}`;
        document.getElementById('btnDirection').onclick = function(e) {
            e.preventDefault();
            openSmartNavigation(w.lat, w.lng, w.id, w.name, 'Pariwisata');
        };
        document.getElementById('btnCall').href = `tel:${(w.telp || '').replace(/\D/g, '')}`;
        document.getElementById('faskesDetailPanel').classList.add('active');
    }
});
</script>
@endpush
