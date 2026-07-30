@extends('layouts.portal')

@section('title', 'Kelola Jasa Warga — Layanan Mandiri')

@section('content')
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-12" style="padding-top: 100px;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Papan Pekerjaan Jasa Warga</h1>
            <p class="text-emerald-200 text-xs sm:text-sm mt-1">Buat permohonan bantuan pekerjaan harian atau kerjakan tugas milik warga desa lain.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('mandiri.dashboard') }}" class="glass-pill px-4 py-2 text-xs font-bold text-white hover:bg-white/20 transition rounded-xl">
                ⬅️ Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-3 shadow-sm">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-300 text-red-800 rounded-2xl text-xs font-bold flex items-center gap-3 shadow-sm">
            <span>⚠️</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- FORM POSTING JOB BARU --}}
        <div class="lg:col-span-1">
            <div class="glass-card p-6 rounded-3xl border border-slate-200 shadow-xl">
                <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center gap-2">
                    ➕ Buat Lowongan Job Baru
                </h3>
                <form action="{{ route('mandiri.jasa.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Pekerjaan / Bantuan</label>
                        <input type="text" name="judul_pekerjaan" required placeholder="Contoh: Butuh Bantuan Bersihkan Kebun Belakang" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Jasa</label>
                        <select name="kategori" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="Kebersihan">Kebersihan &amp; Rumah Tangga</option>
                            <option value="Pertukangan">Pertukangan &amp; Bangunan</option>
                            <option value="Anter_Jemput">Anter / Jemput &amp; Kurir</option>
                            <option value="Pertanian">Pertanian &amp; Perkebunan</option>
                            <option value="Akademik_Tugas">Akademik &amp; Tugas</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Upah Insentif (Fee Rp)</label>
                        <input type="number" name="fee_insentif" required placeholder="100000" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Dusun / RT (Tampil di Publik)</label>
                        <input type="text" name="lokasi_dusun_rt" required placeholder="Contoh: Dusun I / RT 02" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tenggat Waktu Pekerjaan</label>
                        <input type="datetime-local" name="tenggat_waktu" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Tugas Ringkas</label>
                        <textarea name="deskripsi_tugas" rows="3" required placeholder="Jelaskan detail tugas yang harus dikerjakan..." class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Detail Rumah (PRIVAT)</label>
                        <textarea name="alamat_detail" rows="2" required placeholder="Alamat rumah lengkap & nomor rumah (hanya dapat dilihat oleh warga yang meng-claim job)..." class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold py-3 px-4 rounded-xl text-xs transition shadow-md">
                        Posting Lowongan Jasa ➔
                    </button>
                </form>
            </div>
        </div>

        {{-- DAFTAR LOWONGAN WARGA & JOB SAYA --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 1. LOWONGAN WARGA LAIN YANG SIAP DI-TAKE --}}
            <div class="glass-card p-6 rounded-3xl border border-amber-300 shadow-xl bg-gradient-to-br from-amber-50/40 via-white to-amber-50/20">
                <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center gap-2">
                    📢 Lowongan Pekerjaan Warga yang Tersedia (Bisa Di-take)
                </h3>

                @if(isset($openJobs) && $openJobs->count())
                    <div class="space-y-4">
                        @foreach($openJobs as $job)
                            <div class="p-4 bg-white border border-amber-200 rounded-2xl shadow-sm hover:border-amber-400 transition">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="font-extrabold text-slate-900 text-sm">{{ $job->judul_pekerjaan }}</h4>
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        🟢 OPEN
                                    </span>
                                </div>

                                <div class="mt-2 text-xs text-slate-600 space-y-1">
                                    <p>Pembuat: <span class="font-bold text-slate-800">{{ $job->pembuat->nama ?? 'Warga Desa' }}</span> | Lokasi: <span class="font-bold text-slate-800">📍 {{ $job->lokasi_dusun_rt }}</span></p>
                                    <p class="text-slate-700 mt-1 line-clamp-2">{{ $job->deskripsi_tugas }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-sm font-black text-emerald-700">Fee: Rp {{ number_format($job->fee_insentif, 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full border border-amber-300">{{ $job->kategori }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-end">
                                    <form action="{{ route('mandiri.jasa.take', $job->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengambil/mengklaim pekerjaan ini?')" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl text-xs font-extrabold shadow-md transition flex items-center gap-1.5">
                                            💼 Ambil / Klaim Pekerjaan Ini ➔
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500">Saat ini belum ada lowongan pekerjaan baru dari warga lain yang dapat diambil.</p>
                @endif
            </div>

            {{-- 2. JOB PERMINTAAN SAYA --}}
            <div class="glass-card p-6 rounded-3xl border border-slate-200 shadow-xl">
                <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center gap-2">
                    📋 Job Request yang Saya Buat
                </h3>

                @if($myRequests->count())
                    <div class="space-y-4">
                        @foreach($myRequests as $item)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $item->judul_pekerjaan }}</h4>
                                    <span class="text-[9px] font-extrabold px-2.5 py-1 rounded-full border uppercase {{ $item->status_job == 'open' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($item->status_job == 'completed' ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-amber-100 text-amber-800 border-amber-300') }}">
                                        {{ $item->status_job }}
                                    </span>
                                </div>

                                <div class="mt-2 text-xs text-slate-600 space-y-1">
                                    <p>Fee: <span class="font-bold text-emerald-700">Rp {{ number_format($item->fee_insentif, 0, ',', '.') }}</span> | Kategori: {{ $item->kategori }}</p>
                                    <p>Pekerja: <span class="font-bold text-slate-800">{{ $item->pekerja->nama ?? 'Belum Ada Pekerja' }}</span></p>
                                </div>

                                <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                                    @if($item->status_job == 'in_progress')
                                        <form action="{{ route('mandiri.jasa.complete', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                                                ✅ Tandai Selesai
                                            </button>
                                        </form>
                                    @endif

                                    @if($item->status_job == 'open' || $item->status_job == 'in_progress')
                                        <form action="{{ route('mandiri.jasa.cancel', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition">
                                                ❌ Batalkan Job
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500">Anda belum pernah membuat postingan request pekerjaan.</p>
                @endif
            </div>

            {{-- 3. JOB ORANG LAIN YANG SAYA AMBIL --}}
            <div class="glass-card p-6 rounded-3xl border border-slate-200 shadow-xl">
                <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center gap-2">
                    🛠️ Pekerjaan Warga yang Saya Ambil (Worker)
                </h3>

                @if($myTakenJobs->count())
                    <div class="space-y-4">
                        @foreach($myTakenJobs as $item)
                            <div class="p-4 bg-emerald-50/60 border border-emerald-200 rounded-2xl">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $item->judul_pekerjaan }}</h4>
                                    <span class="text-[9px] font-extrabold px-2.5 py-1 rounded-full border uppercase bg-emerald-100 text-emerald-800 border-emerald-300">
                                        {{ $item->status_job }}
                                    </span>
                                </div>

                                <div class="mt-2 text-xs text-slate-700 space-y-1">
                                    <p>Pembuat Job: <span class="font-bold text-slate-900">{{ $item->pembuat->nama ?? 'Warga Desa' }}</span> (No WA: {{ $item->pembuat->telepon ?? 'Lihat di Kantor' }})</p>
                                    <p>Fee Insentif: <span class="font-black text-emerald-800">Rp {{ number_format($item->fee_insentif, 0, ',', '.') }}</span></p>
                                    
                                    {{-- PRIVAT ALAMAT DETAIL DITAMPILKAN SANGAT KHUSUS UNTUK WORKER --}}
                                    <div class="mt-3 p-3 bg-white border border-emerald-200 rounded-xl">
                                        <span class="text-[10px] font-extrabold uppercase text-amber-700 block">🔒 Alamat Detail Privat Rumah Pembuat Job:</span>
                                        <p class="text-xs font-semibold text-slate-800 mt-0.5">{{ $item->alamat_detail }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500">Anda belum pernah mengklaim/mengambil pekerjaan warga lain. Silakan pilih dari lowongan pekerjaan yang tersedia di atas.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
