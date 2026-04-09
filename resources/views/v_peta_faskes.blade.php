@extends('theme.wisatawan')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    @include('theme.navbar')

    <div class="map-page-wrapper">
        <div class="map-overlay-panel animate-fade-up">

            <div class="unified-nav-box shadow-lg">
                <div class="search-input-group">
                    <i class="fas fa-search text-hnb-orange"></i>
                    <input type="text" id="searchInput" class="form-control shadow-none"
                           placeholder="Cari RS, Klinik, atau Apotek..." value="{{ request('q') }}">
                </div>

                <div class="v-separator-map"></div>

                <div class="bpjs-nav-item p-0 border-0">
                    <div class="filter-card-mini {{ request('bpjs') == '1' ? 'active' : '' }}" id="bpjsFilterBtn" style="min-width: 100px; margin: 0;">
                        <i class="fas fa-shield-alt"></i> <span>BPJS</span>
                    </div>
                </div>
            </div>

            <div class="filter-row-unified">
                <div class="filter-card-mini active" data-type="All">
                    <i class="fas fa-map-marked-alt"></i> <span>SEMUA</span>
                </div>
                <div class="filter-card-mini" data-type="Rumah Sakit">
                    <i class="fas fa-hospital-alt"></i> <span>RS / UGD</span>
                </div>
                <div class="filter-card-mini" data-type="Klinik">
                    <i class="fas fa-clinic-medical"></i> <span>KLINIK</span>
                </div>
                <div class="filter-card-mini" data-type="Apotek">
                    <i class="fas fa-pills"></i> <span>APOTEK</span>
                </div>
            </div>
        </div>

        <div id="map"></div>

        <div id="faskesDetailPanel" class="faskes-detail-panel shadow-lg">
            <div class="detail-header-row">
                <div>
                    <h3 id="detailName" class="text-white font-weight-bold mb-0">Nama Fasilitas</h3>
                    <p id="detailType" class="text-hnb-orange small font-weight-bold">Tipe Faskes</p>
                </div>
                <button onclick="closeDetail()" class="btn-close-detail">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="detail-image-box">
                <img id="detailImage" src="" alt="Faskes Preview" onerror="this.src='https://via.placeholder.com/500x300?text=WanderMed+Faskes'">
            </div>
            <div class="badge-group mb-3 d-flex gap-2">
                <span id="badgeBPJS" class="badge badge-success px-3 py-2 radius-hnb d-none">
                    <i class="fas fa-shield-alt mr-1"></i> BPJS OK
                </span>
            </div>
            <div class="info-text small text-white-50 mb-4">
                <p class="mb-2"><i class="fas fa-map-marker-alt text-hnb-orange mr-2"></i> <span id="detailAddress"></span></p>
                <p class="mb-0"><i class="fas fa-phone text-hnb-orange mr-2"></i> <span id="detailPhone"></span></p>
            </div>
            <a id="btnDirection" href="#" target="_blank" class="btn btn-hnb-orange w-100 font-weight-bold py-3 radius-hnb shadow-lg">
                <i class="fas fa-location-arrow mr-2"></i> NAVIGASI SEKARANG
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('map', { zoomControl: false }).setView([-6.5715, 107.7587], 13);
            L.control.zoom({ position: 'bottomright' }).addTo(map);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            var faskesData = [
                {
                    id: 1, name: "RSUD Subang", lat: -6.5710, lng: 107.7600, type: "Rumah Sakit",
                    bpjs: true, address: "Jl. Brigjen Katamso No.37, Subang", phone: "(0260) 411421",
                    img: "https://via.placeholder.com/500x300?text=RSUD+Subang"
                },
                {
                    id: 2, name: "Klinik Pratama Cibogo", lat: -6.5650, lng: 107.7500, type: "Klinik",
                    bpjs: true, address: "Jl. Raya Cibogo No.12, Subang", phone: "(0260) 422111",
                    img: "https://via.placeholder.com/500x300?text=Klinik+Cibogo"
                }
            ];

            var markers = [];

            function updateMarkers() {
                markers.forEach(m => map.removeLayer(m));
                markers = [];

                const typeFilter = document.querySelector('.filter-card-mini.active:not(#bpjsFilterBtn)').dataset.type;
                // Cek apakah tombol BPJS memiliki class 'active'
                const bpjsFilter = document.getElementById('bpjsFilterBtn').classList.contains('active');
                const searchQuery = document.getElementById('searchInput').value.toLowerCase();

                faskesData.forEach(f => {
                    const matchType = (typeFilter === "All" || f.type === typeFilter);
                    const matchBPJS = (!bpjsFilter || f.bpjs);
                    const matchSearch = f.name.toLowerCase().includes(searchQuery);

                    if (matchType && matchBPJS && matchSearch) {
                        var m = L.marker([f.lat, f.lng]).addTo(map);
                        m.on('click', function() {
                            showDetail(f);
                            map.flyTo([f.lat, f.lng], 15);
                        });
                        markers.push(m);
                    }
                });
            }

            window.showDetail = function(data) {
                document.getElementById('detailName').innerText = data.name;
                document.getElementById('detailType').innerText = data.type;
                document.getElementById('detailAddress').innerText = data.address;
                document.getElementById('detailPhone').innerText = data.phone;
                document.getElementById('detailImage').src = data.img;
                document.getElementById('btnDirection').href = `http://googleusercontent.com/maps.google.com/3{data.lat},${data.lng}`;
                data.bpjs ? document.getElementById('badgeBPJS').classList.remove('d-none') : document.getElementById('badgeBPJS').classList.add('d-none');
                document.getElementById('faskesDetailPanel').classList.add('active');
            }

            window.closeDetail = function() {
                document.getElementById('faskesDetailPanel').classList.remove('active');
            }

            // Event Listener Kategori
            document.querySelectorAll('.filter-card-mini:not(#bpjsFilterBtn)').forEach(b => {
                b.addEventListener('click', function() {
                    document.querySelector('.filter-card-mini.active:not(#bpjsFilterBtn)').classList.remove('active');
                    this.classList.add('active');
                    updateMarkers();
                });
            });

            // Event Listener Tombol BPJS (Toggle on/off)
            document.getElementById('bpjsFilterBtn').addEventListener('click', function() {
                this.classList.toggle('active');
                updateMarkers();
            });

            document.getElementById('searchInput').addEventListener('input', updateMarkers);
            updateMarkers();
        });
    </script>
@endpush
