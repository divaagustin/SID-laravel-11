@extends('layouts.portal')

@section('title', 'Pendaftaran Akun Layanan Mandiri')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-950 relative overflow-hidden" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>

    <div class="max-w-2xl w-full glass-card-dark p-8 sm:p-10 rounded-3xl shadow-2xl relative z-10 border border-white/20">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-500/20 text-amber-400 mb-4 border border-amber-400/30 shadow-lg">
                📝
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Pendaftaran Akun Layanan Mandiri</h2>
            <p class="mt-2 text-xs text-emerald-200">
                Isi NIK dan unggah bukti identitas diri untuk mengaktifkan PIN Layanan Mandiri Warga
            </p>
        </div>

        @if($errors->any())
            <div class="mt-6 bg-rose-500/20 border border-rose-400/40 text-rose-200 px-5 py-4 rounded-2xl text-xs font-semibold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('mandiri.register.post') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nik" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">NIK (Nomor Induk Kependudukan)</label>
                    <input id="nik" name="nik" type="text" maxlength="16" required value="{{ old('nik') }}"
                        placeholder="16 digit angka NIK sesuai KTP/KK"
                        class="w-full px-4 py-3.5 rounded-xl bg-white/10 text-white placeholder-emerald-300/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm font-mono tracking-wider">
                </div>

                <div class="md:col-span-2">
                    <label for="no_hp" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">Nomor HP / WhatsApp Aktif</label>
                    <input id="no_hp" name="no_hp" type="text" required value="{{ old('no_hp') }}"
                        placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-3.5 rounded-xl bg-white/10 text-white placeholder-emerald-300/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm font-mono tracking-wider">
                </div>

                <div>
                    <label for="pin" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">Buat PIN Rahasia (6 Digit)</label>
                    <input id="pin" name="pin" type="password" maxlength="20" required placeholder="••••••"
                        class="w-full px-4 py-3.5 rounded-xl bg-white/10 text-white placeholder-emerald-300/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm font-mono tracking-wider">
                </div>

                <div>
                    <label for="pin_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">Konfirmasi PIN Rahasia</label>
                    <input id="pin_confirmation" name="pin_confirmation" type="password" maxlength="20" required placeholder="••••••"
                        class="w-full px-4 py-3.5 rounded-xl bg-white/10 text-white placeholder-emerald-300/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm font-mono tracking-wider">
                </div>

                <div class="md:col-span-2 border-t border-white/10 pt-4">
                    <h3 class="text-sm font-extrabold text-white mb-3 flex items-center gap-2">
                        <span>📷</span> Dokumen Verifikasi Identitas
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="scan_ktp" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">Foto / Scan KTP (Wajib, Maks 3MB)</label>
                            <input id="scan_ktp" name="scan_ktp" type="file" accept="image/*,.pdf" required
                                class="w-full text-xs text-emerald-200 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-amber-500 file:text-white hover:file:bg-amber-600 bg-white/10 rounded-xl border border-white/20">
                        </div>

                        <div>
                            <label for="scan_kk" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">Foto / Scan Kartu Keluarga / KK (Opsional)</label>
                            <input id="scan_kk" name="scan_kk" type="file" accept="image/*,.pdf"
                                class="w-full text-xs text-emerald-200 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-white/20 file:text-white hover:file:bg-white/30 bg-white/10 rounded-xl border border-white/20">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3.5 px-4 rounded-xl text-sm font-extrabold text-white bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 transition shadow-xl hover:shadow-amber-500/25 border border-amber-400/30">
                    Kirim Permohonan Pendaftaran ➔
                </button>
            </div>

            <div class="text-center pt-4 border-t border-white/10">
                <a href="{{ route('mandiri.login') }}" class="text-xs font-bold text-amber-400 hover:underline">
                    ← Sudah Punya PIN? Kembali ke Halaman Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
