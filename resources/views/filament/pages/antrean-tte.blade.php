<x-filament-panels::page>
    {{-- Header Info BSrE --}}
    @php
        $tte = app(\App\Services\TteService::class);
        $configured = $tte->isConfigured();
    @endphp

    @if(! $configured)
    <x-filament::section>
        <div class="flex items-start gap-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <div class="text-amber-500 text-2xl mt-1">⚠️</div>
            <div>
                <h3 class="font-semibold text-amber-800 text-base">Layanan TTE BSrE Belum Dikonfigurasi</h3>
                <p class="text-amber-700 text-sm mt-1">
                    Untuk menggunakan Tanda Tangan Elektronik, Anda perlu mendaftarkan instansi desa ke
                    <a href="https://tte.bssn.go.id" target="_blank" class="underline font-medium">tte.bssn.go.id</a>
                    dan mengisi variabel berikut di file <code>.env</code>:
                </p>
                <div class="mt-3 bg-amber-900 text-amber-100 text-xs rounded-lg p-3 font-mono">
                    BSRE_URL=https://tte.bssn.go.id<br>
                    BSRE_USERNAME=username_akun_bsre_anda<br>
                    BSRE_PASSWORD=password_akun_bsre_anda<br>
                    BSRE_NIK_KEPALA_DESA=nik_kepala_desa
                </div>
                <p class="text-amber-600 text-xs mt-2">
                    Setelah mengisi .env, jalankan <code>php artisan config:clear</code> lalu refresh halaman ini.
                </p>
            </div>
        </div>
    </x-filament::section>
    @else
    <x-filament::section>
        <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-xl">
            <div class="text-green-500 text-xl">✅</div>
            <div>
                <p class="font-semibold text-green-800 text-sm">BSrE Terkonfigurasi</p>
                <p class="text-green-600 text-xs">Gunakan tombol "Cek Koneksi BSrE" untuk memverifikasi koneksi ke server.</p>
            </div>
        </div>
    </x-filament::section>
    @endif

    {{-- Table --}}
    {{ $this->table }}
</x-filament-panels::page>
