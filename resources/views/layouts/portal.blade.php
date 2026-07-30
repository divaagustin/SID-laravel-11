<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Website Resmi Desa ' . ($config->nama_desa ?? 'Serdang'))</title>
    <meta name="description" content="Website Resmi Pemerintah Desa {{ $config->nama_desa ?? 'Serdang' }}, Kecamatan {{ $config->nama_kecamatan ?? 'Meranti' }}, Kabupaten {{ $config->nama_kabupaten ?? 'Asahan' }}. Portal Informasi, Transparansi, &amp; Layanan Mandiri Warga.">

    {{-- Local Compiled Tailwind CSS & Web-Safe Font System --}}
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @stack('head')
    @stack('styles')

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        /* Hero Slider Carousel */
        .hero-slide {
            display: none;
            opacity: 0;
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hero-slide.active {
            display: block;
            opacity: 1;
        }

        /* Glassmorphism Classes */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .glass-card-dark {
            background: rgba(11, 58, 26, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .glass-pill {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Hover animations */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* News Ticker Carousel */
        .ticker-container {
            overflow: hidden;
            white-space: nowrap;
            width: 100%;
        }
        .ticker-track {
            display: inline-block;
            white-space: nowrap;
            padding-left: 100%;
            animation: ticker 60s linear infinite;
        }
        .ticker-track:hover {
            animation-play-state: paused;
        }
        .ticker-item {
            display: inline-block;
            padding-right: 50px;
        }
        @keyframes ticker {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }

        /* Fixed Sticky Translucent Dark Navbar */
        #site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background: rgba(11, 58, 26, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.3s ease;
        }

        /* Nav links with active state indicator */
        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.5rem 0.9rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .nav-link:hover {
            color: #fbbf24;
            background: rgba(255, 255, 255, 0.1);
        }
        .nav-link.active {
            color: #fbbf24;
            background: rgba(217, 119, 6, 0.25);
            border: 1px solid rgba(245, 158, 11, 0.4);
        }

        /* Dropdown Styles */
        .nav-dropdown {
            position: relative;
        }
        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 0.4rem;
            width: 14.5rem;
            background: rgba(11, 58, 26, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 1.25rem;
            padding: 0.5rem;
            box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.6), 0 10px 10px -5px rgba(0, 0, 0, 0.4);
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }
        .nav-dropdown:hover .nav-dropdown-menu,
        .nav-dropdown:focus-within .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.825rem;
            font-weight: 700;
            border-radius: 0.75rem;
            transition: all 0.15s ease;
        }
        .dropdown-item:hover {
            color: #fbbf24;
            background: rgba(255, 255, 255, 0.12);
        }
        .dropdown-item.active {
            color: #fbbf24;
            background: rgba(217, 119, 6, 0.3);
        }

        .batik-pattern {
            background-image: radial-gradient(rgba(217, 119, 6, 0.15) 1px, transparent 0);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-full flex flex-col selection:bg-amber-500 selection:text-white">

    {{-- TOPBAR INFORMASI DESA --}}
    <div class="bg-emerald-950 text-emerald-200 text-xs py-2 px-4 hidden md:block border-b border-emerald-900/80 z-50 relative">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-1.5 font-medium">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Kec. {{ $config->nama_kecamatan ?? 'Meranti' }}, Kab. {{ $config->nama_kabupaten ?? 'Asahan' }}
                </span>
                <span class="flex items-center gap-1.5 font-medium">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h32a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/></svg>
                    {{ $config->email_desa ?? ('kantordesa' . \Illuminate\Support\Str::slug($config->nama_desa ?? 'desa') . '@gmail.com') }}
                </span>
            </div>
            <div class="flex items-center gap-4 text-[11px] font-semibold">
                <span class="text-emerald-400 bg-emerald-900/80 px-2.5 py-0.5 rounded-full border border-emerald-700/60">Jam Kantor: 08:00 - 16:00 WIB</span>
            </div>
        </div>
    </div>

    {{-- HEADER / NAVBAR --}}
    <header id="site-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="{{ route('beranda') }}" class="flex items-center gap-3.5 group">
                    <img src="{{ get_media_url($config->logo ?? null, 'logo') }}" alt="Logo {{ $config->nama_desa ?? 'Desa' }}" class="h-12 w-auto max-w-[50px] object-contain drop-shadow group-hover:scale-105 transition-transform flex-shrink-0">
                    <div class="leading-tight">
                        <div class="text-white font-extrabold text-lg tracking-wide drop-shadow-sm">Desa {{ $config->nama_desa ?? 'Serdang' }}</div>
                        <div class="text-amber-400 text-xs font-bold">Kec. {{ $config->nama_kecamatan ?? 'Meranti' }}</div>
                    </div>
                </a>

                {{-- Nav Desktop (Sleek Grouped Layout) --}}
                <nav class="hidden md:flex items-center gap-2">
                    <a href="{{ route('beranda') }}" class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}">
                        <span>🏠 Beranda</span>
                    </a>

                    {{-- Dropdown 1: Profil Desa --}}
                    <div class="nav-dropdown">
                        <button class="nav-link {{ request()->routeIs('tentang', 'peta', 'galeri') ? 'active' : '' }}">
                            <span>🏛️ Profil Desa</span>
                            <span class="text-[10px] opacity-70">▼</span>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('tentang') }}" class="dropdown-item {{ request()->routeIs('tentang') ? 'active' : '' }}">
                                <span>🏛️ Tentang &amp; Aparatur</span>
                            </a>
                            <a href="{{ route('peta') }}" class="dropdown-item {{ request()->routeIs('peta') ? 'active' : '' }}">
                                <span>🗺️ Peta Wilayah GIS</span>
                            </a>
                            <a href="{{ route('galeri') }}" class="dropdown-item {{ request()->routeIs('galeri') ? 'active' : '' }}">
                                <span>📸 Galeri Foto</span>
                            </a>
                        </div>
                    </div>

                    {{-- Dropdown 2: Informasi & Kabar --}}
                    <div class="nav-dropdown">
                        <button class="nav-link {{ request()->routeIs('berita*', 'dokumen*') ? 'active' : '' }}">
                            <span>📰 Informasi &amp; Publikasi</span>
                            <span class="text-[10px] opacity-70">▼</span>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('berita') }}" class="dropdown-item {{ request()->routeIs('berita*') ? 'active' : '' }}">
                                <span>📰 Berita &amp; Artikel</span>
                            </a>
                            <a href="{{ route('dokumen') }}" class="dropdown-item {{ request()->routeIs('dokumen*') ? 'active' : '' }}">
                                <span>📂 Dokumen &amp; Transparansi</span>
                            </a>
                        </div>
                    </div>

                    {{-- Dropdown 3: Ekonomi Warga --}}
                    <div class="nav-dropdown">
                        <button class="nav-link {{ request()->routeIs('umkm*', 'jasa*') ? 'active' : '' }}">
                            <span>🤝 Ekonomi Warga</span>
                            <span class="text-[10px] opacity-70">▼</span>
                        </button>
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('umkm.publik') }}" class="dropdown-item {{ request()->routeIs('umkm*') ? 'active' : '' }}">
                                <span>🏪 Katalog UMKM Warga</span>
                            </a>
                            <a href="{{ route('jasa.publik') }}" class="dropdown-item {{ request()->routeIs('jasa*') ? 'active' : '' }}">
                                <span>💼 Papan Jasa Warga</span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('mandiri.login') }}"
                       class="inline-flex items-center justify-center whitespace-nowrap bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-5 py-2.5 rounded-full text-xs transition-all shadow-xl hover:shadow-amber-500/30 ml-2 border border-amber-400/40 transform hover:scale-105">
                        ⚡ Layanan Mandiri ➔
                    </a>
                </nav>

                {{-- Mobile hamburger --}}
                <button id="menu-btn" class="md:hidden text-white p-2 rounded-xl hover:bg-white/10 transition" aria-label="Toggle Navigation">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile Nav --}}
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-emerald-800/60 mt-2 pt-3 bg-emerald-950/98 backdrop-blur-xl rounded-b-2xl px-4 shadow-2xl space-y-1">
                <a href="{{ route('beranda') }}" class="block text-white font-bold py-2.5 px-3 rounded-xl hover:bg-white/10 text-sm">🏠 Beranda</a>
                
                <div class="py-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-400 px-3">Profil Desa</span>
                    <a href="{{ route('tentang') }}" class="block text-white font-semibold py-2 px-3 rounded-xl hover:bg-white/10 text-xs mt-1">🏛️ Tentang &amp; Aparatur</a>
                    <a href="{{ route('peta') }}" class="block text-white font-semibold py-2 px-3 rounded-xl hover:bg-white/10 text-xs">🗺️ Peta GIS</a>
                    <a href="{{ route('galeri') }}" class="block text-white font-semibold py-2 px-3 rounded-xl hover:bg-white/10 text-xs">📸 Galeri Foto</a>
                </div>

                <div class="py-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-400 px-3">Informasi</span>
                    <a href="{{ route('berita') }}" class="block text-white font-semibold py-2 px-3 rounded-xl hover:bg-white/10 text-xs mt-1">📰 Berita &amp; Artikel</a>
                    <a href="{{ route('dokumen') }}" class="block text-white font-semibold py-2 px-3 rounded-xl hover:bg-white/10 text-xs">📂 Dokumen Publik</a>
                </div>

                <div class="py-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-400 px-3">Ekonomi Warga</span>
                    <a href="{{ route('umkm.publik') }}" class="block text-white font-semibold py-2 px-3 rounded-xl hover:bg-white/10 text-xs mt-1">🏪 UMKM Warga</a>
                    <a href="{{ route('jasa.publik') }}" class="block text-white font-semibold py-2 px-3 rounded-xl hover:bg-white/10 text-xs">💼 Jasa Warga</a>
                </div>

                <a href="{{ route('mandiri.login') }}" class="block bg-gradient-to-r from-amber-500 to-amber-600 text-white font-extrabold text-center py-3 rounded-xl text-sm mt-3 shadow-lg border border-amber-400/40">⚡ Layanan Mandiri Warga ➔</a>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950 text-white mt-auto border-t border-emerald-800/60 relative overflow-hidden">
        <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-5 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <img src="{{ get_media_url($config->logo ?? null, 'logo') }}" alt="Logo {{ $config->nama_desa ?? 'Desa' }}" class="h-11 w-auto max-w-[45px] object-contain drop-shadow flex-shrink-0">
                        <div>
                            <div class="font-extrabold text-lg text-white">Desa {{ $config->nama_desa ?? 'Serdang' }}</div>
                            <div class="text-amber-400 text-xs font-semibold">Kec. {{ $config->nama_kecamatan ?? 'Meranti' }}, Kab. {{ $config->nama_kabupaten ?? 'Asahan' }}</div>
                        </div>
                    </div>
                    <p class="text-emerald-200 text-xs leading-relaxed opacity-80">
                        Portal Sistem Informasi Resmi Pemerintah Desa {{ $config->nama_desa ?? 'Serdang' }}. Menyalurkan informasi publik, pelayanan mandiri warga, dan transparansi pembangunan desa.
                    </p>
                </div>

                <div>
                    <h4 class="font-extrabold text-white text-sm mb-3 border-b border-emerald-800 pb-1.5">Navigasi Utama</h4>
                    <ul class="space-y-1.5 text-xs text-emerald-200">
                        <li><a href="{{ route('beranda') }}" class="hover:text-amber-400 transition">Beranda Utama</a></li>
                        <li><a href="{{ route('tentang') }}" class="hover:text-amber-400 transition">Profil &amp; Aparatur Desa</a></li>
                        <li><a href="{{ route('berita') }}" class="hover:text-amber-400 transition">Berita &amp; Pengumuman</a></li>
                        <li><a href="{{ route('umkm.publik') }}" class="hover:text-amber-400 transition">Katalog UMKM Warga</a></li>
                        <li><a href="{{ route('jasa.publik') }}" class="hover:text-amber-400 transition">Papan Jasa Warga</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-extrabold text-white text-sm mb-3 border-b border-emerald-800 pb-1.5">Layanan Publik</h4>
                    <ul class="space-y-1.5 text-xs text-emerald-200">
                        <li><a href="{{ route('mandiri.login') }}" class="hover:text-amber-400 transition">Permohonan Surat Online</a></li>
                        <li><a href="{{ route('peta') }}" class="hover:text-amber-400 transition">Peta Geografis Desa</a></li>
                        <li><a href="{{ route('dokumen') }}" class="hover:text-amber-400 transition">Dokumen APBDes &amp; LPPD</a></li>
                        <li><a href="{{ route('galeri') }}" class="hover:text-amber-400 transition">Galeri Foto Kegiatan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-extrabold text-white text-sm mb-3 border-b border-emerald-800 pb-1.5">Kontak Desa</h4>
                    <ul class="space-y-2 text-xs text-emerald-200">
                        <li class="flex items-start gap-2">
                            <span>📍</span>
                            <span>{{ $config->alamat_kantor ?? ('Kantor Desa ' . ($config->nama_desa ?? '') . ', Kec. ' . ($config->nama_kecamatan ?? '') . ', Kab. ' . ($config->nama_kabupaten ?? '')) }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>📞</span>
                            <span>{{ $config->telepon ?? '-' }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>✉️</span>
                            <span>{{ $config->email_desa ?? ('kantordesa' . \Illuminate\Support\Str::slug($config->nama_desa ?? 'desa') . '@gmail.com') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-emerald-900/80 flex flex-col sm:flex-row items-center justify-between text-xs text-emerald-400 font-medium">
                <div>&copy; {{ date('Y') }} Pemerintah Desa {{ $config->nama_desa ?? 'Serdang' }}. All rights reserved.</div>
                <div class="mt-1 sm:mt-0 font-mono text-[11px] opacity-75">OpenSID v2 Premium Engine</div>
            </div>
        </div>
    </footer>

    {{-- Mobile Menu Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('menu-btn');
            const menu = document.getElementById('mobile-menu');
            if (btn && menu) {
                btn.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
