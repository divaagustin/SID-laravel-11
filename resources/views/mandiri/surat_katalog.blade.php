@extends('layouts.portal')

@section('title', 'Pilih Layanan Surat Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 100px;">
    <div class="mb-8">
        <a href="{{ route('mandiri.dashboard') }}" class="glass-pill text-slate-700 hover:text-emerald-800 text-xs font-extrabold px-4 py-2 rounded-xl inline-flex items-center gap-1.5 mb-4 shadow-sm border border-slate-200">
            ← Kembali ke Dasbor Layanan Mandiri
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Katalog Pengajuan Surat Online</h1>
        <p class="text-sm text-slate-600 mt-1">Pilih jenis surat keterangan desa yang ingin Anda ajukan secara online 24 jam</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($suratFormats as $format)
            <div class="glass-card rounded-3xl p-6 border border-slate-200 card-hover flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-extrabold text-2xl mb-4 shadow-inner border border-emerald-200">
                        📄
                    </div>
                    <span class="text-[11px] font-mono font-bold text-slate-400">Kode: {{ $format->kode_surat ?? 'DESA' }}</span>
                    <h3 class="text-lg font-extrabold text-slate-900 mt-1 leading-snug">{{ $format->nama }}</h3>
                    <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                        {{ $format->syarat_surat ?? 'Format resmi pelayanan Administrasi Desa ' . $format->nama }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200">
                    <a href="{{ route('mandiri.surat.form', $format) }}"
                       class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold text-xs py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition shadow-md border border-amber-400/30">
                        <span>Buat Permohonan</span> ➔
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 glass-card rounded-3xl border border-slate-200">
                <div class="text-4xl mb-3">📄</div>
                <h3 class="text-lg font-extrabold text-slate-800">Belum Ada Format Surat Mandiri</h3>
                <p class="text-xs text-slate-500 mt-1">Format surat online belum diaktifkan oleh operator desa.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
