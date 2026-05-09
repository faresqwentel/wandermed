(function () {
    var mapEl = document.getElementById('hero-bg-map');
    if (!mapEl) return;

    // Pusat peta: Subang, Jawa Barat
    var CENTER = [-6.5744, 107.7561];
    var ZOOM   = 13;

    var map = L.map('hero-bg-map', {
        center: CENTER,
        zoom: ZOOM,
        zoomControl:      false,
        scrollWheelZoom:  false,
        doubleClickZoom:  false,
        dragging:         false,
        touchZoom:        false,
        keyboard:         false,
        attributionControl: false
    });

    // Map Tile selalu menggunakan tema gelap/abu (Dark Matter) untuk kedua mode 
    // agar kontras marker lebih baik dan kesan premium tetap terjaga.
    var tileUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

    L.tileLayer(tileUrl, {
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);

    // Lokasi faskes nyata di Subang (demo markers)
    var spots = [
        { latlng: [-6.5744, 107.7561], label: 'RSUD Subang',         type: 'ugd'    },
        { latlng: [-6.5630, 107.7480], label: 'Klinik Pratama Sehat', type: 'klinik' },
        { latlng: [-6.5820, 107.7650], label: 'Apotek Kimia Farma',   type: 'apotek' },
        { latlng: [-6.5700, 107.7720], label: 'Puskesmas Subang',     type: 'ugd'    },
        { latlng: [-6.5500, 107.7400], label: 'Klinik Medika',        type: 'klinik' },
        { latlng: [-6.5900, 107.7550], label: 'RS Hermina Subang',    type: 'ugd'    },
        { latlng: [-6.5780, 107.7300], label: 'Apotek Century',       type: 'apotek' },
    ];

    var colors = { ugd: '#ef4444', klinik: '#ff7a00', apotek: '#10b981' };

    spots.forEach(function (s) {
        var color = colors[s.type] || '#ff7a00';
        var icon = L.divIcon({
            className: '',
            html: '<div style="width:14px;height:14px;border-radius:50%;background:' + color + ';box-shadow:0 0 0 4px ' + color.replace(')', ',0.3)').replace('rgb', 'rgba') + ';animation:markerPulse 2s infinite;"></div>',
            iconSize:   [14, 14],
            iconAnchor: [7, 7]
        });
        L.marker(s.latlng, { icon: icon })
         .addTo(map)
         .bindTooltip(s.label, { permanent: false, direction: 'top', className: 'hero-map-tooltip' });
    });

    // Gentle slow pan animation setelah load
    setTimeout(function () {
        map.flyTo([-6.5680, 107.7580], 13, { duration: 12, easeLinearity: 0.1 });
    }, 1200);

    // Aktifkan dragging hanya jika hero tidak di-scroll (opsional, dibiarkan disabled)
})();