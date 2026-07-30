@extends('layouts.portal')

@section('title', 'Peta Wilayah Desa & GIS Interaktif')
@section('description', 'Peta digital interaktif sebaran fasilitas umum, lokasi penting, dan batas wilayah Desa ' . ($config->nama_desa ?? 'Serdang'))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    /* CSS Container Peta & Tile Reflow Optimization */
    #map-wrapper {
        position: relative;
        width: 100%;
        border-radius: 1.5rem;
        overflow: hidden;
    }
    #map {
        width: 100%;
        height: 500px;
        max-height: 70vh;
        border-radius: 1.5rem;
        z-index: 10;
        background-color: #e5e7eb;
    }
    @media (max-width: 640px) {
        #map { height: 400px; }
    }
    
    /* Leaflet Popup & Custom Controls Styling */
    .leaflet-container {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 1.25rem !important;
        padding: 0.25rem !important;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.2) !important;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .leaflet-popup-tip {
        background: white !important;
    }
    .location-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .location-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.12);
    }
</style>
@endpush

@section('content')

{{-- ===== HERO HEADER HALAMAN ===== --}}
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-16 relative overflow-hidden shadow-2xl" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="text-amber-400 text-xs font-extrabold uppercase tracking-widest bg-white/10 px-4 py-1.5 rounded-full border border-amber-400/30 mb-3 inline-block">PEMETAAN GEOSPASIAL</span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-md">Peta Digital &amp; Batas Wilayah Desa</h1>
        <p class="text-emerald-100 max-w-2xl mx-auto text-sm sm:text-base mt-2 opacity-90">Peta interaktif sebaran fasilitas umum, infrastruktur, titik lokasi penting, dan garis batas wilayah Desa {{ $config->nama_desa ?? 'Serdang' }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    
    {{-- ===== SEKSI 1: MAP CONTAINER & CONTROLS ===== --}}
    <div id="map-container" class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200 scroll-mt-24">
        {{-- Map Header Controls --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6 pb-5 border-b border-slate-200">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                    <span>🗺️</span> Peta Sebaran GIS &amp; Batas Wilayah
                </h2>
                <p class="text-xs text-slate-500 mt-1">Gunakan layer switcher di pojok kanan atas peta untuk mengganti mode Peta Jalan / Foto Satelit</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3.5 py-1.5 rounded-full border border-emerald-200">
                    📍 {{ $lokasis->count() }} Fasilitas &amp; Lokasi
                </span>
                <span class="bg-amber-100 text-amber-800 text-xs font-extrabold px-3.5 py-1.5 rounded-full border border-amber-200">
                    📐 {{ $areas->count() }} Wilayah Polygon
                </span>
            </div>
        </div>

        {{-- Leaflet Map Container --}}
        <div id="map-wrapper" class="shadow-inner border border-slate-300">
            <div id="map"></div>
        </div>
    </div>

    {{-- ===== SEKSI 2: DAFTAR LOKASI & ALAMAT FASILITAS DESA ===== --}}
    <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 mb-8 border-b border-slate-200">
            <div>
                <span class="text-amber-600 text-xs font-extrabold uppercase tracking-widest bg-amber-50 px-3 py-1 rounded-full border border-amber-200">DIREKTORI GEOSPASIAL</span>
                <h2 class="text-2xl font-extrabold text-slate-900 mt-2 flex items-center gap-2">
                    <span>🏢</span> Daftar Fasilitas &amp; Alamat Tertanda Desa
                </h2>
                <p class="text-xs text-slate-500 mt-1">Klik tombol "Lihat di Peta 📍" pada kartu untuk mengarahkan kamera peta ke titik lokasi secara otomatis</p>
            </div>

            {{-- Live Search Input --}}
            <div class="w-full md:w-80">
                <div class="relative">
                    <input type="text" id="searchLocation" onkeyup="filterLocations()" placeholder="Cari nama lokasi / fasilitas..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs shadow-sm">
                    <span class="absolute left-3.5 top-2.5 text-slate-400 text-sm">🔍</span>
                </div>
            </div>
        </div>

        {{-- Facility Cards Grid --}}
        @if($lokasis->count() > 0)
            <div id="locationGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($lokasis as $loc)
                    <div class="location-card glass-pill rounded-2xl p-5 border border-slate-200/80 bg-white/70 flex flex-col justify-between"
                         data-name="{{ strtolower($loc->nama) }}"
                         data-category="{{ strtolower($loc->kategori_point->nama ?? '') }}">
                        <div>
                            {{-- Card Image or Fallback Icon --}}
                            <div class="h-40 rounded-xl overflow-hidden bg-slate-100 mb-4 relative shadow-inner border border-slate-200">
                                <img src="{{ get_media_url($loc->foto, 'galeri') }}" alt="{{ $loc->nama }}" class="w-full h-full object-cover">
                                @if($loc->kategori_point)
                                    <span class="absolute top-3 left-3 bg-emerald-950/90 text-amber-300 text-[10px] font-extrabold px-3 py-1 rounded-full border border-amber-400/30 shadow backdrop-blur-md">
                                        {{ $loc->kategori_point->nama }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-extrabold text-slate-900 text-base leading-snug mb-1">{{ $loc->nama }}</h3>
                            
                            @if($loc->desk)
                                <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 mb-3">{{ $loc->desk }}</p>
                            @endif

                            <div class="text-[11px] text-slate-400 font-mono mb-4 flex items-center gap-1.5">
                                <span>🌐</span>
                                <span>Lat: {{ $loc->lat }}, Lng: {{ $loc->lng }}</span>
                            </div>
                        </div>

                        <button onclick="focusOnMap({{ $loc->lat }}, {{ $loc->lng }}, '{{ addslashes($loc->nama) }}')"
                            class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-4 py-2.5 rounded-xl text-xs transition shadow-md border border-amber-400/30">
                            <span>📍</span> Lihat di Peta ➔
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Empty Search Result Message --}}
            <div id="noResults" class="hidden text-center py-12 text-slate-400">
                <div class="text-4xl mb-2">🔍</div>
                <p class="font-extrabold text-slate-700">Lokasi Tidak Ditemukan</p>
                <p class="text-xs text-slate-500 mt-1">Coba kata kunci pencarian yang lain.</p>
            </div>
        @else
            <div class="text-center py-12 text-slate-400">
                <div class="text-5xl mb-3">🗺️</div>
                <p class="font-extrabold text-slate-700">Belum Ada Lokasi Fasilitas Tertanda</p>
                <p class="text-xs text-slate-500 mt-1">Penanda lokasi fasilitas umum dapat ditambahkan melalui panel admin.</p>
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    let map, markersMap = {};

    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = {{ $defaultLat ?? -2.98 }};
        const defaultLng = {{ $defaultLng ?? 104.75 }};

        // 1. Initialize Map
        map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([defaultLat, defaultLng], 14);

        // 2. Tile Layers
        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors | GIS Desa {{ $config->nama_desa ?? "Serdang" }}'
        });

        const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles © Esri — Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping'
        });

        // Add default layer
        osmLayer.addTo(map);

        const baseMaps = {
            "🗺️ Peta Jalan (OpenStreetMap)": osmLayer,
            "🛰️ Foto Satelit (Esri Satellite)": satelliteLayer
        };

        L.control.layers(baseMaps, null, { position: 'topright' }).addTo(map);

        // Marker Utama Kantor Desa Serdang
        const villageCenterMarker = L.marker([defaultLat, defaultLng], {
            title: "Pemerintah Desa {{ $config->nama_desa ?? 'Serdang' }}"
        }).addTo(map);

        villageCenterMarker.bindPopup(`
            <div style="font-family:'Plus Jakarta Sans',sans-serif;" class="p-2.5 max-w-xs">
                <span class="inline-block bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full mb-1.5 border border-emerald-200">PUSAT PEMERINTAHAN DESA</span>
                <h4 class="font-extrabold text-sm text-slate-900">Kantor Desa {{ $config->nama_desa ?? 'Serdang' }}</h4>
                <p class="text-xs text-slate-600 mt-1">Kec. {{ $config->nama_kecamatan ?? 'Meranti' }}, Kab. {{ $config->nama_kabupaten ?? 'Asahan' }}</p>
                <div class="text-[10px] text-slate-400 font-mono mt-2 pt-2 border-t border-slate-100">📍 Lat: ${defaultLat}, Lng: ${defaultLng}</div>
            </div>
        `);

        // 3. Tile Reflow Fix (Solves grey box issue on initial load / container resize)
        function triggerTileReflow() {
            setTimeout(function() {
                if (map) {
                    map.invalidateSize(true);
                }
            }, 300);
        }

        // Trigger reflow immediately and on events
        triggerTileReflow();
        window.addEventListener('resize', triggerTileReflow);
        window.addEventListener('load', triggerTileReflow);

        // 4. Add Location Markers
        const lokasis = @json($lokasis);

        lokasis.forEach(function (loc) {
            if (loc.lat && loc.lng) {
                const marker = L.marker([parseFloat(loc.lat), parseFloat(loc.lng)]).addTo(map);

                let popupContent = `<div style="font-family:'Plus Jakarta Sans',sans-serif;" class="p-2 max-w-xs">`;
                if (loc.foto) {
                    popupContent += `<img src="/storage/${loc.foto}" class="w-full h-32 object-cover rounded-xl mb-2 shadow">`;
                }
                popupContent += `<h4 class="font-bold text-sm text-slate-900">${loc.nama}</h4>`;
                if (loc.kategori_point) {
                    popupContent += `<span class="inline-block bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full my-1 border border-emerald-200">${loc.kategori_point.nama}</span>`;
                }
                if (loc.desk) {
                    popupContent += `<p class="text-xs text-slate-600 mt-1 leading-relaxed">${loc.desk}</p>`;
                }
                popupContent += `<div class="text-[10px] text-slate-400 font-mono mt-2 pt-2 border-t border-slate-100">📍 Lat: ${loc.lat}, Lng: ${loc.lng}</div>`;
                popupContent += `</div>`;

                marker.bindPopup(popupContent);
                markersMap[loc.nama] = marker;
            }
        });

        // 5. Render Batas Utama Wilayah Desa (Garis Merah Khas Google Maps)
        @if(!empty($config->path))
        try {
            const rawPath = @json($config->path);
            const villageBoundaryCoords = typeof rawPath === 'string' ? JSON.parse(rawPath) : rawPath;
            if (villageBoundaryCoords && Array.isArray(villageBoundaryCoords) && villageBoundaryCoords.length > 2) {
                const villageBoundary = L.polygon(villageBoundaryCoords, {
                    color: '#ef4444',
                    weight: 3.5,
                    dashArray: '6, 6',
                    fillColor: '#ef4444',
                    fillOpacity: 0.12
                }).addTo(map);

                villageBoundary.bindPopup(`
                    <div style="font-family:'Plus Jakarta Sans',sans-serif;" class="p-2">
                        <span class="inline-block bg-red-100 text-red-700 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full mb-1 border border-red-200">BATAS DESA RESMI</span>
                        <h4 class="font-bold text-sm text-slate-900">Batas Administrasi Desa {{ $config->nama_desa ?? 'Serdang' }}</h4>
                        <p class="text-xs text-slate-600 mt-1">Kec. {{ $config->nama_kecamatan ?? '' }}, Kab. {{ $config->nama_kabupaten ?? '' }}</p>
                    </div>
                `);

                map.fitBounds(villageBoundary.getBounds(), { padding: [30, 30] });
            }
        } catch (e) {
            console.error('Batas desa path invalid format:', e);
        }
        @endif

        // 6. Add Area Polygons Sub-Wilayah / Dusun
        const areas = @json($areas);

        areas.forEach(function (area) {
            if (area.path) {
                try {
                    const coordinates = JSON.parse(area.path);
                    const polygon = L.polygon(coordinates, {
                        color: '#d97706',
                        fillColor: '#f59e0b',
                        fillOpacity: 0.25,
                        weight: 3
                    }).addTo(map);

                    polygon.bindPopup(`
                        <div style="font-family:'Plus Jakarta Sans',sans-serif;" class="p-2">
                            <h4 class="font-bold text-sm text-slate-900">📐 ${area.nama}</h4>
                            <p class="text-xs text-slate-600 mt-1">${area.desk || 'Batas Wilayah Resmi Desa'}</p>
                        </div>
                    `);
                } catch (e) {
                    console.error('Invalid GeoJSON coordinates for area:', area);
                }
            }
        });
    });

    // 6. Interactive Focus Function (Fly to location & open popup)
    function focusOnMap(lat, lng, name) {
        // Scroll to map container smoothly
        const container = document.getElementById('map-container');
        if (container) {
            container.scrollIntoView({ behavior: 'smooth' });
        }

        // Fly to location & open popup
        setTimeout(function() {
            if (map) {
                map.flyTo([lat, lng], 17, {
                    animate: true,
                    duration: 1.2
                });
                
                if (markersMap[name]) {
                    setTimeout(function() {
                        markersMap[name].openPopup();
                    }, 1200);
                }
            }
        }, 300);
    }

    // 7. Live Location Search Filtering
    function filterLocations() {
        const query = document.getElementById('searchLocation').value.toLowerCase();
        const cards = document.querySelectorAll('.location-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const category = card.getAttribute('data-category');
            if (name.includes(query) || category.includes(query)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noResults = document.getElementById('noResults');
        if (noResults) {
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    }
</script>
@endpush
