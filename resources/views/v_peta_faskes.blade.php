{{-- ============================================================
     Halaman Peta Faskes Wisatawan – WanderMed
     Dirombak total: UI friendly, bottom sheet informatif,
     marker custom, filter visual, dan info fasilitas lengkap.
     ============================================================ --}}
@extends('theme.wisatawan')

@push('styles')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* ===================== LAYOUT PETA ===================== */
        .map-page-wrapper {
            position: fixed;
            top: 85px;
            left: 0;
            width: 100vw;
            height: calc(100vh - 85px);
            z-index: 1;
        }
        #map {
            width: 100%;
            height: 100%;
        }

        /* ===================== OVERLAY TOP PANEL ===================== */
        .map-overlay-panel {
            position: absolute;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 96%;
            max-width: 720px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }
        .map-overlay-panel > * { pointer-events: auto; }

        /* Search + BPJS Unified Box */
        .unified-nav-box {
            background: rgba(13, 27, 46, 0.97);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.45);
        }
        .search-input-group {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }
        .search-input-group i { color: var(--hnb-orange); font-size: 15px; flex-shrink: 0; }
        .search-input-group input {
            background: transparent !important;
            border: none !important;
            color: #fff !important;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            width: 100%;
            outline: none;
        }
        .search-input-group input::placeholder { color: rgba(255,255,255,0.4); }

        /* Separator vertikal */
        .v-separator-map {
            width: 1px;
            height: 24px;
            background: rgba(255,255,255,0.15);
            flex-shrink: 0;
        }

        /* Tombol Lokasi Saya */
        .btn-my-location {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            padding: 7px 13px;
            color: rgba(255,255,255,0.8);
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            flex-shrink: 0;
        }
        .btn-my-location:hover { background: rgba(255, 122, 0, 0.2); color: var(--hnb-orange); border-color: var(--hnb-orange); }
        .btn-my-location i { font-size: 14px; }

        /* BPJS Toggle Chip */
        .bpjs-chip {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 10px;
            border: 1.5px solid rgba(28, 200, 138, 0.4);
            background: rgba(28, 200, 138, 0.08);
            color: #1cc88a;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
            flex-shrink: 0;
            letter-spacing: 0.3px;
        }
        .bpjs-chip i { font-size: 13px; }
        .bpjs-chip.inactive {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.5);
        }

        /* ===================== FILTER KATEGORI ===================== */
        .filter-row-unified {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
            scrollbar-width: none;
        }
        .filter-row-unified::-webkit-scrollbar { display: none; }

        .filter-chip {
            cursor: pointer;
            background: rgba(13, 27, 46, 0.95);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 9px 16px;
            display: flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
            transition: all 0.3s ease;
            color: rgba(255,255,255,0.65);
            font-size: 12px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        }
        .filter-chip i { font-size: 14px; }
        .filter-chip:hover { background: rgba(255,122,0,0.12); border-color: rgba(255,122,0,0.4); color: var(--hnb-orange); }
        .filter-chip.active {
            background: var(--hnb-orange);
            border-color: var(--hnb-orange);
            color: #fff;
            box-shadow: 0 4px 20px rgba(255,122,0,0.4);
        }

        /* ===================== RESULT COUNT BADGE ===================== */
        .result-count-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 1000;
            background: rgba(13, 27, 46, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 12px;
            font-family: 'Poppins', sans-serif;
            color: rgba(255,255,255,0.7);
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }
        .result-count-badge strong { color: var(--hnb-orange); font-size: 14px; }

        /* ===================== BOTTOM SHEET DETAIL ===================== */
        .faskes-detail-panel {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translate(-50%, 105%); /* Tersembunyi di bawah */
            width: 100%;
            max-width: 560px;
            background: #0d1b2e;
            border-radius: 24px 24px 0 0;
            z-index: 1050;
            box-shadow: 0 -8px 40px rgba(0,0,0,0.7);
            transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
            /* Handle geser */
            padding-top: 8px;
        }
        .faskes-detail-panel.active {
            transform: translate(-50%, 0);
        }

        /* Handle tarik */
        .panel-handle {
            width: 40px;
            height: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            margin: 0 auto 14px;
        }

        /* Header Panel */
        .panel-header {
            padding: 0 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .panel-faskes-name {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 4px;
        }
        .panel-faskes-type {
            font-size: 12px;
            font-weight: 600;
            color: var(--hnb-orange);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .panel-close-btn {
            background: rgba(255,255,255,0.08);
            border: none;
            color: rgba(255,255,255,0.6);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.3s;
            margin-left: 12px;
        }
        .panel-close-btn:hover { background: rgba(231,74,59,0.2); color: #e74a3b; }

        /* Status Badges Row */
        .panel-badges {
            display: flex;
            gap: 8px;
            padding: 14px 20px;
            flex-wrap: wrap;
        }
        .info-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }
        .info-chip.status-open { background: rgba(28,200,138,0.15); color: #1cc88a; border: 1px solid rgba(28,200,138,0.3); }
        .info-chip.status-closed { background: rgba(231,74,59,0.15); color: #e74a3b; border: 1px solid rgba(231,74,59,0.3); }
        .info-chip.bpjs-yes { background: rgba(54,185,204,0.15); color: #36b9cc; border: 1px solid rgba(54,185,204,0.3); }
        .info-chip.bpjs-no { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.1); }
        .info-chip.chip-distance { background: rgba(255,122,0,0.12); color: var(--hnb-orange); border: 1px solid rgba(255,122,0,0.25); }
        .info-chip i { font-size: 12px; }

        /* Scrollable body panel */
        .panel-body {
            padding: 0 20px 24px;
            max-height: 55vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .panel-body::-webkit-scrollbar { width: 3px; }
        .panel-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }

        /* Section dalam panel */
        .panel-section { margin-bottom: 18px; }
        .panel-section-title {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            margin-bottom: 10px;
        }

        /* Info Baris (Alamat, Telepon, Jam) */
        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 14px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            margin-bottom: 8px;
        }
        .info-row i {
            color: var(--hnb-orange);
            width: 16px;
            text-align: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .info-row-content { flex: 1; }
        .info-row-label { font-size: 10px; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .info-row-value { font-size: 13.5px; color: rgba(255,255,255,0.85); font-weight: 500; line-height: 1.4; }

        /* Jam Operasional highlight */
        .info-row.jam-open { background: rgba(28,200,138,0.06); border-color: rgba(28,200,138,0.15); }
        .info-row.jam-open i { color: #1cc88a; }

        /* Grid Fasilitas (layanan tersedia) */
        .facilities-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .facility-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 10px;
            font-size: 12.5px;
            color: rgba(255,255,255,0.7);
            font-weight: 500;
        }
        .facility-item .fac-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }
        .fac-icon.red   { background: rgba(231,74,59,0.18); color: #e74a3b; }
        .fac-icon.blue  { background: rgba(78,115,223,0.18); color: #4e73df; }
        .fac-icon.green { background: rgba(28,200,138,0.18); color: #1cc88a; }
        .fac-icon.teal  { background: rgba(54,185,204,0.18); color: #36b9cc; }
        .fac-icon.orange{ background: rgba(255,122,0,0.18); color: var(--hnb-orange); }
        .fac-icon.yellow{ background: rgba(246,194,62,0.18); color: #f6c23e; }
        .fac-icon.purple{ background: rgba(130,100,200,0.18); color: #a78de8; }

        /* Tombol Aksi Utama */
        .panel-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 16px 20px 22px;
            border-top: 1px solid rgba(255,255,255,0.07);
            background: #0d1b2e;
        }
        .btn-action-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-action-primary.navigate {
            background: var(--hnb-orange);
            color: #fff;
            grid-column: span 2;
            padding: 14px;
            font-size: 14px;
            box-shadow: 0 6px 24px rgba(255,122,0,0.4);
        }
        .btn-action-primary.navigate:hover { background: var(--hnb-orange-hover); transform: translateY(-2px); }
        .btn-action-primary.call {
            background: rgba(28,200,138,0.15);
            color: #1cc88a;
            border: 1px solid rgba(28,200,138,0.3);
        }
        .btn-action-primary.call:hover { background: rgba(28,200,138,0.25); }
        .btn-action-primary.share {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-action-primary.share:hover { background: rgba(255,255,255,0.12); }

        /* ===================== CUSTOM MARKER ===================== */
        .wm-marker-pin {
            width: 36px;
            height: 36px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.35);
        }
        .wm-marker-pin i {
            transform: rotate(45deg);
            font-size: 14px;
            color: #fff;
        }
        .marker-rs      { background: #e74a3b; }
        .marker-klinik  { background: #4e73df; }
        .marker-apotek  { background: #1cc88a; }

        /* ===================== PANEL FLOATING ZOOM ===================== */
        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4) !important;
            border-radius: 12px !important;
            overflow: hidden;
        }
        .leaflet-control-zoom a {
            background: rgba(13,27,46,0.97) !important;
            color: #fff !important;
            border: none !important;
            border-bottom: 1px solid rgba(255,255,255,0.07) !important;
            font-size: 18px !important;
            line-height: 30px !important;
            width: 36px !important;
            height: 36px !important;
            transition: background 0.2s !important;
        }
        .leaflet-control-zoom a:hover { background: rgba(255,122,0,0.3) !important; color: var(--hnb-orange) !important; }
        .leaflet-control-zoom-out { border-bottom: none !important; }

        /* Dark Tile Map (menggunakan filter CSS) */
        .leaflet-tile-pane {
            filter: brightness(0.72) saturate(0.9);
        }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 576px) {
            .map-overlay-panel { width: 94%; top: 12px; }
            .unified-nav-box { padding: 10px 14px; gap: 8px; }
            .btn-my-location span { display: none; }
            .faskes-detail-panel { max-width: 100%; border-radius: 20px 20px 0 0; }
            .facilities-grid { grid-template-columns: 1fr; }
            .result-count-badge { display: none; }
        }

        /* =====================================================================
         * LIGHT MODE — Override semua elemen peta agar sesuai tema terang
         * Dipicu oleh class .light-mode pada <body> (dari script-wisatawan.js)
         * ===================================================================== */

        /* Peta lebih cerah di light mode */
        body.light-mode .leaflet-tile-pane {
            filter: brightness(1) saturate(1.1);
        }

        /* --- Search Box & Unified Nav --- */
        body.light-mode .unified-nav-box {
            background: rgba(255, 255, 255, 0.97);
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }
        body.light-mode .search-input-group input {
            color: #1a2a44 !important;
        }
        body.light-mode .search-input-group input::placeholder {
            color: rgba(26, 42, 68, 0.4);
        }
        body.light-mode .v-separator-map {
            background: rgba(0, 0, 0, 0.1);
        }

        /* --- Tombol Lokasi Saya --- */
        body.light-mode .btn-my-location {
            background: rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 0, 0, 0.12);
            color: #1a2a44;
        }
        body.light-mode .btn-my-location:hover {
            background: rgba(255, 122, 0, 0.1);
            border-color: var(--hnb-orange);
            color: var(--hnb-orange);
        }

        /* --- BPJS Chip --- */
        body.light-mode .bpjs-chip {
            background: rgba(28, 200, 138, 0.1);
            border-color: rgba(28, 200, 138, 0.4);
        }
        body.light-mode .bpjs-chip.inactive {
            background: rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 0, 0, 0.1);
            color: rgba(26, 42, 68, 0.45);
        }

        /* --- Filter Chips Kategori --- */
        body.light-mode .filter-chip {
            background: rgba(255, 255, 255, 0.97);
            border-color: rgba(0, 0, 0, 0.08);
            color: #1a2a44;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }
        body.light-mode .filter-chip:hover {
            background: rgba(255, 122, 0, 0.08);
            border-color: rgba(255, 122, 0, 0.4);
            color: var(--hnb-orange);
        }
        body.light-mode .filter-chip.active {
            background: var(--hnb-orange);
            border-color: var(--hnb-orange);
            color: #fff;
        }

        /* --- Result Count Badge --- */
        body.light-mode .result-count-badge {
            background: rgba(255, 255, 255, 0.97);
            border-color: rgba(0, 0, 0, 0.08);
            color: #1a2a44;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        /* --- Zoom Controls Leaflet --- */
        body.light-mode .leaflet-control-zoom a {
            background: #ffffff !important;
            color: #1a2a44 !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06) !important;
            box-shadow: none !important;
        }
        body.light-mode .leaflet-control-zoom a:hover {
            background: rgba(255, 122, 0, 0.1) !important;
            color: var(--hnb-orange) !important;
        }
        body.light-mode .leaflet-control-zoom {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.12) !important;
        }

        /* --- Bottom Sheet Panel utama --- */
        body.light-mode .faskes-detail-panel {
            background: #f4f7fb !important;
            box-shadow: 0 -8px 40px rgba(0, 0, 0, 0.18) !important;
        }
        body.light-mode .panel-handle {
            background: rgba(0, 0, 0, 0.15);
        }
        body.light-mode .panel-header {
            border-bottom-color: rgba(0, 0, 0, 0.07);
        }
        body.light-mode .panel-faskes-name {
            color: #1a2a44 !important;
        }

        /* --- Tombol tutup panel --- */
        body.light-mode .panel-close-btn {
            background: rgba(0, 0, 0, 0.06);
            color: #1a2a44;
        }
        body.light-mode .panel-close-btn:hover {
            background: rgba(231, 74, 59, 0.12);
            color: #e74a3b;
        }

        /* --- Section title label --- */
        body.light-mode .panel-section-title {
            color: rgba(26, 42, 68, 0.4);
        }

        /* --- Info Rows (Alamat, Jam, Telpon) --- */
        body.light-mode .info-row {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(0, 0, 0, 0.07);
        }
        body.light-mode .info-row-label {
            color: rgba(26, 42, 68, 0.4) !important;
        }
        body.light-mode .info-row-value {
            color: #1a2a44 !important;
        }
        body.light-mode .info-row.jam-open {
            background: rgba(28, 200, 138, 0.07);
            border-color: rgba(28, 200, 138, 0.25);
        }

        /* --- Facility Grid Items --- */
        body.light-mode .facility-item {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(0, 0, 0, 0.07) !important;
            color: #1a2a44 !important;
        }

        /* --- Scrollbar panel body di light mode --- */
        body.light-mode .panel-body::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.12);
        }

        /* --- Tombol Aksi di Panel Bawah --- */
        body.light-mode .panel-actions {
            background: #f4f7fb !important;
            border-top-color: rgba(0, 0, 0, 0.07) !important;
        }
        body.light-mode .btn-action-primary.share {
            background: rgba(0, 0, 0, 0.05);
            color: #1a2a44;
            border-color: rgba(0, 0, 0, 0.1);
        }
        body.light-mode .btn-action-primary.share:hover {
            background: rgba(0, 0, 0, 0.09);
        }
    </style>
@endpush

@section('content')
    @include('theme.navbar')

    <div class="map-page-wrapper">

        {{-- ========= OVERLAY PANEL ATAS ========= --}}
        <div class="map-overlay-panel">

            {{-- Search Bar + Tombol Lokasi + BPJS --}}
            <div class="unified-nav-box">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari RS, Klinik, atau Apotek...">
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

            {{-- Filter Kategori Master --}}
            <div class="filter-row-unified" style="display: flex; gap: 10px; align-items: center; background: #fff; padding: 10px; border-radius: 12px; margin-top: 10px;">
                <label for="masterFilter" style="font-weight: 600; color: #1e293b; margin: 0;"><i class="fas fa-filter"></i> Kategori:</label>
                <select id="masterFilter" class="form-control filter-select" style="width: auto; border-radius: 8px; border: 1px solid #cbd5e1;">
                    <option value="All">Semua Destinasi</option>
                    <option value="Faskes">Hanya Faskes</option>
                    <option value="Pariwisata">Hanya Pariwisata</option>
                </select>
            </div>
            
            {{-- Filter Faskes Sub-Kategori --}}
            <div class="filter-row-unified" id="faskesSubFilter" style="margin-top: 10px;">
                <div class="filter-chip active" data-type="AllFaskes">
                    <i class="fas fa-th-large"></i> Semua Faskes
                </div>
                <div class="filter-chip" data-type="Rumah Sakit">
                    <i class="fas fa-hospital-alt" style="color: #e74a3b;"></i> RS / UGD
                </div>
                <div class="filter-chip" data-type="Klinik">
                    <i class="fas fa-clinic-medical" style="color: #4e73df;"></i> Klinik
                </div>
                <div class="filter-chip" data-type="Apotek">
                    <i class="fas fa-pills" style="color: #1cc88a;"></i> Apotek
                </div>
                <div class="filter-chip" data-type="Puskesmas">
                    <i class="fas fa-heartbeat" style="color: #f6c23e;"></i> Puskesmas
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

                {{-- Fasilitas --}}
                <div class="panel-section">
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
            <div class="panel-actions">
                <a id="btnDirection" href="#" target="_blank" class="btn-action-primary navigate">
                    <i class="fas fa-location-arrow"></i> Navigasi via Google Maps
                </a>
                <a id="btnCall" href="tel:+62" class="btn-action-primary call">
                    <i class="fas fa-phone-alt"></i> Hubungi
                </a>
                <button class="btn-action-primary share" onclick="shareLocation()">
                    <i class="fas fa-share-alt"></i> Bagikan
                </button>
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

        fac.innerHTML = '';
        if (w.foto) {
             fac.innerHTML = `<img src="${w.foto}" style="width:100%; border-radius:12px; height:150px; object-fit:cover;">`;
        }

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
