<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                🛍️ Potensi Desa & Lapak BUMDes
            </h2>
            <a href="/admin/produk-desas" class="text-xs text-amber-600 dark:text-amber-400 font-bold hover:underline">Lihat Semua ➔</a>
        </div>

        <div class="space-y-3">
            @forelse($produks as $produk)
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/60 rounded-xl border border-gray-100 dark:border-gray-700/60">
                    <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-950 flex items-center justify-center text-2xl flex-shrink-0 font-bold text-amber-800 dark:text-amber-200">
                        📦
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $produk->nama }}</h4>
                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    </div>
                    <span class="text-[10px] bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-extrabold px-2 py-1 rounded-md">Aktif</span>
                </div>
            @empty
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl text-center">
                    <p class="text-xs text-gray-500">Belum ada produk UMKM / BUMDes terdaftar.</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
