@extends('layouts.portal')

@section('title', 'Cek Penerima Bantuan Sosial')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 100px;">
    {{-- Header Profil --}}
    <div class="glass-card rounded-3xl p-6 shadow-sm border border-slate-200 mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center text-white text-xl font-extrabold shadow-md border border-amber-400/30">
                {{ substr($penduduk->nama, 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">{{ $penduduk->nama }}</h1>
                <p class="text-xs text-slate-500 font-mono mt-0.5">NIK: {{ $penduduk->nik }} · Alamat: {{ $penduduk->alamat_sekarang ?? 'Desa Serdang' }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mandiri.dashboard') }}" class="glass-pill text-slate-700 hover:text-emerald-800 text-xs font-extrabold px-4 py-2 rounded-xl inline-flex items-center gap-1.5 shadow-sm border border-slate-200">
                ← Kembali ke Dasbor
            </a>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-slate-900 flex items-center gap-2">
            <span class="w-3 h-8 bg-amber-500 rounded-full inline-block"></span>
            Program Bantuan Sosial Terdaftar
        </h2>
        <p class="text-slate-600 text-xs mt-1">Daftar bantuan sosial dari Pemerintah Pusat, Provinsi, Kabupaten, atau Desa yang terverifikasi untuk NIK Anda.</p>
    </div>

    @if(isset($bantuanList) && $bantuanList->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($bantuanList as $item)
        <div class="glass-card rounded-3xl shadow-md border border-slate-200 p-6 relative overflow-hidden card-hover">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="px-3.5 py-1 bg-amber-100 text-amber-800 text-xs font-extrabold rounded-full border border-amber-200">
                        {{ $item->program->asaldana ?? 'Pemerintah' }}
                    </span>
                    <h3 class="font-extrabold text-lg text-slate-900 mt-2">{{ $item->program->nama ?? 'Program Bantuan' }}</h3>
                </div>
                <div class="w-10 h-10 bg-emerald-100 text-emerald-800 rounded-full flex items-center justify-center font-extrabold text-lg border border-emerald-200">
                    ✓
                </div>
            </div>

            <div class="space-y-2 text-xs text-slate-600 border-t border-slate-200 pt-4">
                <div class="flex justify-between">
                    <span class="text-slate-500">Nomor Kartu:</span>
                    <span class="font-bold text-slate-900 font-mono">{{ $item->no_id_kartu ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Sasaran Program:</span>
                    <span class="font-bold text-slate-900">{{ $item->program->sasaran_label ?? 'Penduduk' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Status Kepesertaan:</span>
                    <span class="font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Terverifikasi Aktif</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="glass-card rounded-3xl p-12 text-center text-slate-400 border border-slate-200">
        <div class="text-5xl mb-3">🛡️</div>
        <h3 class="text-lg font-extrabold text-slate-700">Belum Ada Program Bantuan Sosial</h3>
        <p class="text-xs text-slate-500 mt-1">Saat ini NIK Anda belum terdaftar dalam program bantuan sosial aktif.</p>
    </div>
    @endif
</div>
@endsection
