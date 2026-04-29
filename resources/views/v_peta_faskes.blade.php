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
            <div class="panel-body">

                {{-- Modul Foto (Ditampilkan Jika Ada - Misal Pariwisata) --}}
                <div id="headerPhotoContainer" style="display: none; padding: 0 15px 15px 15px;">
                    <img id="detailPhoto" src="" alt="Foto Lokasi" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                </div>

                {{-- Info Utama --}}
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
                            <div class="info-row-label">Jam Operasional / Status</div>
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

                {{-- Fasilitas --}}
                <div class="panel-section" id="sectionFasilitas">
                    <div class="panel-section-title">Fasilitas Tersedia</div>
                    <div class="facilities-grid" id="detailFacilities">
                        {{-- Diisi oleh JS --}}
                    </div>
                </div>

                {{-- Catatan Khusus --}}
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

            {{-- Tombol Aksi --}}
            <div class="panel-actions" style="display: flex; flex-direction: column; gap: 8px;">
                <a id="btnDirection" href="#" target="_blank" class="btn btn-hnb-orange w-100 py-3 font-weight-bold shadow-lg" style="border-radius: 12px; font-size: 1.15rem;">
                    <i class="fas fa-location-arrow mr-2"></i> Mulai Rute Navigasi
                </a>
                <div class="d-flex" style="gap: 10px;">
                    <a id="btnCall" href="tel:+62" class="btn btn-outline-secondary w-50 py-2" style="border-radius: 10px;">
                        <i class="fas fa-phone-alt mr-1"></i> Hubungi
                    </a>
                    <button class="btn btn-outline-secondary w-50 py-2 share" onclick="shareLocation()" style="border-radius: 10px;">
                        <i class="fas fa-share-alt mr-1"></i> Bagikan
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

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
        // Isi nama & tipe
        document.getElementById('detailName').textContent    = data.name;
        document.getElementById('detailType').textContent    = data.type;
        document.getElementById('detailAddress').textContent = data.address;
        document.getElementById('detailJam').textContent     = data.jam;
        document.getElementById('detailPhone').textContent   = data.phone;

        // Warna baris jam (hijau jika buka)
        const rowJam = document.getElementById('rowJam');
        if (data.status === 'open') {
            rowJam.classList.add('jam-open');
            rowJam.querySelector('i').className = 'fas fa-clock';
        } else {
            rowJam.classList.remove('jam-open');
        }

        // Badge status chips
        const badgesEl = document.getElementById('detailBadges');
        const statusChip = data.status === 'open'
            ? `<span class="info-chip status-open"><i class="fas fa-circle" style="font-size:8px;"></i> Buka Sekarang</span>`
            : `<span class="info-chip status-closed"><i class="fas fa-circle" style="font-size:8px;"></i> Sedang Tutup</span>`;
        const bpjsChip = data.bpjs
            ? `<span class="info-chip bpjs-yes"><i class="fas fa-shield-alt"></i> Terima BPJS</span>`
            : `<span class="info-chip bpjs-no"><i class="fas fa-times-circle"></i> Non-BPJS</span>`;
        badgesEl.innerHTML = statusChip + bpjsChip + `<span class="info-chip chip-distance"><i class="fas fa-route"></i> ~850m dari Anda</span>`;

        // Fasilitas Grid
        const facEl = document.getElementById('detailFacilities');
        facEl.innerHTML = data.facilities.map(f => `
            <div class="facility-item">
                <div class="${f.icon}"><i class="${f.ico}"></i></div>
                ${f.label}
            </div>`).join('');

        // Catatan khusus
        const notesSection = document.getElementById('rowNotes');
        if (data.notes) {
            notesSection.style.display = 'block';
            document.getElementById('detailNotes').textContent = data.notes;
        } else {
            notesSection.style.display = 'none';
        }

        // Tombol navigasi Google Maps
        document.getElementById('btnDirection').href =
            `https://www.google.com/maps/dir/?api=1&destination=${data.lat},${data.lng}`;

        // Tombol telepon
        document.getElementById('btnCall').href = `tel:${data.phone.replace(/\D/g, '')}`;

        // Konfigurasi Visibilitas Section
        document.getElementById('headerPhotoContainer').style.display = 'none';
        document.getElementById('sectionFasilitas').style.display = 'block';

        // Buka panel
        document.getElementById('faskesDetailPanel').classList.add('active');
    };

    // Tutup bottom sheet
    window.closeDetail = function() {
        document.getElementById('faskesDetailPanel').classList.remove('active');
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
    // TOMBOL LOKASI SAYA (Geolocation)
    // =========================================================
    document.getElementById('btnMyLocation').addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolocation.');
            return;
        }
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        const btn = this;
        navigator.geolocation.getCurrentPosition(
            pos => {
                const { latitude: lat, longitude: lng } = pos.coords;
                map.flyTo([lat, lng], 15, { duration: 1.5 });

                // Tandai posisi pengguna
                L.circleMarker([lat, lng], {
                    radius: 10, color: '#ff7a00', fillColor: '#ff7a00', fillOpacity: 0.5, weight: 3
                }).addTo(map).bindTooltip('<b>📍 Lokasi Anda</b>', { permanent: false });

                btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span>Lokasi Saya</span>';
            },
            err => {
                alert('Gagal mendapatkan lokasi. Pastikan izin lokasi browser aktif.');
                btn.innerHTML = '<i class="fas fa-crosshairs"></i> <span>Lokasi Saya</span>';
            }
        );
    });

    // =========================================================
    // INISIALISASI PERTAMA
    // =========================================================
    updateMarkers();

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

        badgesEl = document.getElementById('detailBadges');
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
        
        // Sembunyikan Fasilitas (karena data wisata belum pakai)
        document.getElementById('sectionFasilitas').style.display = 'none';

        if (w.deskripsi) {
            notes.style.display = 'block';
            document.getElementById('detailNotes').textContent = w.deskripsi;
        } else {
            notes.style.display = 'none';
        }

        document.getElementById('btnDirection').href = `https://www.google.com/maps/dir/?api=1&destination=${w.lat},${w.lng}`;
        document.getElementById('btnCall').href = `tel:${(w.telp || '').replace(/\D/g, '')}`;
        document.getElementById('faskesDetailPanel').classList.add('active');
    }
});
</script>
@endpush
