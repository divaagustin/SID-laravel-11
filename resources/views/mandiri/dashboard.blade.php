@extends('layouts.portal')

@section('title', 'Dasbor Layanan Mandiri Warga')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 100px;">
    {{-- Header Glass Banner --}}
    <div class="glass-card-dark rounded-3xl p-6 sm:p-10 text-white shadow-2xl mb-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
        <div class="relative z-10">
            <span class="bg-amber-500/30 text-amber-300 text-xs font-extrabold px-3.5 py-1 rounded-full border border-amber-400/40 uppercase tracking-wider">LAYANAN MANDIRI WARGA</span>
            <h1 class="text-3xl font-extrabold mt-3 text-white drop-shadow">Selamat Datang, {{ $penduduk->nama }}</h1>
            <p class="text-emerald-200 text-sm mt-1">NIK: <span class="font-mono text-white font-bold">{{ $penduduk->nik }}</span> | Alamat: {{ $penduduk->alamat_sekarang ?? 'Desa Serdang' }}</p>
        </div>
        <div class="flex items-center gap-3 relative z-10 flex-shrink-0 flex-wrap">
            <a href="{{ route('mandiri.surat.katalog') }}" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-5 py-3 rounded-2xl shadow-xl transition flex items-center gap-2 text-xs border border-amber-400/30">
                <span>➕</span> Permohonan Surat
            </a>
            <a href="{{ route('mandiri.umkm') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold px-5 py-3 rounded-2xl shadow-xl transition flex items-center gap-2 text-xs border border-emerald-400/30">
                <span>🏪</span> UMKM Saya
            </a>
            <a href="{{ route('mandiri.jasa') }}" class="bg-blue-700 hover:bg-blue-800 text-white font-extrabold px-5 py-3 rounded-2xl shadow-xl transition flex items-center gap-2 text-xs border border-blue-400/30">
                <span>💼</span> Jasa Warga
            </a>
            <form action="{{ route('mandiri.logout') }}" method="POST">
                @csrf
                <button type="submit" class="glass-pill text-white hover:bg-rose-600 font-extrabold px-4 py-3 rounded-2xl text-xs transition border border-white/20">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl mb-8 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-xl">✅</span>
                <p class="text-sm font-extrabold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- QUICK ACCESS MENU GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <a href="{{ route('mandiri.surat.katalog') }}" class="glass-card p-5 rounded-2xl border border-slate-200 hover:border-amber-400 transition card-hover flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-2xl font-extrabold flex-shrink-0">📄</div>
            <div>
                <h4 class="font-extrabold text-slate-900 text-sm">Surat Online</h4>
                <p class="text-xs text-slate-500 mt-0.5">Pengajuan Dokumen Surat</p>
            </div>
        </a>

        <a href="{{ route('mandiri.umkm') }}" class="glass-card p-5 rounded-2xl border border-slate-200 hover:border-emerald-500 transition card-hover flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-2xl font-extrabold flex-shrink-0">🏪</div>
            <div>
                <h4 class="font-extrabold text-slate-900 text-sm">UMKM Saya</h4>
                <p class="text-xs text-slate-500 mt-0.5">Kelola Produk &amp; Usaha</p>
            </div>
        </a>

        <a href="{{ route('mandiri.jasa') }}" class="glass-card p-5 rounded-2xl border border-slate-200 hover:border-blue-500 transition card-hover flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center text-2xl font-extrabold flex-shrink-0">💼</div>
            <div>
                <h4 class="font-extrabold text-slate-900 text-sm">Jasa Warga</h4>
                <p class="text-xs text-slate-500 mt-0.5">Micro-tasking &amp; Lowongan</p>
            </div>
        </a>

        <a href="{{ route('mandiri.pengaduan') }}" class="glass-card p-5 rounded-2xl border border-slate-200 hover:border-purple-500 transition card-hover flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center text-2xl font-extrabold flex-shrink-0">💬</div>
            <div>
                <h4 class="font-extrabold text-slate-900 text-sm">Pengaduan</h4>
                <p class="text-xs text-slate-500 mt-0.5">Lapor &amp; Aspirasi Warga</p>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Profile Card & Quick Actions --}}
        <div class="space-y-6">
            <div class="glass-card rounded-3xl p-6 border border-slate-200">
                <h2 class="text-lg font-extrabold text-slate-900 mb-4 pb-3 border-b border-slate-200 flex items-center gap-2">
                    <span>👤</span> Profil Kependudukan
                </h2>
                <dl class="space-y-3.5 text-sm">
                    <div>
                        <dt class="text-slate-400 text-[11px] font-extrabold uppercase tracking-wider">Nama Lengkap</dt>
                        <dd class="font-extrabold text-slate-900 text-base">{{ $penduduk->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-[11px] font-extrabold uppercase tracking-wider">NIK</dt>
                        <dd class="font-mono font-bold text-slate-900">{{ $penduduk->nik }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-[11px] font-extrabold uppercase tracking-wider">Tempat, Tgl Lahir</dt>
                        <dd class="text-slate-900 font-medium">{{ $penduduk->tempatlahir }}, {{ $penduduk->tanggallahir ? \Carbon\Carbon::parse($penduduk->tanggallahir)->format('d F Y') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-[11px] font-extrabold uppercase tracking-wider">Jenis Kelamin</dt>
                        <dd class="text-slate-900 font-medium">{{ $penduduk->sex == 1 ? 'Laki-laki' : 'Perempuan' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Right Column: Recent Requests --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-3xl p-6 border border-slate-200">
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200">
                    <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                        <span>📄</span> Riwayat Permohonan Surat
                    </h2>
                    <a href="{{ route('mandiri.surat.katalog') }}" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Katalog Surat ➔</a>
                </div>

                @if(isset($permohonans) && $permohonans->count())
                    <div class="space-y-4">
                        @foreach($permohonans as $req)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm">{{ $req->suratFormat->nama ?? 'Surat Keterangan' }}</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Tanggal: {{ \Carbon\Carbon::parse($req->created_at)->format('d M Y, H:i') }} WIB</p>
                                </div>
                                <div>
                                    @if($req->status == 1)
                                        <span class="bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full border border-emerald-200">✅ Selesai</span>
                                    @elseif($req->status == 2)
                                        <span class="bg-amber-100 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full border border-amber-200">⏳ Diproses</span>
                                    @else
                                        <span class="bg-slate-200 text-slate-700 text-xs font-extrabold px-3 py-1 rounded-full">📩 Terkirim</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-slate-400">
                        <div class="text-4xl mb-2">📥</div>
                        <p class="font-extrabold text-slate-700">Belum Ada Permohonan Surat</p>
                        <p class="text-xs text-slate-500 mt-1">Klik tombol "Permohonan Surat" di atas untuk membuat permohonan baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
