@extends('layouts.portal')

@section('title', 'Katalog UMKM & Usaha Warga Desa')

@section('content')

{{-- ===== HERO HEADER HALAMAN ===== --}}
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-16 relative overflow-hidden shadow-2xl" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="text-amber-400 text-xs font-extrabold uppercase tracking-widest bg-white/10 px-4 py-1.5 rounded-full border border-amber-400/30 mb-3 inline-block">EKONOMI KREATIF WARGA</span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-md">Katalog UMKM &amp; Usaha Desa</h1>
        <p class="text-emerald-100 max-w-2xl mx-auto text-sm sm:text-base mt-2 opacity-90">Dukung perekonomian warga lokal Desa {{ $config->nama_desa ?? 'Serdang' }} dengan membeli produk dan menggunakan jasa warga desa secara langsung.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    {{-- ===== SEARCH & FILTER BAR ===== --}}
    <form action="{{ route('umkm.publik') }}" method="GET" class="glass-card p-6 rounded-3xl mb-10 border border-slate-200 shadow-xl">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Cari Usaha / Produk</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama usaha, warung, produk..." class="w-full pl-10 pr-4 py-3 bg-white/80 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm">
                    <span class="absolute left-3.5 top-3 text-slate-400">🔍</span>
                </div>
            </div>
            <div>
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Kategori Usaha</label>
                <select name="kategori" class="w-full py-3 px-3 bg-white/80 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm">
                    <option value="">Semua Kategori</option>
                    <option value="Kuliner" {{ request('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner / Olahan Makanan</option>
                    <option value="Sembako" {{ request('kategori') == 'Sembako' ? 'selected' : '' }}>Sembako &amp; Kelontong</option>
                    <option value="Elektronik/Konter" {{ request('kategori') == 'Elektronik/Konter' ? 'selected' : '' }}>Elektronik &amp; Konter Pulsa</option>
                    <option value="Pertanian" {{ request('kategori') == 'Pertanian' ? 'selected' : '' }}>Pertanian &amp; Hasil Bumi</option>
                    <option value="Pabrik/Manufaktur" {{ request('kategori') == 'Pabrik/Manufaktur' ? 'selected' : '' }}>Pabrik / Manufaktur</option>
                    <option value="Jasa_Tetap" {{ request('kategori') == 'Jasa_Tetap' ? 'selected' : '' }}>Jasa Tetap (Bengkel, Jahit, Salon)</option>
                    <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-700 to-green-800 hover:from-emerald-800 hover:to-green-900 text-white font-extrabold py-3 px-4 rounded-xl text-sm transition-all shadow-md">
                    Cari UMKM
                </button>
                <a href="{{ route('umkm.publik') }}" class="glass-pill px-4 py-3 text-slate-600 hover:text-emerald-950 font-bold rounded-xl text-sm transition-all shadow-sm">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- ===== GRID KATALOG UMKM ===== --}}
    @if(isset($umkms) && $umkms->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($umkms as $item)
                <div class="glass-card rounded-3xl overflow-hidden card-hover flex flex-col justify-between group border border-slate-200 shadow-xl">
                    <div>
                        <div class="relative aspect-video overflow-hidden bg-slate-100">
                            <img src="{{ get_media_url($item->foto_usaha, 'galeri') }}" alt="{{ $item->nama_usaha }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <span class="absolute top-4 left-4 text-[10px] font-extrabold px-3 py-1 rounded-full shadow backdrop-blur-md border border-white/20 uppercase tracking-wider {{ $item->status_operasional == 'buka' ? 'bg-emerald-600/90 text-white' : 'bg-red-600/90 text-white' }}">
                                {{ $item->status_operasional == 'buka' ? '🟢 BUKA' : '🔴 TUTUP' }}
                            </span>
                            <span class="absolute top-4 right-4 bg-emerald-950/80 text-amber-300 text-[10px] font-extrabold px-3 py-1 rounded-full border border-amber-400/30 shadow backdrop-blur-md">
                                {{ $item->kategori_usaha }}
                            </span>
                        </div>
                        <div class="p-6">
                            <h3 class="font-extrabold text-slate-900 text-lg leading-snug group-hover:text-emerald-700 transition">
                                {{ $item->nama_usaha }}
                            </h3>
                            <p class="text-slate-600 text-xs mt-2 line-clamp-3 leading-relaxed">
                                {{ $item->deskripsi_produk ?? 'Penjualan produk dan layanan jasa berkualitas milik warga desa.' }}
                            </p>
                            
                            <div class="mt-4 pt-4 border-t border-slate-100 space-y-1.5 text-xs text-slate-500 font-medium">
                                <div class="flex items-center gap-2">
                                    <span>🕒</span>
                                    <span>Jam: {{ $item->jam_operasional }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>📍</span>
                                    <span class="truncate">{{ $item->alamat_usaha ?? 'Desa Serdang' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <a href="{{ $item->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-extrabold py-3 px-4 rounded-xl text-xs transition-all shadow-md">
                            <span>💬 Hubungi via WhatsApp</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $umkms->links() }}
        </div>
    @else
        <div class="glass-card rounded-3xl p-16 text-center max-w-md mx-auto my-12 border border-slate-200">
            <div class="text-5xl mb-4">🏪</div>
            <h3 class="text-lg font-extrabold text-slate-800">Belum Ada UMKM Terdaftar</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Daftarkan usaha atau produk milik Anda melalui menu Layanan Mandiri Warga.</p>
            <a href="{{ route('mandiri.login') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold px-6 py-2.5 rounded-full text-xs mt-5 transition-all shadow">
                Daftarkan Usaha Saya ➔
            </a>
        </div>
    @endif
</div>

@endsection
