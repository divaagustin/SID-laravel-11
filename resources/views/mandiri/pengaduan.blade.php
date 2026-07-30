@extends('layouts.portal')

@section('title', 'Lapor Desa & Kotak Aspirasi Warga')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 100px;">
    <div class="mb-8">
        <a href="{{ route('mandiri.dashboard') }}" class="glass-pill text-slate-700 hover:text-emerald-800 text-xs font-extrabold px-4 py-2 rounded-xl inline-flex items-center gap-1.5 mb-4 shadow-sm border border-slate-200">
            ← Kembali ke Dasbor Layanan Mandiri
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kotak Pengaduan &amp; Aspirasi Warga</h1>
        <p class="text-sm text-slate-600 mt-1">Sampaikan keluhan, aspirasi, atau pengaduan Anda secara langsung kepada Pemerintah Desa</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-sm">
            <span class="text-xl">✅</span>
            <p class="text-sm font-extrabold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Form Pengaduan --}}
        <div>
            <div class="glass-card rounded-3xl p-6 shadow-xl border border-slate-200 sticky top-24">
                <h2 class="text-lg font-extrabold text-slate-900 mb-4 pb-3 border-b border-slate-200 flex items-center gap-2">
                    <span>📣</span> Buat Pengaduan Baru
                </h2>

                <form action="{{ route('mandiri.pengaduan.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="subjek" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">Subjek / Judul Pengaduan</label>
                        <input type="text" id="subjek" name="subjek" required
                            value="{{ old('subjek') }}"
                            placeholder="Misal: Jalan Rusak di RT 01 / Permohonan Lampu Jalan"
                            class="w-full px-4 py-3 rounded-xl bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                        @error('subjek') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="komentar" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">Detail Pesan / Pengaduan</label>
                        <textarea id="komentar" name="komentar" rows="5" required
                            placeholder="Tuliskan secara lengkap lokasi, kondisi, atau usulan Anda..."
                            class="w-full px-4 py-3 rounded-xl bg-white text-slate-900 placeholder-slate-400 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs leading-relaxed">{{ old('komentar') }}</textarea>
                        @error('komentar') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold text-xs rounded-xl shadow-lg border border-amber-400/30 transition">
                        Kirim Pengaduan ➔
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Riwayat Pengaduan --}}
        <div class="lg:col-span-2">
            <div class="glass-card rounded-3xl p-6 border border-slate-200">
                <h2 class="text-lg font-extrabold text-slate-900 mb-6 pb-3 border-b border-slate-200 flex items-center gap-2">
                    <span>💬</span> Riwayat Aspirasi &amp; Pengaduan Anda
                </h2>

                @if(isset($pesanMandiris) && $pesanMandiris->count())
                    <div class="space-y-4">
                        @foreach($pesanMandiris as $pesan)
                            <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-extrabold text-slate-900 text-sm">{{ $pesan->subjek }}</h4>
                                    <span class="text-[10px] font-mono text-slate-400 font-bold">{{ \Carbon\Carbon::parse($pesan->created_at)->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <p class="text-xs text-slate-700 leading-relaxed">{{ $pesan->komentar }}</p>
                                @if($pesan->tanggapan)
                                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-1">
                                        <p class="font-extrabold text-emerald-950">Tanggapan Kepala Desa / Operator:</p>
                                        <p class="leading-relaxed">{{ $pesan->tanggapan }}</p>
                                    </div>
                                @else
                                    <span class="inline-block bg-amber-100 text-amber-800 text-[10px] font-extrabold px-3 py-1 rounded-full border border-amber-200">⏳ Menunggu Tanggapan Admin</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-slate-400">
                        <div class="text-4xl mb-2">💬</div>
                        <p class="font-extrabold text-slate-700">Belum Ada Pengaduan Dibuat</p>
                        <p class="text-xs text-slate-500 mt-1">Gunakan formulir di sebelah kiri untuk menyampaikan pengaduan Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
