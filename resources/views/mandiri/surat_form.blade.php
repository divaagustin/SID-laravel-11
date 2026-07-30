@extends('layouts.portal')

@section('title', 'Form Pengajuan ' . $suratFormat->nama)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 100px;">
    <div class="mb-8">
        <a href="{{ route('mandiri.surat.katalog') }}" class="glass-pill text-slate-700 hover:text-emerald-800 text-xs font-extrabold px-4 py-2 rounded-xl inline-flex items-center gap-1.5 mb-4 shadow-sm border border-slate-200">
            ← Kembali ke Katalog Surat
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Form Pengajuan: {{ $suratFormat->nama }}</h1>
        <p class="text-sm text-slate-600 mt-1">Isi formulir permohonan berikut dan unggah berkas persyaratan yang diperlukan</p>
    </div>

    <div class="glass-card rounded-3xl p-8 shadow-xl border border-slate-200">
        <form action="{{ route('mandiri.surat.store', $suratFormat) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Informational Header --}}
            <div class="glass-pill rounded-2xl p-5 border border-emerald-300 text-xs text-slate-800 space-y-1 bg-emerald-50/70">
                <p class="font-extrabold text-emerald-900 text-sm mb-1">👤 Informasi Pemohon:</p>
                <p>Nama Lengkap: <strong class="text-slate-900">{{ $penduduk->nama }}</strong> | NIK: <strong class="text-slate-900 font-mono">{{ $penduduk->nik }}</strong></p>
                <p>Alamat Terdaftar: <strong class="text-slate-900">{{ $penduduk->alamat_sekarang ?? 'Desa Serdang' }}</strong></p>
            </div>

            {{-- Form Fields --}}
            <div class="space-y-5">
                <div>
                    <label for="no_hp_aktif" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">Nomor WhatsApp Aktif (Untuk Notifikasi Status)</label>
                    <input type="text" id="no_hp_aktif" name="no_hp_aktif" required
                        value="{{ old('no_hp_aktif', $penduduk->telepon ?? '') }}"
                        placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-3.5 rounded-xl bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm font-mono">
                </div>

                <div>
                    <label for="keterangan" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">Tujuan / Keperluan Pembuatan Surat</label>
                    <textarea id="keterangan" name="keterangan" rows="3" required
                        placeholder="Jelaskan secara singkat keperluan pembuatan surat ini (misal: Persyaratan melamar pekerjaan / pengurusan BPJS / dll)"
                        class="w-full px-4 py-3.5 rounded-xl bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm leading-relaxed">{{ old('keterangan') }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <h3 class="text-sm font-extrabold text-slate-900 mb-3 flex items-center gap-2">
                        <span>📎</span> Lampiran Berkas Persyaratan (PDF / Foto JPG/PNG, Max 5MB)
                    </h3>

                    <div class="space-y-3">
                        <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50">
                            <label class="block text-xs font-extrabold text-slate-800 mb-1.5">1. Foto / Scan KTP / KK Pemohon (Wajib)</label>
                            <input type="file" name="syarat_file[ktp_kk]" accept="image/*,.pdf"
                                class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-amber-500 file:text-white hover:file:bg-amber-600">
                        </div>
                        <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50">
                            <label class="block text-xs font-extrabold text-slate-800 mb-1.5">2. Surat Pengantar RT / RW (Jika Ada)</label>
                            <input type="file" name="syarat_file[pengantar_rt_rw]" accept="image/*,.pdf"
                                class="w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('mandiri.surat.katalog') }}" class="glass-pill px-6 py-3 rounded-xl text-xs font-extrabold text-slate-700 hover:bg-slate-200 border border-slate-300">
                    Batal
                </a>
                <button type="submit"
                    class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-8 py-3.5 rounded-xl text-xs shadow-xl border border-amber-400/30">
                    Kirim Permohonan Surat ➔
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
