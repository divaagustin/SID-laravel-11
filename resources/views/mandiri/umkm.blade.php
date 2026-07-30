@extends('layouts.portal')

@section('title', 'Kelola UMKM Saya — Layanan Mandiri')

@section('content')
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-12" style="padding-top: 100px;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kelola UMKM &amp; Usaha Saya</h1>
            <p class="text-emerald-200 text-xs sm:text-sm mt-1">Daftarkan produk atau usaha harian Anda agar tampil di Katalog Publik Desa.</p>
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
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-3">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- FORM TAMBAH UMKM --}}
        <div class="lg:col-span-1">
            <div class="glass-card p-6 rounded-3xl border border-slate-200 shadow-xl">
                <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center gap-2">
                    ➕ Tambah Usaha Baru
                </h3>
                <form action="{{ route('mandiri.umkm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Usaha / Produk</label>
                        <input type="text" name="nama_usaha" required placeholder="Contoh: Warung Kelontong Berkah" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Usaha</label>
                        <select name="kategori_usaha" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="Kuliner">Kuliner / Olahan Makanan</option>
                            <option value="Sembako">Sembako &amp; Kelontong</option>
                            <option value="Elektronik/Konter">Elektronik &amp; Konter Pulsa</option>
                            <option value="Pertanian">Pertanian &amp; Hasil Bumi</option>
                            <option value="Pabrik/Manufaktur">Pabrik / Manufaktur</option>
                            <option value="Jasa_Tetap">Jasa Tetap (Bengkel, Jahit, Salon)</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Aktif</label>
                        <input type="text" name="no_whatsapp" required placeholder="08123456789" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jam Operasional</label>
                        <input type="text" name="jam_operasional" placeholder="08.00 - 17.00 WIB" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lokasi Usaha</label>
                        <textarea name="alamat_usaha" rows="2" placeholder="Dusun II, Jalan Flamboyan No 12" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Foto Usaha / Produk</label>
                        <input type="file" name="foto_usaha" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Produk</label>
                        <textarea name="deskripsi_produk" rows="3" placeholder="Jelaskan jenis makanan, produk unggulan, harga, dll..." class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-green-700 hover:from-emerald-700 hover:to-green-800 text-white font-extrabold py-3 px-4 rounded-xl text-xs transition shadow-md">
                        Kirinkan Pengajuan UMKM ➔
                    </button>
                </form>
            </div>
        </div>

        {{-- DAFTAR UMKM SAYA --}}
        <div class="lg:col-span-2">
            <div class="glass-card p-6 rounded-3xl border border-slate-200 shadow-xl">
                <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center gap-2">
                    🏬 Daftar Usaha Milik Saya
                </h3>

                @if($umkms->count())
                    <div class="space-y-4">
                        @foreach($umkms as $item)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ get_media_url($item->foto_usaha, 'galeri') }}" alt="{{ $item->nama_usaha }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-slate-900 text-sm">{{ $item->nama_usaha }}</h4>
                                            <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full border uppercase {{ $item->status_moderasi == 'approved' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($item->status_moderasi == 'rejected' ? 'bg-red-100 text-red-800 border-red-300' : 'bg-amber-100 text-amber-800 border-amber-300') }}">
                                                {{ $item->status_moderasi }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">Kategori: <span class="font-semibold text-slate-700">{{ $item->kategori_usaha }}</span> | WA: {{ $item->no_whatsapp }}</p>
                                        <p class="text-[11px] text-slate-400 mt-1">Status Operasional: <span class="font-bold {{ $item->status_operasional == 'buka' ? 'text-emerald-600' : 'text-red-600' }}">{{ strtoupper($item->status_operasional) }}</span></p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <form action="{{ route('mandiri.umkm.toggle', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold border transition shadow-sm {{ $item->status_operasional == 'buka' ? 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' }}">
                                            Set {{ $item->status_operasional == 'buka' ? 'Tutup' : 'Buka' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center text-slate-400">
                        <div class="text-4xl mb-2">🏪</div>
                        <p class="text-xs font-bold text-slate-600">Anda belum mendaftarkan usaha UMKM.</p>
                        <p class="text-[11px] text-slate-400 mt-1">Gunakan formulir di samping untuk mengajukan usaha milik Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
