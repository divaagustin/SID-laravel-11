<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                🏗️ Manajemen Proyek & Pembangunan Desa
            </h2>
            <a href="/admin/pembangunans" class="text-xs text-emerald-600 dark:text-emerald-400 font-bold hover:underline">Lihat Semua ➔</a>
        </div>

        <div class="space-y-3">
            @forelse($proyeks as $proyek)
                <div class="p-3 bg-gray-50 dark:bg-gray-800/60 rounded-xl border border-gray-100 dark:border-gray-700/60">
                    <div class="flex items-center justify-between mb-1.5">
                        <h4 class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $proyek->judul }}</h4>
                        <span class="text-[10px] text-amber-600 dark:text-amber-400 font-extrabold bg-amber-50 dark:bg-amber-950 px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-800">
                            Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden mb-1">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ min(100, $proyek->progres ?? 75) }}%;"></div>
                    </div>
                    <div class="flex justify-between text-[10px] text-gray-500 font-medium">
                        <span>Lokasi: {{ $proyek->lokasi ?? 'Dusun I' }}</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">Progres: {{ $proyek->progres ?? 75 }}%</span>
                    </div>
                </div>
            @empty
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl text-center">
                    <p class="text-xs text-gray-500">Belum ada proyek pembangunan terdaftar.</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
