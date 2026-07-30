<?php

namespace App\Services;

use App\Models\PermohonanSurat;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsappNotificationService — Service Gateway WhatsApp untuk Notifikasi Layanan Desa
 *
 * Mendukung Provider Gateway populer:
 *   1. Fonnte (https://fonnte.com) — Default
 *   2. Wablas (https://wablas.com)
 *   3. Ruanggwa (https://ruanggwa.com)
 *   4. Generic Custom HTTP API Gateway
 *
 * Konfigurasi .env:
 *   WA_GATEWAY_PROVIDER=fonnte
 *   WA_GATEWAY_URL=https://api.fonnte.com/send
 *   WA_GATEWAY_TOKEN=token_api_gateway_anda
 */
class WhatsappNotificationService
{
    private string $provider;
    private string $url;
    private string $token;
    private int $timeout;

    public function __construct()
    {
        $this->provider = config('services.whatsapp.provider', 'fonnte');
        $this->url      = config('services.whatsapp.url', 'https://api.fonnte.com/send');
        $this->token    = config('services.whatsapp.token', '');
        $this->timeout  = (int) config('services.whatsapp.timeout', 15);
    }

    /**
     * Cek apakah Service WhatsApp Gateway sudah dikonfigurasi.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->token);
    }

    /**
     * Format nomor telepon Indonesia menjadi format standar WhatsApp (misal: 628123456789).
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Hapus karakter non-digit
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Ubah awalan 08xxx menjadi 628xxx
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        }

        return $cleaned;
    }

    /**
     * Kirim pesan WhatsApp ke nomor tujuan.
     *
     * @param  string  $targetPhone  Nomor tujuan (contoh: 08123456789 atau 628123456789)
     * @param  string  $message      Isi pesan teks
     * @return array   ['success' => bool, 'message' => string, 'response' => array]
     */
    public function send(string $targetPhone, string $message): array
    {
        if (! $this->isConfigured()) {
            return [
                'success'  => false,
                'message'  => 'WhatsApp Gateway belum dikonfigurasi. Isi WA_GATEWAY_TOKEN di file .env terlebih dahulu.',
                'response' => [],
            ];
        }

        $formattedPhone = $this->formatPhoneNumber($targetPhone);

        try {
            switch (strtolower($this->provider)) {
                case 'wablas':
                    $response = Http::withHeaders(['Authorization' => $this->token])
                        ->timeout($this->timeout)
                        ->post($this->url, [
                            'phone'   => $formattedPhone,
                            'message' => $message,
                        ]);
                    break;

                case 'ruanggwa':
                    $response = Http::timeout($this->timeout)
                        ->post($this->url, [
                            'token'   => $this->token,
                            'number'  => $formattedPhone,
                            'message' => $message,
                        ]);
                    break;

                case 'fonnte':
                default:
                    $response = Http::withHeaders(['Authorization' => $this->token])
                        ->timeout($this->timeout)
                        ->post($this->url, [
                            'target'  => $formattedPhone,
                            'message' => $message,
                        ]);
                    break;
            }

            if ($response->successful()) {
                Log::info("WhatsApp Sent to {$formattedPhone}", ['provider' => $this->provider]);

                return [
                    'success'  => true,
                    'message'  => 'Pesan WhatsApp berhasil dikirim.',
                    'response' => $response->json() ?? [],
                ];
            }

            Log::error("WhatsApp Failed to {$formattedPhone}", [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success'  => false,
                'message'  => "HTTP {$response->status()}: " . $response->body(),
                'response' => $response->json() ?? [],
            ];

        } catch (\Exception $e) {
            Log::error("WhatsApp Exception to {$formattedPhone}: " . $e->getMessage());

            return [
                'success'  => false,
                'message'  => $e->getMessage(),
                'response' => [],
            ];
        }
    }

    /**
     * Kirim notifikasi perubahan status permohonan surat ke WhatsApp warga.
     *
     * @param  PermohonanSurat  $permohonan
     * @param  string  $event  'proses' | 'revisi' | 'selesai'
     */
    public function sendSuratNotification(PermohonanSurat $permohonan, string $event): array
    {
        $targetPhone = $permohonan->no_hp_aktif;
        if (empty($targetPhone)) {
            return ['success' => false, 'message' => 'Nomor HP warga tidak tersedia.'];
        }

        $namaWarga  = $permohonan->pemohon->nama ?? 'Warga';
        $jenisSurat = $permohonan->formatSurat->nama ?? 'Surat Keterangan';
        $noResi     = $permohonan->no_antrian;

        $message = match ($event) {
            'proses' => "Halo Bapak/Ibu {$namaWarga},\n\nPermohonan surat *{$jenisSurat}* Anda dengan No. Resi *{$noResi}* sedang diproses oleh Operator Desa.\n\nSalam,\nPemerintah Desa",
            'revisi' => "Halo Bapak/Ibu {$namaWarga},\n\nPermohonan surat *{$jenisSurat}* Anda (Resi: *{$noResi}*) memerlukan revisi berkas.\nCatatan:_{$permohonan->alasan}_\n\nSilakan perbaiki melalui portal Layanan Mandiri Warga.\n\nSalam,\nPemerintah Desa",
            'selesai' => "Halo Bapak/Ibu {$namaWarga},\n\nPermohonan surat *{$jenisSurat}* Anda (Resi: *{$noResi}*) telah SELESAI dan ditandatangani TTE secara elektronik.\n\nDokumen sudah siap diunduh di portal Layanan Mandiri Warga.\n\nSalam,\nPemerintah Desa",
            default   => "Halo {$namaWarga}, ada pembaruan status permohonan surat {$jenisSurat} (Resi: {$noResi}).",
        };

        return $this->send($targetPhone, $message);
    }
}
