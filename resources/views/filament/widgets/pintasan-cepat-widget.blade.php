<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                ⚡ Pintasan Cepat Aksi Admin
            </h2>
            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold bg-emerald-50 dark:bg-emerald-950 px-2.5 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">Operasional</span>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <a href="/admin/surat-keluars/create" class="flex flex-col items-center justify-center p-3.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 rounded-xl border border-emerald-200/60 dark:border-emerald-800/60 transition-all text-center group">
                <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">📄</span>
                <span class="text-xs font-bold">Buat Surat Baru</span>
            </a>

            <a href="/admin/antrean-tte" class="flex flex-col items-center justify-center p-3.5 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/40 dark:hover:bg-amber-900/60 text-amber-800 dark:text-amber-200 rounded-xl border border-amber-200/60 dark:border-amber-800/60 transition-all text-center group">
                <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">✒️</span>
                <span class="text-xs font-bold">Antrean TTE Sekdes</span>
            </a>

            <a href="/admin/penduduks/create" class="flex flex-col items-center justify-center p-3.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/60 text-blue-800 dark:text-blue-200 rounded-xl border border-blue-200/60 dark:border-blue-800/60 transition-all text-center group">
                <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">👤</span>
                <span class="text-xs font-bold">Tambah Penduduk</span>
            </a>

            <a href="/admin/pesan-mandiris" class="flex flex-col items-center justify-center p-3.5 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/60 text-purple-800 dark:text-purple-200 rounded-xl border border-purple-200/60 dark:border-purple-800/60 transition-all text-center group">
                <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">💬</span>
                <span class="text-xs font-bold">Tanggapi Pengaduan</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
