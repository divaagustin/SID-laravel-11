<x-filament-panels::page>
    @php
        $openDkService = app(\App\Services\OpenDkSyncService::class);
        $configured    = $openDkService->isConfigured();
        $payload       = $openDkService->buildPayload();
        $lastSync      = \Illuminate\Support\Facades\DB::table('log_sinkronisasi')
                            ->where('modul', 'OpenDK Sync')
                            ->orderByDesc('created_at')
                            ->first();
    @endphp

    @if(! $configured)
    <x-filament::section>
        <div class="flex items-start gap-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <div class="text-amber-500 text-2xl mt-1">⚠️</div>
            <div>
                <h3 class="font-semibold text-amber-800 text-base">API OpenDK Belum Dikonfigurasi</h3>
                <p class="text-amber-700 text-sm mt-1">
                    Untuk mensinkronkan data statistik kependudukan dan persuratan desa ke server Kecamatan (OpenDK), 
                    masukkan kredensial API berikut di file <code>.env</code>:
                </p>
                <div class="mt-3 bg-amber-900 text-amber-100 text-xs rounded-lg p-3 font-mono">
                    OPENDK_URL=https://opendk.kecamatan.go.id/api/v1/sync<br>
                    OPENDK_API_KEY=secret_api_key_opendk_desa<br>
                    OPENDK_DESA_CODE={{ $payload['desa']['kode_desa'] ?? '12.09.18.2001' }}
                </div>
                <p class="text-amber-600 text-xs mt-2">
                    Setelah mengedit .env, jalankan <code>php artisan config:clear</code> lalu refresh halaman ini.
                </p>
            </div>
        </div>
    </x-filament::section>
    @else
    <x-filament::section>
        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="text-green-500 text-2xl">✅</div>
                <div>
                    <h3 class="font-semibold text-green-800 text-base">API OpenDK Terhubung & Siap</h3>
                    <p class="text-green-600 text-xs mt-0.5">
                        Kode Desa: <span class="font-mono font-bold">{{ $payload['desa']['kode_desa'] }}</span> | 
                        Terakhir Disinkronkan: <span class="font-bold">{{ $lastSync ? \Carbon\Carbon::parse($lastSync->created_at)->format('d F Y, H:i') . ' WIB' : 'Belum Pernah' }}</span>
                    </p>
                </div>
            </div>
            <div>
                <p class="text-xs text-green-700">Sinkronisasi otomatis berjalan setiap malam pukul 01:00 WIB.</p>
            </div>
        </div>
    </x-filament::section>
    @endif

    {{-- Payload Preview Section --}}
    <x-filament::section>
        <x-slot name="heading">
            📊 Pratinjau Payload Data Sinkronisasi Desa
        </x-slot>
        <x-slot name="description">
            Struktur data agregat yang dikirimkan secara aman ke server Kecamatan (OpenDK)
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl">
                <div class="text-xs text-gray-500 font-medium uppercase">Total Penduduk Desa</div>
                <div class="text-2xl font-black text-gray-900 mt-1">{{ number_format($payload['kependudukan']['total_penduduk']) }} Jiwa</div>
                <div class="text-[11px] text-gray-400 mt-1">L: {{ $payload['kependudukan']['laki_laki'] }} | P: {{ $payload['kependudukan']['perempuan'] }}</div>
            </div>

            <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl">
                <div class="text-xs text-gray-500 font-medium uppercase">Total Kepala Keluarga</div>
                <div class="text-2xl font-black text-gray-900 mt-1">{{ number_format($payload['kependudukan']['total_keluarga']) }} KK</div>
                <div class="text-[11px] text-gray-400 mt-1">Akun Warga Mandiri: {{ $payload['kependudukan']['warga_mandiri'] }}</div>
            </div>

            <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl">
                <div class="text-xs text-gray-500 font-medium uppercase">Total Transaksi Persuratan</div>
                <div class="text-2xl font-black text-gray-900 mt-1">{{ number_format($payload['layanan']['total_log_surat']) }} Surat</div>
                <div class="text-[11px] text-gray-400 mt-1">Permohonan Online: {{ $payload['layanan']['permohonan_online'] }}</div>
            </div>
        </div>

        <details class="bg-gray-900 text-green-400 font-mono text-xs p-4 rounded-xl">
            <summary class="cursor-pointer font-bold text-gray-200 mb-2">Inspect Raw JSON Payload (Strict Sanitized Schema)</summary>
            <pre class="overflow-x-auto">{{ json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </details>
    </x-filament::section>
</x-filament-panels::page>
