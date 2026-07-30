@extends('layouts.portal')

@section('title', 'Berita & Kabar Desa')
@section('description', 'Informasi terkini kegiatan, pengumuman, dan kabar pembangunan Desa ' . ($config->nama_desa ?? 'Serdang'))

@section('content')

{{-- ===== HERO HEADER HALAMAN ===== --}}
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-16 relative overflow-hidden shadow-2xl" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="text-amber-400 text-xs font-extrabold uppercase tracking-widest bg-white/10 px-4 py-1.5 rounded-full border border-amber-400/30 mb-3 inline-block">KABAR TERBARU</span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-md">Berita &amp; Artikel Desa</h1>
        <p class="text-emerald-100 max-w-2xl mx-auto text-sm sm:text-base mt-2 opacity-90">Dapatkan informasi resmi mengenai kegiatan kemasyarakatan, pengumuman, dan perkembangan Desa {{ $config->nama_desa ?? 'Serdang' }}</p>

        {{-- Search Form --}}
        <form action="{{ route('berita') }}" method="GET" class="mt-8 max-w-xl mx-auto flex gap-2">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kata kunci berita..."
                    class="w-full pl-5 pr-4 py-3.5 rounded-2xl bg-white/90 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-amber-400 text-sm shadow-xl backdrop-blur-md border border-white/40">
            </div>
            <button type="submit" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-7 py-3.5 rounded-2xl text-sm transition shadow-xl border border-amber-400/30 flex-shrink-0">
                🔍 Cari
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Filter Categories --}}
    @if(isset($kategories) && $kategories->count() > 0)
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 scrollbar-hide">
            <a href="{{ route('berita') }}" class="px-5 py-2.5 rounded-xl text-xs font-extrabold whitespace-nowrap transition-all {{ !request('kategori') ? 'bg-emerald-800 text-white shadow-md' : 'glass-pill text-slate-700 hover:text-emerald-800' }}">
                🏷️ Semua Berita
            </a>
            @foreach($kategories as $kat)
                <a href="{{ route('berita', ['kategori' => $kat->slug]) }}" class="px-5 py-2.5 rounded-xl text-xs font-extrabold whitespace-nowrap transition-all {{ request('kategori') == $kat->slug ? 'bg-emerald-800 text-white shadow-md' : 'glass-pill text-slate-700 hover:text-emerald-800' }}">
                    {{ $kat->kategori }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Articles Grid --}}
    @if($artikels->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($artikels as $index => $item)
                <div class="glass-card rounded-3xl overflow-hidden card-hover flex flex-col justify-between group">
                    <div>
                        <div class="relative aspect-video overflow-hidden bg-slate-100">
                            <img src="{{ get_media_url($item->gambar, 'berita') }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @if($item->kategori)
                                <span class="absolute top-4 left-4 bg-emerald-800/90 backdrop-blur-md text-white text-xs font-extrabold px-3 py-1 rounded-full border border-emerald-700/50 shadow">
                                    {{ $item->kategori->kategori }}
                                </span>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 text-xs text-slate-400 mb-2 font-semibold">
                                <span>📅 {{ \Carbon\Carbon::parse($item->tgl_upload)->format('d M Y') }}</span>
                                <span>•</span>
                                <span>👁️ {{ number_format($item->hit) }}x dilihat</span>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-emerald-700 transition line-clamp-2 leading-snug">
                                <a href="{{ route('berita.detail', $item->slug ?? $item->id) }}">
                                    {{ $item->judul }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                                {{ strip_tags($item->isi) }}
                            </p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 pt-2">
                        <a href="{{ route('berita.detail', $item->slug ?? $item->id) }}" class="inline-flex items-center text-xs font-bold text-emerald-700 hover:text-emerald-900 gap-1">
                            Baca Selengkapnya ➔
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $artikels->links() }}
        </div>
    @else
        <div class="flex items-center justify-center py-20 glass-card rounded-3xl text-center">
            <div>
                <div class="text-5xl mb-3">📰</div>
                <h3 class="text-lg font-extrabold text-slate-800">Belum Ada Berita</h3>
                <p class="text-xs text-slate-500 mt-1">Belum ada berita atau artikel yang sesuai dengan pencarian Anda.</p>
            </div>
        </div>
    @endif
</div>
@endsection
