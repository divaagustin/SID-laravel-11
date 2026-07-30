@extends('layouts.portal')

@section('title', 'Tentang Desa')
@section('description', 'Profil lengkap Desa ' . ($config->nama_desa ?? '') . ' - Sejarah, Visi, Misi, Struktur Organisasi & Peta Wilayah')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<style>
    #map-preview {
        width: 100%;
        height: 400px;
        border-radius: 1.5rem;
        z-index: 10;
    }

    /* Sejarah Desa Styling — Glassmorphism Dark Theme */
    .sejarah-content {
        color: rgba(209, 250, 229, 0.95);
        font-size: 0.975rem;
        line-height: 1.75;
    }
    .sejarah-content p {
        text-align: left;
        line-height: 1.75;
        margin-bottom: 1.125rem;
        color: rgba(236, 253, 245, 0.92);
    }
    .sejarah-content h2, .sejarah-content h3 {
        color: #fbbf24;
        font-weight: 800;
        font-size: 1.35rem;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        letter-spacing: -0.01em;
    }
    .sejarah-content strong {
        color: #ffffff;
        font-weight: 800;
    }

    /* Misi Desa Dynamic Numbered Cards Styling */
    .misi-content ol, .misi-content ul {
        counter-reset: misi-counter;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .misi-content li {
        counter-increment: misi-counter;
        position: relative;
        padding: 1.125rem 1.25rem 1.125rem 3.5rem !important;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 1.25rem;
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.7;
        text-align: justify;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    .misi-content li:hover {
        border-color: rgba(245, 158, 11, 0.7);
        transform: translateY(-3px);
        box-shadow: 0 12px 20px -5px rgba(245, 158, 11, 0.15);
        background: #ffffff;
    }
    .misi-content li::before {
        content: counter(misi-counter);
        position: absolute;
        left: 1rem;
        top: 1.125rem;
        width: 1.85rem;
        height: 1.85rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #ffffff;
        font-weight: 900;
        font-size: 0.85rem;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(217, 119, 6, 0.3);
    }
</style>
@endpush

@section('content')

{{-- ===== HERO HEADER HALAMAN ===== --}}
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-16 relative overflow-hidden shadow-2xl" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-8">
            {{-- Logo Desa --}}
            <div class="flex-shrink-0">
                <img src="{{ get_media_url($config->logo ?? null, 'logo') }}" alt="Logo {{ $config->nama_desa ?? 'Desa' }}"
                     class="w-28 h-28 object-contain rounded-3xl bg-white/10 p-3 border border-white/20 shadow-2xl backdrop-blur-md">
            </div>
            {{-- Info Desa --}}
            <div>
                <span class="text-amber-400 text-xs font-extrabold uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full border border-amber-400/30 mb-2 inline-block">PORTAL RESMI PEMERINTAH DESA</span>
                <h1 class="text-3xl md:text-5xl font-extrabold mb-2 text-white drop-shadow-md">
                    Desa {{ $config->nama_desa ?? 'Serdang' }}
                </h1>
                <p class="text-emerald-100 text-lg font-medium mb-4">
                    Kecamatan {{ $config->nama_kecamatan ?? '-' }}, Kabupaten {{ $config->nama_kabupaten ?? '-' }}, Provinsi {{ $config->nama_propinsi ?? '-' }}
                </p>
                <div class="flex flex-wrap gap-3">
                    @if(isset($config->kode_desa) && $config->kode_desa)
                    <span class="glass-pill text-white text-xs font-bold px-3.5 py-1.5 rounded-full border border-white/20">
                        📋 Kode Desa: {{ $config->kode_desa }}
                    </span>
                    @endif
                    @if(isset($config->kode_pos) && $config->kode_pos)
                    <span class="glass-pill text-white text-xs font-bold px-3.5 py-1.5 rounded-full border border-white/20">
                        📮 Kode Pos: {{ $config->kode_pos }}
                    </span>
                    @endif
                    @if(isset($config->telepon) && $config->telepon)
                    <span class="glass-pill text-white text-xs font-bold px-3.5 py-1.5 rounded-full border border-white/20">
                        📞 {{ $config->telepon }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== NAVIGASI ANCHOR GLASS ===== --}}
<div class="glass-card border-b border-slate-200/60 sticky top-20 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto gap-2 py-3 scrollbar-hide">
            <a href="#sejarah" class="glass-pill px-5 py-2 text-xs font-extrabold text-slate-700 hover:text-emerald-800 rounded-xl transition-all">📜 Sejarah Desa</a>
            <a href="#visi-misi" class="glass-pill px-5 py-2 text-xs font-extrabold text-slate-700 hover:text-emerald-800 rounded-xl transition-all">🎯 Visi &amp; Misi</a>
            <a href="#struktur" class="glass-pill px-5 py-2 text-xs font-extrabold text-slate-700 hover:text-emerald-800 rounded-xl transition-all">🏛️ Struktur Organisasi</a>
            <a href="#peta" class="glass-pill px-5 py-2 text-xs font-extrabold text-slate-700 hover:text-emerald-800 rounded-xl transition-all">🗺️ Peta Wilayah</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">

    {{-- ===== SEKSI 1: SEJARAH DESA (DARK GLASS THEME MATCHED) ===== --}}
    <section id="sejarah" class="scroll-mt-28">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-10 bg-amber-500 rounded-full"></div>
            <div>
                <span class="text-xs font-extrabold text-amber-600 uppercase tracking-widest block">Asal Usul &amp; Perjalanan</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Sejarah Desa</h2>
            </div>
        </div>

        <div class="glass-card-dark text-white rounded-3xl overflow-hidden p-8 sm:p-12 border border-emerald-700/50 shadow-2xl relative">
            <div class="absolute top-6 right-8 text-9xl text-white/5 font-serif pointer-events-none">📜</div>
            @if(isset($config->sejarah_desa) && !empty(trim(strip_tags($config->sejarah_desa))))
                <div class="sejarah-content relative z-10">
                    {!! $config->sejarah_desa !!}
                </div>
            @else
                <div class="sejarah-content relative z-10 space-y-6">
                    <p>
                        <strong class="text-amber-400 text-3xl font-black float-left mr-3.5 leading-none">D</strong>esa {{ $config->nama_desa ?? 'Serdang' }} merupakan salah satu desa yang terletak secara strategis di wilayah Kecamatan {{ $config->nama_kecamatan ?? 'Meranti' }}, Kabupaten {{ $config->nama_kabupaten ?? 'Asahan' }}, Provinsi {{ $config->nama_propinsi ?? 'Sumatera Utara' }}. Berdiri di atas nilai-nilai luhur kebudayaan nusantara, kearifan lokal, serta tradisi gotong royong yang senantiasa terjaga dari generasi ke generasi.
                    </p>
                    <p>
                        Dalam perkembangannya, Pemerintah Desa berkomitmen penuh mendorong transformasi pelayanan publik modern, keterbukaan informasi publik (transparansi APBDes), pengembangan ekonomi kreatif UMKM warga, serta pemerataan sarana infrastruktur secara berkelanjutan demi terwujudnya masyarakat desa yang mandiri, cerdas, dan sejahtera.
                    </p>
                </div>
            @endif
        </div>
    </section>

    {{-- ===== SEKSI 2: VISI & MISI ===== --}}
    <section id="visi-misi" class="scroll-mt-28">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-10 bg-amber-500 rounded-full"></div>
            <div>
                <span class="text-xs font-extrabold text-amber-600 uppercase tracking-widest block">Cita-Cita &amp; Arah Pembangunan</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Visi &amp; Misi Desa</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            {{-- VISI --}}
            <div class="glass-card-dark text-white rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden flex flex-col justify-between border border-emerald-700/50">
                <div class="absolute top-0 right-0 w-44 h-44 bg-white/5 rounded-full -translate-y-12 translate-x-12"></div>
                <div>
                    <div class="w-14 h-14 bg-amber-500/20 border border-amber-400/40 rounded-2xl flex items-center justify-center text-3xl mb-5 shadow-inner">
                        🔭
                    </div>
                    <h3 class="text-2xl font-black mb-5 text-amber-400 uppercase tracking-wider">VISI DESA</h3>
                    @if(isset($config->visi) && !empty(trim(strip_tags($config->visi))))
                        <div class="text-emerald-100 text-lg sm:text-xl leading-relaxed font-semibold italic bg-white/5 p-6 rounded-2xl border border-white/10 backdrop-blur-sm text-justify">
                            "{!! strip_tags($config->visi) !!}"
                        </div>
                    @else
                        <div class="text-emerald-100 text-base sm:text-lg leading-relaxed font-semibold italic bg-white/5 p-6 sm:p-8 rounded-2xl border border-white/10 backdrop-blur-sm text-justify shadow-sm">
                            "Mewujudkan Desa {{ $config->nama_desa ?? 'Serdang' }} yang Maju, Mandiri, Sejahtera, Transparan, dan Berkelanjutan Berbasis Pelayanan Digital &amp; Gotong Royong."
                        </div>
                    @endif
                </div>
            </div>

            {{-- MISI (100% DYNAMIC WITH AUTOMATIC BADGED CARDS) --}}
            <div class="glass-card rounded-3xl p-8 sm:p-10 border-t-4 border-t-amber-500 shadow-2xl flex flex-col justify-between bg-white/90">
                <div>
                    <div class="w-14 h-14 bg-amber-500/10 border border-amber-400/30 rounded-2xl flex items-center justify-center text-3xl mb-5">
                        🎯
                    </div>
                    <h3 class="text-xl font-black mb-6 text-slate-900 uppercase tracking-wider">MISI DESA</h3>

                    <div class="misi-content">
                        @if(isset($config->misi) && !empty(trim(strip_tags($config->misi))))
                            {!! $config->misi !!}
                        @else
                            <ol>
                                <li>Meningkatkan kualitas tata kelola pemerintahan desa yang bersih, transparan, dan akuntabel berbasis teknologi informasi digital.</li>
                                <li>Mengembangkan perekonomian warga melalui pemberdayaan UMKM, BUMDes, dan digitalisasi usaha harian masyarakat desa.</li>
                                <li>Meningkatkan kualitas sarana dan prasarana infrastruktur publik desa yang merata, aman, dan berwawasan lingkungan.</li>
                            </ol>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SEKSI 3: STRUKTUR ORGANISASI ===== --}}
    <section id="struktur" class="scroll-mt-28">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-10 bg-amber-500 rounded-full"></div>
            <div>
                <span class="text-xs font-extrabold text-amber-600 uppercase tracking-widest block">Pemerintahan &amp; Kemasyarakatan</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Struktur Organisasi Desa</h2>
            </div>
        </div>

        <div class="glass-card rounded-3xl p-8 border border-slate-200 shadow-xl">
            @if(isset($pamong) && $pamong->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($pamong as $staf)
                    <div class="glass-pill p-5 rounded-2xl text-center card-hover flex flex-col items-center">
                        <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-2 border-amber-400 shadow-md bg-slate-100">
                            <img src="{{ get_media_url($staf->foto, 'pamong') }}" class="w-full h-full object-cover" alt="{{ $staf->pamong_nama }}">
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm mb-1 leading-tight">{{ $staf->pamong_nama }}</h3>
                        <span class="bg-amber-500/20 text-amber-800 text-[11px] font-extrabold px-3 py-1 rounded-full border border-amber-400/30 mt-1">
                            {{ $staf->jabatan->nama ?? 'Aparatur Desa' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-slate-400">
                    <div class="text-5xl mb-3">🏛️</div>
                    <p class="font-extrabold text-slate-700">Daftar Aparatur Desa Belum Diisi</p>
                    <p class="text-xs text-slate-500 mt-1">Daftar pamong akan muncul otomatis dari database.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ===== SEKSI 4: PETA LOKASI DESA ===== --}}
    <section id="peta" class="scroll-mt-28">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1.5 h-10 bg-amber-500 rounded-full"></div>
            <div>
                <span class="text-xs font-extrabold text-amber-600 uppercase tracking-widest block">Geografis &amp; Wilayah</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Peta Lokasi &amp; Batas Wilayah</h2>
            </div>
        </div>

        <div class="glass-card rounded-3xl p-6 overflow-hidden shadow-xl border border-slate-200">
            <div id="map-preview" class="h-96 rounded-2xl overflow-hidden shadow-inner border border-slate-300"></div>
            <div class="mt-4 flex justify-between items-center text-xs text-slate-500 px-2">
                <span>📍 Koordinat Desa: {{ $config->lat ?? '-3.023456' }}, {{ $config->lng ?? '99.612345' }}</span>
                <a href="{{ route('peta') }}" class="font-extrabold text-emerald-700 hover:underline flex items-center gap-1">
                    Buka Peta GIS Lengkap ➔
                </a>
            </div>
        </div>
    </section>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('map-preview');
    if (!mapElement || typeof L === 'undefined') return;

    const lat = {{ floatval($config->lat ?? -3.023456) }};
    const lng = {{ floatval($config->lng ?? 99.612345) }};
    
    const map = L.map('map-preview', {
        scrollWheelZoom: false
    }).setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup('<b>Desa {{ $config->nama_desa ?? "Serdang" }}</b><br>Kec. {{ $config->nama_kecamatan ?? "Meranti" }}')
        .openPopup();
});
</script>
@endpush
