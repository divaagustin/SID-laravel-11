<x-filament-panels::page>
    @php
        $waService = app(\App\Services\WhatsappNotificationService::class);
        $configured = $waService->isConfigured();
        $provider = config('services.whatsapp.provider', 'fonnte');
    @endphp

    @if(! $configured)
    <x-filament::section>
        <div class="flex items-start gap-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <div class="text-amber-500 text-2xl mt-1">⚠️</div>
            <div>
                <h3 class="font-semibold text-amber-800 text-base">Gateway WhatsApp Belum Dikonfigurasi</h3>
                <p class="text-amber-700 text-sm mt-1">
                    Untuk mengaktifkan pengiriman notifikasi WhatsApp otomatis ke warga saat status surat berubah,
                    Anda perlu mendaftar di provider Gateway WhatsApp (seperti 
                    <a href="https://fonnte.com" target="_blank" class="underline font-medium">Fonnte</a>, 
                    <a href="https://wablas.com" target="_blank" class="underline font-medium">Wablas</a>, atau 
                    <a href="https://ruanggwa.com" target="_blank" class="underline font-medium">Ruanggwa</a>)
                    dan memasukkan kredensial di file <code>.env</code>:
                </p>
                <div class="mt-3 bg-amber-900 text-amber-100 text-xs rounded-lg p-3 font-mono">
                    WA_GATEWAY_PROVIDER=fonnte<br>
                    WA_GATEWAY_URL=https://api.fonnte.com/send<br>
                    WA_GATEWAY_TOKEN=token_api_gateway_anda
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
                    <h3 class="font-semibold text-green-800 text-base">WhatsApp Gateway Aktif</h3>
                    <p class="text-green-600 text-xs mt-0.5">Provider: <span class="font-mono font-bold uppercase">{{ $provider }}</span> | Gateway API Token terkonfigurasi.</p>
                </div>
            </div>
            <div>
                <p class="text-xs text-green-700">Gunakan tombol "Kirim Tes Pesan WA" di kanan atas untuk menguji koneksi.</p>
            </div>
        </div>
    </x-filament::section>
    @endif

    <x-filament::section>
        <x-slot name="heading">
            📱 Informasi Layanan Notifikasi WhatsApp
        </x-slot>
        <div class="text-sm text-gray-600 space-y-2">
            <p>Sistem ini secara otomatis mengirimkan notifikasi WhatsApp kepada warga dalam skenario berikut:</p>
            <ul class="list-disc list-inside space-y-1 text-xs text-gray-700">
                <li><strong>Saat Draf Dibuat (Status Diproses):</strong> "Halo [Nama], permohonan surat [JenisSurat] (Resi: [NoAntrian]) sedang diproses oleh Operator Desa."</li>
                <li><strong>Saat Permohonan Perlu Revisi / Ditolak:</strong> "Halo [Nama], permohonan surat [JenisSurat] memerlukan revisi berkas: [Alasan]."</li>
                <li><strong>Saat Surat Selesai & TTE:</strong> "Halo [Nama], permohonan surat [JenisSurat] telah SELESAI dan ditandatangani TTE secara elektronik."</li>
            </ul>
        </div>
    </x-filament::section>
</x-filament-panels::page>
