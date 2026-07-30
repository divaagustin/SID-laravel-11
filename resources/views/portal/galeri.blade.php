@extends('layouts.portal')

@section('title', 'Galeri Dokumentasi Foto Kegiatan Desa')

@section('content')

{{-- ===== HERO HEADER HALAMAN ===== --}}
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-16 relative overflow-hidden shadow-2xl" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="text-amber-400 text-xs font-extrabold uppercase tracking-widest bg-white/10 px-4 py-1.5 rounded-full border border-amber-400/30 mb-3 inline-block">DOKUMENTASI DESA</span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-md">Galeri Foto &amp; Kegiatan</h1>
        <p class="text-emerald-100 max-w-2xl mx-auto text-sm sm:text-base mt-2 opacity-90">Kumpulan dokumentasi foto kegiatan kemasyarakatan, pembangunan, dan kebudayaan Desa {{ $config->nama_desa ?? 'Serdang' }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(isset($galeris) && $galeris->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($galeris as $item)
                <div class="glass-card rounded-3xl overflow-hidden card-hover group cursor-pointer" onclick="openLightbox('{{ $item->gambar_url }}', '{{ addslashes($item->nama) }}')">
                    <div class="aspect-square overflow-hidden bg-slate-100 relative">
                        <img src="{{ $item->gambar_url }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-3xl font-bold">
                            🔍
                        </div>
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] text-slate-400 font-mono font-bold">📅 {{ \Carbon\Carbon::parse($item->tgl_upload)->format('d M Y') }}</span>
                        <h3 class="font-extrabold text-slate-900 text-sm mt-1 line-clamp-2 leading-snug group-hover:text-emerald-700 transition">{{ $item->nama }}</h3>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $galeris->links() }}
        </div>
    @else
        <div class="flex items-center justify-center py-20 glass-card rounded-3xl text-center">
            <div>
                <div class="text-5xl mb-3">🖼️</div>
                <h3 class="text-lg font-extrabold text-slate-800">Belum Ada Foto Galeri</h3>
                <p class="text-xs text-slate-500 mt-1">Foto dokumentasi kegiatan desa akan ditampilkan di sini.</p>
            </div>
        </div>
    @endif
</div>

{{-- Lightbox Modal --}}
<div id="lightbox" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-xl hidden flex items-center justify-center p-4" onclick="closeLightbox()">
    <div class="max-w-4xl w-full text-center relative" onclick="event.stopPropagation()">
        <button onclick="closeLightbox()" class="absolute -top-12 right-0 text-white text-3xl font-bold hover:text-amber-400 transition">&times;</button>
        <img id="lightbox-img" src="" alt="Preview" class="max-h-[80vh] w-auto mx-auto rounded-2xl shadow-2xl border border-white/20">
        <h4 id="lightbox-title" class="text-white text-lg font-extrabold mt-4 drop-shadow"></h4>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openLightbox(src, title) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-title').innerText = title;
    document.getElementById('lightbox').classList.remove('hidden');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
}
</script>
@endpush
