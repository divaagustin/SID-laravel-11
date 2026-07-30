@extends('layouts.portal')

@section('title', $artikel->judul)

@section('content')

{{-- ===== HERO HEADER HALAMAN ===== --}}
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-14 relative overflow-hidden shadow-2xl" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="{{ route('berita') }}" class="glass-pill text-white text-xs font-bold px-4 py-2 rounded-xl inline-flex items-center gap-1.5 mb-5 hover:bg-white/20 transition">
            ← Kembali ke Daftar Berita
        </a>
        @if($artikel->kategori)
            <span class="bg-amber-500 text-white text-xs font-extrabold px-3.5 py-1 rounded-full uppercase tracking-wider block w-max mb-3 border border-amber-400/40">
                {{ $artikel->kategori->kategori }}
            </span>
        @endif
        <h1 class="text-2xl sm:text-4xl font-extrabold text-white leading-tight tracking-tight drop-shadow-md">
            {{ $artikel->judul }}
        </h1>
        <div class="flex flex-wrap items-center gap-4 text-xs text-emerald-200 mt-5 pt-4 border-t border-white/10 font-semibold">
            <span>📅 {{ \Carbon\Carbon::parse($artikel->tgl_upload)->format('d F Y, H:i') }} WIB</span>
            <span>•</span>
            <span>👤 Penulis: {{ $artikel->author->name ?? 'Admin Desa' }}</span>
            <span>•</span>
            <span>👁️ {{ number_format($artikel->hit) }} kali dilihat</span>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Featured Image --}}
        <img src="{{ get_media_url($artikel->gambar, 'berita') }}" alt="{{ $artikel->judul }}" class="w-full h-auto max-h-[500px] object-cover">

    {{-- Article Content Body --}}
    <div class="glass-card rounded-3xl p-8 sm:p-12 mb-12">
        <div class="prose prose-emerald max-w-none text-slate-800 leading-relaxed text-sm sm:text-base space-y-4 [&>p]:mb-4 [&>h2]:text-emerald-800 [&>h2]:font-extrabold [&>ul]:list-disc [&>ul]:pl-6">
            {!! $artikel->isi !!}
        </div>
    </div>

    {{-- Related Articles --}}
    @if(isset($beritaTerkait) && $beritaTerkait->count() > 0)
        <div class="pt-8 border-t border-slate-200">
            <h3 class="text-xl font-extrabold text-slate-900 mb-6 flex items-center gap-2">
                <span>📰</span> Berita Terkait Lainnya
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($beritaTerkait as $terkait)
                    <a href="{{ route('berita.detail', $terkait->slug ?? $terkait->id) }}" class="glass-card rounded-2xl p-5 card-hover block group">
                        <span class="text-[10px] font-bold text-slate-400 font-mono">📅 {{ \Carbon\Carbon::parse($terkait->tgl_upload)->format('d M Y') }}</span>
                        <h4 class="font-extrabold text-slate-900 text-sm mt-1.5 line-clamp-2 leading-snug group-hover:text-emerald-700 transition">
                            {{ $terkait->judul }}
                        </h4>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
