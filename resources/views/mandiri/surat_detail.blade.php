@extends('layouts.portal')

@section('title', 'Detail Permohonan ' . $permohonanSurat->no_antrian)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 100px;">
    <div class="mb-8">
        <a href="{{ route('mandiri.dashboard') }}" class="glass-pill text-slate-700 hover:text-emerald-800 text-xs font-extrabold px-4 py-2 rounded-xl inline-flex items-center gap-1.5 mb-4 shadow-sm border border-slate-200">
            ← Kembali ke Dasbor Layanan Mandiri
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="font-mono text-xs font-bold text-slate-400">Resi Antrean: {{ $permohonanSurat->no_antrian }}</span>
                <h1 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $permohonanSurat->formatSurat->nama ?? 'Surat Keterangan' }}</h1>
            </div>
            <div>
                <span class="px-4 py-2 text-xs font-extrabold rounded-full border shadow-sm {{ $permohonanSurat->status_color }}">
                    {{ $permohonanSurat->status_label }}
                </span>
            </div>
        </div>
    </div>

    {{-- Real-time Timeline Status Tracking --}}
    <div class="glass-card rounded-3xl p-8 shadow-xl border border-slate-200 mb-8">
        <h2 class="text-lg font-extrabold text-slate-900 mb-6 pb-3 border-b border-slate-200 flex items-center gap-2">
            <span>📍</span> Alur Lacak Status Verifikasi
        </h2>

        <div class="relative pl-6 border-l-2 border-emerald-400 space-y-8">
            {{-- Step 1: Pengajuan Masuk --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0 w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shadow-md">1</span>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Permohonan Berhasil Dikirim</h3>
                    <p class="text-xs font-mono text-slate-500 mt-0.5">{{ $permohonanSurat->created_at->format('d F Y, H:i') }} WIB</p>
                    <p class="text-xs text-slate-600 mt-1">Permohonan telah tersimpan di sistem dan menunggu verifikasi berkas oleh Operator Desa.</p>
                </div>
            </div>

            {{-- Step 2: Pemeriksaan Operator --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0 w-6 h-6 rounded-full {{ $permohonanSurat->status >= 1 ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-300 text-slate-600' }} flex items-center justify-center text-xs font-bold">2</span>
                <div>
                    <h3 class="font-extrabold {{ $permohonanSurat->status >= 1 ? 'text-slate-900' : 'text-slate-400' }} text-sm">Pemeriksaan &amp; Pembuatan Draf (Operator)</h3>
                    @if($permohonanSurat->status >= 1)
                        <p class="text-xs text-emerald-700 font-extrabold mt-1">✓ Berkas disetujui &amp; draf surat telah diterbitkan oleh Operator Desa.</p>
                    @else
                        <p class="text-xs text-slate-400 mt-1">Menunggu pemeriksaan berkas oleh Operator Desa.</p>
                    @endif
                </div>
            </div>

            {{-- Step 3: Paraf Sekretaris Desa & TTE Kades --}}
            <div class="relative">
                <span class="absolute -left-[31px] top-0 w-6 h-6 rounded-full {{ $permohonanSurat->status == 3 ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-300 text-slate-600' }} flex items-center justify-center text-xs font-bold">3</span>
                <div>
                    <h3 class="font-extrabold {{ $permohonanSurat->status == 3 ? 'text-slate-900' : 'text-slate-400' }} text-sm">Paraf Sekdes &amp; TTE BSrE Kepala Desa</h3>
                    @if($permohonanSurat->status == 3)
                        <p class="text-xs text-emerald-700 font-extrabold mt-1">✓ Surat telah ditandatangani secara elektronik (TTE BSrE) oleh Kepala Desa.</p>
                    @else
                        <p class="text-xs text-slate-400 mt-1">Menunggu verifikasi TTE oleh Kepala Desa.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Rincian Data Permohonan --}}
    <div class="glass-card rounded-3xl p-8 border border-slate-200">
        <h2 class="text-lg font-extrabold text-slate-900 mb-4 pb-3 border-b border-slate-200 flex items-center gap-2">
            <span>📄</span> Detail Informasi Permohonan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-700">
            <div>
                <span class="text-slate-400 block mb-0.5">Pemohon:</span>
                <span class="font-extrabold text-slate-900 text-sm">{{ $permohonanSurat->penduduk->nama ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">NIK Pemohon:</span>
                <span class="font-extrabold font-mono text-slate-900 text-sm">{{ $permohonanSurat->penduduk->nik ?? '-' }}</span>
            </div>
            <div class="md:col-span-2">
                <span class="text-slate-400 block mb-0.5">Tujuan / Keperluan:</span>
                <span class="font-extrabold text-slate-900 leading-relaxed">{{ $permohonanSurat->keterangan ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
