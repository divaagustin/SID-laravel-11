@extends('layouts.portal')

@section('title', 'Beranda')
@section('description', 'Portal resmi Desa ' . ($config->nama_desa ?? 'Serdang') . ' - Transparansi, Akuntabilitas & Pelayanan Mandiri Warga Digital')

@section('content')

{{-- ===== HERO SLIDER BANNER ===== --}}
<section class="relative bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-900 overflow-hidden shadow-2xl" style="min-height: 560px; padding-top: 80px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>

    <div class="relative z-10">
        @if(isset($sliderArtikels) && $sliderArtikels->count() > 0)
            @foreach($sliderArtikels as $index => $slide)
            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" id="slide-{{ $index + 1 }}">
                <div class="relative flex items-center" style="min-height: 480px;">
                    <img src="{{ get_media_url($slide->gambar ?? null, 'berita') }}" class="absolute inset-0 w-full h-full object-cover opacity-50" alt="{{ $slide->judul }}">
                    <div class="max-w-7xl mx-auto px-6 lg:px-16 w-full z-10 py-12">
                        <div class="max-w-2xl">
                            <span class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs font-extrabold px-3.5 py-1.5 rounded-full mb-5 uppercase tracking-wider shadow-lg border border-amber-400/30">
                                📢 {{ $slide->kategori->kategori ?? 'Kabar Desa' }}
                            </span>
                            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4 drop-shadow-md text-white">
                                {{ $slide->judul }}
                            </h1>
                            <p class="text-emerald-100 text-base md:text-lg mb-8 line-clamp-2 leading-relaxed opacity-95">
                                {{ Str::limit(strip_tags($slide->isi), 140) }}
                            </p>
                            <div class="flex flex-wrap items-center gap-4">
                                <a href="{{ route('berita.detail', $slide->slug) }}"
                                   class="inline-flex items-center justify-center whitespace-nowrap bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-7 py-3.5 rounded-full text-sm transition-all shadow-xl hover:shadow-amber-500/25">
                                    Baca Selengkapnya →
                                </a>
                                <a href="{{ route('mandiri.login') }}"
                                   class="inline-flex items-center justify-center whitespace-nowrap glass-pill text-white hover:bg-white hover:text-emerald-950 font-extrabold px-7 py-3.5 rounded-full text-sm transition-colors shadow-lg">
                                    Layanan Mandiri Warga
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            {{-- Default Fallback Slide --}}
            <div class="hero-slide active" id="slide-1">
                <div class="relative flex items-center" style="min-height: 480px;">
                    <div class="max-w-7xl mx-auto px-6 lg:px-16 w-full z-10 py-12">
                        <div class="max-w-2xl">
                            <span class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs font-extrabold px-3.5 py-1.5 rounded-full mb-5 uppercase tracking-wider shadow-lg border border-amber-400/30">
                                🏡 Portal Resmi Desa {{ $config->nama_desa ?? 'Serdang' }}
                            </span>
                            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4 drop-shadow-md text-white">
                                Transparansi &amp; Pelayanan Publik Digital Desa
                            </h1>
                            <p class="text-emerald-100 text-base md:text-lg mb-8 leading-relaxed">
                                Mewujudkan Desa {{ $config->nama_desa ?? 'Serdang' }} yang maju, mandiri, akuntabel, dan sejahtera melalui kemudahan layanan digital.
                            </p>
                            <div class="flex flex-wrap items-center gap-4">
                                <a href="{{ route('mandiri.login') }}"
                                   class="inline-flex items-center justify-center whitespace-nowrap bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-7 py-3.5 rounded-full text-sm transition-all shadow-xl hover:shadow-amber-500/25">
                                    Masuk Akun Layanan Mandiri ➔
                                </a>
                                <a href="{{ route('tentang') }}"
                                   class="inline-flex items-center justify-center whitespace-nowrap glass-pill text-white hover:bg-white hover:text-emerald-950 font-extrabold px-7 py-3.5 rounded-full text-sm transition-colors shadow-lg">
                                    Profil &amp; Pamong Desa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Slide Khusus: Ekonomi Warga & UMKM --}}
        <div class="hero-slide" id="slide-ekonomi">
            <div class="relative flex items-center" style="min-height: 480px;">
                <img src="{{ asset('images/bg/panorama3.jpeg') }}" class="absolute inset-0 w-full h-full object-cover opacity-50" alt="Ekonomi Desa Serdang">
                <div class="max-w-7xl mx-auto px-6 lg:px-16 w-full z-10 py-12 relative">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-xs font-extrabold px-3.5 py-1.5 rounded-full mb-5 uppercase tracking-wider shadow-lg border border-emerald-400/40">
                            🤝 Pemberdayaan Ekonomi &amp; Jasa Warga
                        </span>
                        <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-4 drop-shadow-md text-white">
                            Dukung Usaha UMKM &amp; Pekerjaan Harian Warga Desa
                        </h1>
                        <p class="text-emerald-100 text-base md:text-lg mb-8 leading-relaxed opacity-95">
                            Jelajahi produk unggulan olahan kuliner, usaha desa, serta kerajinan UMKM warga lokal, atau temukan peluang bantuan pekerjaan harian &amp; jasa antar sesama warga Desa {{ $config->nama_desa ?? 'Serdang' }}.
                        </p>
                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ route('umkm.publik') }}"
                               class="inline-flex items-center justify-center whitespace-nowrap bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-7 py-3.5 rounded-full text-sm transition-all shadow-xl hover:shadow-amber-500/25 border border-amber-400/40 transform hover:scale-105">
                                🏪 Katalog UMKM Warga ➔
                            </a>
                            <a href="{{ route('jasa.publik') }}"
                               class="inline-flex items-center justify-center whitespace-nowrap bg-emerald-700/90 hover:bg-emerald-600 text-white font-extrabold px-7 py-3.5 rounded-full text-sm transition-all shadow-xl border border-emerald-400/40 transform hover:scale-105">
                                💼 Papan Jasa &amp; Pekerjaan Warga ➔
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slider Controls --}}
    <button onclick="prevSlide()" aria-label="Slide Sebelumnya" class="slider-btn absolute left-4 sm:left-8 top-1/2 transform -translate-y-1/2 z-20 w-11 h-11 glass-pill text-white hover:bg-amber-500 rounded-full flex items-center justify-center shadow-lg text-2xl font-bold transition">
        ‹
    </button>
    <button onclick="nextSlide()" aria-label="Slide Berikutnya" class="slider-btn absolute right-4 sm:right-8 top-1/2 transform -translate-y-1/2 z-20 w-11 h-11 glass-pill text-white hover:bg-amber-500 rounded-full flex items-center justify-center shadow-lg text-2xl font-bold transition">
        ›
    </button>

    {{-- Dot Indicators --}}
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-20 flex items-center gap-2" id="hero-dots-container">
    </div>
</section>

{{-- ===== RUNNING TEXT / TICKER PENGUMUMAN ===== --}}
<div class="bg-gradient-to-r from-amber-500 via-amber-600 to-amber-500 text-white py-3 px-4 shadow-md relative z-20">
    <div class="max-w-7xl mx-auto flex items-center gap-3">
        <span class="bg-emerald-950 text-white text-xs font-extrabold px-3 py-1.5 rounded-lg flex-shrink-0 uppercase tracking-wider whitespace-nowrap shadow-sm border border-emerald-800/50">
            📢 PENGUMUMAN
        </span>
        <div class="ticker-container flex-1 min-w-0 overflow-hidden">
            <div class="ticker-track">
                <span class="ticker-item text-sm font-semibold text-white">
                    Selamat Datang di Portal Resmi Desa {{ $config->nama_desa ?? 'Serdang' }}, Kecamatan {{ $config->nama_kecamatan ?? 'Meranti' }}
                </span>
                <span class="ticker-item text-sm font-semibold text-white">
                    ⚡ Manfaatkan Layanan Mandiri Warga untuk pengurusan surat online 24 jam gratis!
                </span>
                <span class="ticker-item text-sm font-semibold text-white">
                    📊 Data APBDes &amp; Transparansi Keuangan Desa dapat diakses secara terbuka di portal ini.
                </span>
                {{-- Seamless duplicate --}}
                <span class="ticker-item text-sm font-semibold text-white">
                    Selamat Datang di Portal Resmi Desa {{ $config->nama_desa ?? 'Serdang' }}, Kecamatan {{ $config->nama_kecamatan ?? 'Meranti' }}
                </span>
                <span class="ticker-item text-sm font-semibold text-white">
                    ⚡ Manfaatkan Layanan Mandiri Warga untuk pengurusan surat online 24 jam gratis!
                </span>
                <span class="ticker-item text-sm font-semibold text-white">
                    📊 Data APBDes &amp; Transparansi Keuangan Desa dapat diakses secara terbuka di portal ini.
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ===== QUICK ACCESS LAYANAN MANDIRI WARGA (4 KARTU FITUR) ===== --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-14 relative z-30">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Feature 1 --}}
        <a href="{{ route('mandiri.login') }}" class="glass-card p-6 rounded-3xl card-hover flex items-start gap-4 group border border-slate-200 shadow-xl">
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500/20 to-emerald-700/20 text-emerald-800 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 group-hover:scale-110 transition-transform shadow-inner border border-emerald-400/30">
                📄
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-base group-hover:text-emerald-700 transition-colors">Permohonan Surat</h3>
                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Cetak Keterangan Usaha, Domisili, SKU online 24 jam.</p>
            </div>
        </a>

        {{-- Feature 2 --}}
        <a href="{{ route('mandiri.login') }}" class="glass-card p-6 rounded-3xl card-hover flex items-start gap-4 group border border-slate-200 shadow-xl">
            <div class="w-14 h-14 bg-gradient-to-br from-amber-500/20 to-amber-700/20 text-amber-800 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 group-hover:scale-110 transition-transform shadow-inner border border-amber-400/30">
                🛡️
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-base group-hover:text-emerald-700 transition-colors">Cek Bantuan Sosial</h3>
                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Verifikasi kepesertaan DTKS, PKH, &amp; BLT Desa.</p>
            </div>
        </a>

        {{-- Feature 3 --}}
        <a href="{{ route('mandiri.login') }}" class="glass-card p-6 rounded-3xl card-hover flex items-start gap-4 group border border-slate-200 shadow-xl">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500/20 to-blue-700/20 text-blue-800 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 group-hover:scale-110 transition-transform shadow-inner border border-blue-400/30">
                💬
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-base group-hover:text-emerald-700 transition-colors">Lapor Pengaduan</h3>
                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Kirim aspirasi &amp; pengaduan ke Kepala Desa.</p>
            </div>
        </a>

        {{-- Feature 4 --}}
        <a href="{{ route('dokumen') }}" class="glass-card p-6 rounded-3xl card-hover flex items-start gap-4 group border border-slate-200 shadow-xl">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500/20 to-purple-700/20 text-purple-800 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 group-hover:scale-110 transition-transform shadow-inner border border-purple-400/30">
                📂
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-base group-hover:text-emerald-700 transition-colors">Dokumen Publik</h3>
                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Unduh Perdes &amp; Transparansi APBDes PDF.</p>
            </div>
        </a>

    </div>
</section>

{{-- ===== STATISTIK REAL-TIME DEMOGRAFI DESA (4 KARTU) ===== --}}
<section class="py-14 mb-16 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs font-extrabold text-amber-600 uppercase tracking-widest bg-amber-50 px-3 py-1 rounded-full border border-amber-200">DATA DEMOGRAFI TERINTEGRASI</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mt-2">Statistik Kependudukan Desa</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="glass-card p-6 rounded-3xl card-hover border-l-4 border-l-emerald-600">
                <div class="text-4xl font-extrabold text-emerald-800">{{ number_format($stats['penduduk']) }}</div>
                <div class="text-slate-600 text-xs font-extrabold uppercase tracking-wider mt-2">Penduduk Jiwa</div>
            </div>
            <div class="glass-card p-6 rounded-3xl card-hover border-l-4 border-l-amber-500">
                <div class="text-4xl font-extrabold text-amber-600">{{ number_format($stats['keluarga']) }}</div>
                <div class="text-slate-600 text-xs font-extrabold uppercase tracking-wider mt-2">Kepala Keluarga</div>
            </div>
            <div class="glass-card p-6 rounded-3xl card-hover border-l-4 border-l-blue-600">
                <div class="text-4xl font-extrabold text-blue-600">{{ number_format($stats['laki_laki']) }}</div>
                <div class="text-slate-600 text-xs font-extrabold uppercase tracking-wider mt-2">Laki-Laki</div>
            </div>
            <div class="glass-card p-6 rounded-3xl card-hover border-l-4 border-l-pink-500">
                <div class="text-4xl font-extrabold text-pink-600">{{ number_format($stats['perempuan']) }}</div>
                <div class="text-slate-600 text-xs font-extrabold uppercase tracking-wider mt-2">Perempuan</div>
            </div>
        </div>
    </div>
</section>

{{-- ===== TRANSPARANSI KEUANGAN APBDES ===== --}}
@if(isset($apbdes) && $apbdes)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    <div class="glass-card-dark rounded-3xl p-8 md:p-10 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
            <div class="max-w-lg">
                <span class="bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider shadow border border-amber-400/30">TRANSPARANSI KEUANGAN</span>
                <h2 class="text-2xl md:text-3xl font-extrabold mt-3 mb-2 text-white">Laporan APBDes Tahun {{ $apbdes->tahun }}</h2>
                <p class="text-emerald-100 text-sm leading-relaxed">Informasi realisasi anggaran pendapatan dan belanja desa secara akuntabel dan terbuka untuk seluruh warga.</p>
            </div>
            
            <div class="flex-1 glass-pill p-6 rounded-2xl border border-white/20 space-y-4">
                {{-- Pagu Anggaran --}}
                <div>
                    <div class="flex justify-between text-sm font-semibold mb-1 text-white">
                        <span>Pagu Anggaran</span>
                        <span class="text-amber-300 font-bold">Rp {{ number_format($apbdes->anggaran, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-white/20 h-3 rounded-full overflow-hidden">
                        <div class="bg-amber-400 h-full rounded-full w-full"></div>
                    </div>
                </div>

                {{-- Realisasi Penyerapan --}}
                @php
                    $persenRealisasi = $apbdes->anggaran > 0 ? min(100, round(($apbdes->realisasi / $apbdes->anggaran) * 100)) : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm font-semibold mb-1 text-white">
                        <span>Realisasi Penyerapan</span>
                        <span class="text-emerald-300 font-bold">Rp {{ number_format($apbdes->realisasi, 0, ',', '.') }} ({{ $persenRealisasi }}%)</span>
                    </div>
                    <div class="w-full bg-white/20 h-3 rounded-full overflow-hidden">
                        <div class="bg-emerald-400 h-full rounded-full transition-all duration-500" style="width: {{ $persenRealisasi }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ===== SHOWCASE APARATUR DESA DINAMIS ===== --}}
<section class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 py-16 text-white mb-16 relative overflow-hidden">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-xl mx-auto mb-12">
            <span class="text-xs font-extrabold text-amber-400 uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full border border-amber-400/30">PEMERINTAHAN DESA</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-white mt-3">Aparatur &amp; Perangkat Desa</h2>
            <p class="text-emerald-200 text-sm mt-2">Pimpinan &amp; Perangkat Desa {{ $config->nama_desa ?? 'Serdang' }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($pamong as $pm)
                <div class="glass-pill p-6 rounded-3xl border border-white/20 text-center card-hover flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full bg-emerald-700/50 border-4 border-amber-400 overflow-hidden mb-4 flex items-center justify-center text-3xl font-bold flex-shrink-0 shadow-xl">
                        @if($pm->foto)
                            <img src="{{ get_media_url($pm->foto, 'pamong') }}" class="w-full h-full object-cover" alt="{{ $pm->pamong_nama }}">
                        @else
                            👤
                        @endif
                    </div>
                    <span class="bg-amber-500/30 text-amber-300 text-xs font-extrabold px-3 py-1 rounded-full border border-amber-400/40 uppercase tracking-wider mb-2">
                        {{ $pm->jabatan->nama ?? 'Perangkat Desa' }}
                    </span>
                    <h3 class="font-extrabold text-white text-lg leading-snug">
                        {{ trim(($pm->gelar_depan ?? '') . ' ' . $pm->pamong_nama . ' ' . ($pm->gelar_belakang ?? '')) }}
                    </h3>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-emerald-200 italic">
                    Belum ada aparatur desa diset untuk ditampilkan di Beranda.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== KABAR & BERITA TERBARU DESA (GAMBAR DINAMIS) ===== --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <span class="text-xs font-extrabold text-amber-600 uppercase tracking-widest bg-amber-50 px-3 py-1 rounded-full border border-amber-200">INFORMASI TERKINI</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mt-2">Berita &amp; Pengumuman Desa</h2>
        </div>
        <a href="{{ route('berita') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900 transition-colors flex items-center gap-1">
            Lihat Semua Berita ➔
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($artikel as $index => $item)
        <div class="glass-card rounded-3xl overflow-hidden card-hover flex flex-col justify-between">
            <div>
                <div class="h-48 bg-slate-100 overflow-hidden relative">
                    <img src="{{ get_media_url($item->gambar ?? null, 'berita') }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" alt="{{ $item->judul }}">
                    <span class="absolute top-3 left-3 bg-emerald-800 text-white text-xs font-extrabold px-3 py-1 rounded-full shadow-md border border-emerald-700/50">
                        {{ $item->kategori->kategori ?? 'Kabar Desa' }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="text-xs text-slate-400 mb-2 font-semibold flex items-center gap-2">
                        <span>📅 {{ \Carbon\Carbon::parse($item->tgl_upload)->format('d M Y') }}</span>
                        <span>•</span>
                        <span>👁️ {{ number_format($item->hit) }} dilihat</span>
                    </div>
                    <h3 class="font-extrabold text-slate-900 text-lg mb-2 leading-snug line-clamp-2 hover:text-emerald-700 transition-colors">
                        <a href="{{ route('berita.detail', $item->slug) }}">{{ $item->judul }}</a>
                    </h3>
                    <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed">{{ Str::limit(strip_tags($item->isi), 110) }}</p>
                </div>
            </div>
            <div class="px-6 pb-6 pt-0">
                <a href="{{ route('berita.detail', $item->slug) }}" class="text-xs font-bold text-emerald-700 hover:underline inline-flex items-center gap-1">
                    Baca Selengkapnya ➔
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 glass-card p-8 text-center rounded-3xl text-slate-400">
            <div class="text-4xl mb-2">📰</div>
            <p class="font-bold text-slate-700">Belum Ada Berita Diterbitkan</p>
            <p class="text-xs text-slate-400 mt-1">Dapatkan update informasi berita dari admin desa.</p>
        </div>
        @endforelse
    </div>
</section>

{{-- ===== CALL TO ACTION LAYANAN MANDIRI ===== --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    <div class="glass-card-dark rounded-3xl p-8 md:p-12 text-white shadow-2xl batik-pattern relative overflow-hidden">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 z-10 relative">
            <div class="max-w-xl">
                <h2 class="text-2xl md:text-3xl font-extrabold mb-3 text-white">Butuh Pengurusan Surat Resmi Desa?</h2>
                <p class="text-emerald-100 text-base leading-relaxed">Gunakan Layanan Mandiri Warga untuk mengajukan surat keterangan secara online tanpa perlu mengantre di kantor desa.</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('mandiri.login') }}" class="inline-flex items-center justify-center whitespace-nowrap bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-8 py-4 rounded-full text-sm transition-all transform hover:scale-105 shadow-xl hover:shadow-amber-500/25 border border-amber-400/30">
                    Masuk Akun Layanan Mandiri ➔
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const totalSlides = slides.length;
    const dotsContainer = document.getElementById('hero-dots-container');
    let dots = [];
    let timer = null;

    if (dotsContainer && totalSlides > 1) {
        dotsContainer.innerHTML = '';
        slides.forEach((_, idx) => {
            const btn = document.createElement('button');
            btn.className = `slider-dot w-3 h-3 rounded-full transition-all border border-white/40 ${idx === 0 ? 'bg-amber-400 w-8' : 'bg-white/40'}`;
            btn.setAttribute('aria-label', `Slide ${idx + 1}`);
            btn.onclick = () => goToSlide(idx);
            dotsContainer.appendChild(btn);
        });
        dots = dotsContainer.querySelectorAll('.slider-dot');
    }

    function showSlide(n) {
        if (totalSlides === 0) return;
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => {
            d.classList.remove('bg-amber-400', 'w-8');
            d.classList.add('bg-white/40');
        });

        currentSlide = (n + totalSlides) % totalSlides;
        if (slides[currentSlide]) {
            slides[currentSlide].classList.add('active');
        }
        if (dots[currentSlide]) {
            dots[currentSlide].classList.remove('bg-white/40');
            dots[currentSlide].classList.add('bg-amber-400', 'w-8');
        }
    }

    window.nextSlide = function() {
        showSlide(currentSlide + 1);
        resetTimer();
    };

    window.prevSlide = function() {
        showSlide(currentSlide - 1);
        resetTimer();
    };

    window.goToSlide = function(n) {
        showSlide(n);
        resetTimer();
    };

    function resetTimer() {
        if (timer) clearInterval(timer);
        if (totalSlides > 1) {
            timer = setInterval(function() { showSlide(currentSlide + 1); }, 6000);
        }
    }

    resetTimer();
});
</script>
@endpush
