@extends('layouts.portal')

@section('title', 'Dokumen Transparansi Publik & Peraturan Desa')

@section('content')

{{-- ===== HERO HEADER HALAMAN ===== --}}
<div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-green-950 text-white py-16 relative overflow-hidden shadow-2xl" style="padding-top: 100px;">
    <div class="absolute inset-0 batik-pattern opacity-10 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="text-amber-400 text-xs font-extrabold uppercase tracking-widest bg-white/10 px-4 py-1.5 rounded-full border border-amber-400/30 mb-3 inline-block">TRANSPARANSI INFORMASI</span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-md">Dokumen Publik &amp; Peraturan Desa</h1>
        <p class="text-emerald-100 max-w-2xl mx-auto text-sm sm:text-base mt-2 opacity-90">Unduh laporan APBDes, Peraturan Desa (Perdes), Surat Keputusan, dan berkas transparansi Desa {{ $config->nama_desa ?? 'Serdang' }}</p>

        {{-- Search Form --}}
        <form action="{{ route('dokumen') }}" method="GET" class="mt-8 max-w-xl mx-auto flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama dokumen / peraturan..."
                class="w-full px-5 py-3.5 rounded-2xl bg-white/90 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-amber-400 text-sm shadow-xl backdrop-blur-md border border-white/40">
            <button type="submit" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-7 py-3.5 rounded-2xl text-sm transition shadow-xl border border-amber-400/30 flex-shrink-0">
                🔍 Cari
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Document List Card --}}
    <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 mb-6 border-b border-slate-200 gap-4">
            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <span>📁</span> Berkas &amp; Dokumen Resmi Desa
            </h2>
            <span class="text-xs font-extrabold text-emerald-800 bg-emerald-100 px-3.5 py-1.5 rounded-full border border-emerald-200 w-max">
                Total: {{ $dokumens->total() }} Dokumen
            </span>
        </div>

        @if($dokumens->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-100/80 text-slate-900 font-extrabold uppercase tracking-wider text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-4">Nama Dokumen</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4 text-center">Tahun</th>
                            <th class="px-5 py-4 text-center">Tgl Upload</th>
                            <th class="px-5 py-4 text-right">Aksi &amp; Layanan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($dokumens as $doc)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4 font-extrabold text-slate-900 text-sm">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl flex-shrink-0">📄</span>
                                        <div>
                                            {{ $doc->nama }}
                                            @if($doc->keterangan)
                                                <p class="text-xs text-slate-500 font-normal mt-0.5">{{ $doc->keterangan }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-block px-3 py-1 bg-emerald-100/90 text-emerald-800 font-extrabold rounded-full text-[10px] border border-emerald-200">
                                        {{ $doc->kategori_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center font-mono font-bold whitespace-nowrap">{{ $doc->tahun ?? '-' }}</td>
                                <td class="px-5 py-4 text-center font-mono text-slate-400 whitespace-nowrap">{{ \Carbon\Carbon::parse($doc->tgl_upload)->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    @if($doc->satuan)
                                        <div class="inline-flex items-center gap-2">
                                            {{-- Tombol Baca Online 👁️ --}}
                                            <button onclick="openPdfModal('{{ route('dokumen.baca', $doc->id) }}', '{{ e($doc->nama) }}')"
                                                    class="inline-flex items-center gap-1.5 bg-slate-200 hover:bg-slate-300 text-slate-800 font-extrabold px-3.5 py-2 rounded-xl text-xs transition border border-slate-300">
                                                <span>👁️</span> Baca Online
                                            </button>

                                            {{-- Tombol Unduh PDF 📥 --}}
                                            <a href="{{ route('dokumen.unduh', $doc->id) }}"
                                               class="inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-extrabold px-4 py-2 rounded-xl text-xs shadow transition border border-amber-400/30">
                                                <span>📥</span> Unduh PDF
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Berkas Tidak Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 pt-4 border-t border-slate-100">
                {{ $dokumens->links() }}
            </div>
        @else
            <div class="text-center py-16 text-slate-400">
                <div class="text-5xl mb-3">📁</div>
                <h3 class="text-lg font-extrabold text-slate-700">Belum Ada Dokumen</h3>
                <p class="text-xs text-slate-500 mt-1">Dokumen publik akan ditampilkan di sini setelah diunggah oleh admin.</p>
            </div>
        @endif
    </div>
</div>

{{-- ===== MODAL READER DOKUMEN PDF ONLINE ===== --}}
<div id="pdfModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/80 backdrop-blur-md p-4 sm:p-6">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden border border-slate-200">
        <div class="p-4 bg-emerald-950 text-white flex items-center justify-between">
            <h3 id="pdfModalTitle" class="font-extrabold text-sm truncate flex items-center gap-2">
                <span>📄</span> Preview Dokumen
            </h3>
            <button onclick="closePdfModal()" class="text-slate-300 hover:text-white font-extrabold text-xl px-2 py-1">✕</button>
        </div>
        <div class="flex-grow bg-slate-100">
            <iframe id="pdfFrame" class="w-full h-full border-0" src="about:blank"></iframe>
        </div>
    </div>
</div>

<script>
    function openPdfModal(url, title) {
        document.getElementById('pdfModalTitle').innerText = '📄 ' + title;
        document.getElementById('pdfFrame').src = url;
        document.getElementById('pdfModal').classList.remove('hidden');
        document.getElementById('pdfModal').classList.add('flex');
    }

    function closePdfModal() {
        document.getElementById('pdfModal').classList.add('hidden');
        document.getElementById('pdfModal').classList.remove('flex');
        document.getElementById('pdfFrame').src = 'about:blank';
    }
</script>
@endsection
