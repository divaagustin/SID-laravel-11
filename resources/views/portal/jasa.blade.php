@extends('layouts.portal')

@section('title', 'Papan Pekerjaan & Jasa Warga Desa')

@section('content')

{{-- ===== HERO HEADER HALAMAN ===== --}}
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-16 relative overflow-hidden shadow-2xl" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="text-amber-400 text-xs font-extrabold uppercase tracking-widest bg-white/10 px-4 py-1.5 rounded-full border border-amber-400/30 mb-3 inline-block">PLATFORM MICRO-TASKING DESA</span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-md">Jasa &amp; Pekerjaan Harian Warga</h1>
        <p class="text-emerald-100 max-w-2xl mx-auto text-sm sm:text-base mt-2 opacity-90">Bantu sesama warga desa yang membutuhkan pekerjaan fisik/harian atau temukan bantuan untuk kebutuhan tugas rumah Anda.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    {{-- ===== SEARCH & FILTER BAR ===== --}}
    <form action="{{ route('jasa.publik') }}" method="GET" class="glass-card p-6 rounded-3xl mb-10 border border-slate-200 shadow-xl">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Cari Judul Pekerjaan</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik bantuan pertukangan, kebersihan, kurir..." class="w-full pl-10 pr-4 py-3 bg-white/80 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm">
                    <span class="absolute left-3.5 top-3 text-slate-400">🔍</span>
                </div>
            </div>
            <div>
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Kategori Jasa</label>
                <select name="kategori" class="w-full py-3 px-3 bg-white/80 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm">
                    <option value="">Semua Kategori</option>
                    <option value="Kebersihan" {{ request('kategori') == 'Kebersihan' ? 'selected' : '' }}>Kebersihan &amp; Rumah Tangga</option>
                    <option value="Pertukangan" {{ request('kategori') == 'Pertukangan' ? 'selected' : '' }}>Pertukangan &amp; Bangunan</option>
                    <option value="Anter_Jemput" {{ request('kategori') == 'Anter_Jemput' ? 'selected' : '' }}>Anter / Jemput &amp; Kurir</option>
                    <option value="Pertanian" {{ request('kategori') == 'Pertanian' ? 'selected' : '' }}>Pertanian &amp; Perkebunan</option>
                    <option value="Akademik_Tugas" {{ request('kategori') == 'Akademik_Tugas' ? 'selected' : '' }}>Akademik &amp; Tugas</option>
                    <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-700 to-green-800 hover:from-emerald-800 hover:to-green-900 text-white font-extrabold py-3 px-4 rounded-xl text-sm transition-all shadow-md">
                    Cari Job
                </button>
                <a href="{{ route('jasa.publik') }}" class="glass-pill px-4 py-3 text-slate-600 hover:text-emerald-950 font-bold rounded-xl text-sm transition-all shadow-sm">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- ===== PAPAN LOWONGAN JASA WARGA ===== --}}
    @if(isset($jasas) && $jasas->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($jasas as $item)
                <div class="glass-card rounded-3xl p-6 flex flex-col justify-between card-hover border border-slate-200 shadow-xl relative {{ $item->status_job == 'open' ? 'bg-white/90' : 'bg-slate-50/70 opacity-80' }}">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider border shadow-sm {{ $item->status_job == 'open' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-amber-100 text-amber-800 border-amber-300' }}">
                                {{ $item->status_job == 'open' ? '🟢 OPEN (Belum Di-take)' : '⏳ IN PROGRESS (Sedang Dikerjakan)' }}
                            </span>
                            <span class="text-xs font-bold text-slate-400">
                                📍 {{ $item->lokasi_dusun_rt }}
                            </span>
                        </div>

                        <h3 class="font-extrabold text-slate-900 text-lg leading-snug">
                            {{ $item->judul_pekerjaan }}
                        </h3>

                        <div class="my-3 flex items-center gap-2">
                            <span class="bg-amber-500/10 text-amber-700 text-xs font-extrabold px-3 py-1 rounded-full border border-amber-400/30">
                                {{ $item->kategori }}
                            </span>
                        </div>

                        <p class="text-slate-600 text-xs line-clamp-3 leading-relaxed mb-4">
                            {{ $item->deskripsi_tugas }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Upah Insentif (Fee)</span>
                                <span class="text-base font-black text-emerald-700">Rp {{ number_format($item->fee_insentif, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Tenggat Waktu</span>
                                <span class="text-xs font-bold text-slate-700">
                                    {{ $item->tenggat_waktu ? \Carbon\Carbon::parse($item->tenggat_waktu)->format('d M Y, H:i') : 'Tanpa Tenggat' }}
                                </span>
                            </div>
                        </div>

                        @if($item->status_job == 'open')
                            <a href="{{ route('mandiri.jasa') }}" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold py-3 px-4 rounded-xl text-xs transition-all shadow-md">
                                💼 Ambil Pekerjaan Ini ➔
                            </a>
                        @else
                            <button disabled class="w-full inline-flex items-center justify-center gap-2 bg-slate-300 text-slate-600 font-bold py-3 px-4 rounded-xl text-xs cursor-not-allowed">
                                🔒 Pekerjaan Sedang Dikerjakan Warga
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $jasas->links() }}
        </div>
    @else
        <div class="glass-card rounded-3xl p-16 text-center max-w-md mx-auto my-12 border border-slate-200">
            <div class="text-5xl mb-4">💼</div>
            <h3 class="text-lg font-extrabold text-slate-800">Belum Ada Lowongan Pekerjaan</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Butuh bantuan pekerjaan harian? Buat postingan lowongan jasa melalui Layanan Mandiri Warga.</p>
            <a href="{{ route('mandiri.login') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold px-6 py-2.5 rounded-full text-xs mt-5 transition-all shadow">
                Buat Request Pekerjaan ➔
            </a>
        </div>
    @endif
</div>

@endsection
