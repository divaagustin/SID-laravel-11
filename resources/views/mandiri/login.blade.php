@extends('layouts.portal')

@section('title', 'Login Layanan Mandiri Warga')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="padding-top: 100px;">
    {{-- Background Panorama Image --}}
    <img src="{{ asset('images/bg/panorama1.jpeg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Panorama Desa Serdang">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-950/40 via-emerald-950/30 to-slate-950/45 backdrop-blur-[2px]"></div>
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>

    <div class="max-w-md w-full glass-card-dark p-8 sm:p-10 rounded-3xl shadow-2xl relative z-10 border border-white/20">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-500/20 text-amber-400 mb-4 border border-amber-400/30 shadow-lg">
                🔐
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Layanan Mandiri Warga</h2>
            <p class="mt-2 text-xs text-emerald-200">
                Masuk menggunakan Nomor Induk Kependudukan (NIK) &amp; PIN Rahasia Anda
            </p>
        </div>

        @if(session('success'))
            <div class="mt-6 bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 px-4 py-3 rounded-2xl text-xs font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 bg-rose-500/20 border border-rose-400/40 text-rose-200 px-4 py-3 rounded-2xl text-xs font-semibold">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('mandiri.login.post') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="nik" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">Nomor Induk Kependudukan (NIK)</label>
                    <div class="relative">
                        <input id="nik" name="nik" type="text" maxlength="16" required
                            value="{{ old('nik') }}"
                            placeholder="16 Digit NIK KTP/KK"
                            class="w-full px-4 py-3.5 rounded-xl bg-white/10 text-white placeholder-emerald-300/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm font-mono tracking-wider">
                    </div>
                    @error('nik')
                        <p class="mt-1 text-xs text-rose-300 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pin" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">PIN Rahasia (6 Digit)</label>
                    <div class="relative">
                        <input id="pin" name="pin" type="password" maxlength="20" required
                            placeholder="••••••"
                            class="w-full px-4 py-3.5 rounded-xl bg-white/10 text-white placeholder-emerald-300/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm font-mono tracking-wider">
                    </div>
                    @error('pin')
                        <p class="mt-1 text-xs text-rose-300 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3.5 px-4 rounded-xl text-sm font-extrabold text-white bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 transition shadow-xl hover:shadow-amber-500/25 border border-amber-400/30">
                    Masuk ke Akun Saya ➔
                </button>
            </div>

            <div class="text-center pt-4 border-t border-white/10 space-y-2">
                <p class="text-xs text-emerald-200/80">
                    Belum memiliki PIN Layanan Mandiri?
                </p>
                <a href="{{ route('mandiri.register') }}"
                   class="inline-flex items-center justify-center w-full py-2.5 px-4 rounded-xl text-xs font-extrabold text-amber-300 bg-white/10 hover:bg-white/20 border border-amber-400/40 transition shadow-sm">
                    ✨ Aktivasi PIN / Buat Akun Baru ➔
                </a>
                <p class="text-[11px] text-emerald-300/70 pt-1">
                    Atau hubungi Kantor Desa {{ $config->nama_desa ?? '' }} jika lupa PIN Anda.
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
