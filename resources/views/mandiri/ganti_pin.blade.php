@extends('layouts.portal')

@section('title', 'Ubah PIN Layanan Mandiri')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-950 relative overflow-hidden" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>

    <div class="max-w-md w-full glass-card-dark p-8 sm:p-10 rounded-3xl shadow-2xl relative z-10 border border-white/20">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-500/20 text-amber-400 mb-4 border border-amber-400/30 shadow-lg">
                🔑
            </div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Ubah PIN Layanan Mandiri</h2>
            <p class="mt-2 text-xs text-emerald-200">
                Buat PIN 6 digit baru untuk mengamankan akses akun Layanan Mandiri Warga Anda
            </p>
        </div>

        @if(session('warning'))
            <div class="mt-6 bg-amber-500/20 border border-amber-400/40 text-amber-200 px-4 py-3 rounded-2xl text-xs font-extrabold">
                ⚠️ {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-6 bg-rose-500/20 border border-rose-400/40 text-rose-200 px-4 py-3 rounded-2xl text-xs font-semibold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-6 space-y-5" action="{{ route('mandiri.ganti-pin.post') }}" method="POST">
            @csrf

            <div>
                <label for="pin" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">PIN Rahasia Baru (6 Digit)</label>
                <input id="pin" name="pin" type="password" maxlength="20" required placeholder="••••••"
                    class="w-full px-4 py-3.5 rounded-xl bg-white/10 text-white placeholder-emerald-300/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm font-mono tracking-wider">
            </div>

            <div>
                <label for="pin_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-emerald-200 mb-1.5">Konfirmasi PIN Rahasia Baru</label>
                <input id="pin_confirmation" name="pin_confirmation" type="password" maxlength="20" required placeholder="••••••"
                    class="w-full px-4 py-3.5 rounded-xl bg-white/10 text-white placeholder-emerald-300/50 border border-white/20 focus:outline-none focus:ring-2 focus:ring-amber-400 text-sm font-mono tracking-wider">
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center py-3.5 px-4 rounded-xl text-sm font-extrabold text-white bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 transition shadow-xl hover:shadow-amber-500/25 border border-amber-400/30">
                    Simpan PIN Baru ➔
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
