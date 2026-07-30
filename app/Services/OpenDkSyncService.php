<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Penduduk;

/**
 * OpenDkSyncService — Service Gateway Sinkronisasi Data Desa ke OpenDK (Kecamatan)
 */
class OpenDkSyncService
{
    private string $url;
    private string $apiKey;
    private string $kodeDesa;
    private int $timeout;

    public function __construct()
    {
        $this->url      = config('services.opendk.url', '');
        $this->apiKey   = config('services.opendk.api_key', '');
        $this->kodeDesa = config('services.opendk.kode_desa', '12.09.18.2001');
        $this->timeout  = (int) config('services.opendk.timeout', 30);
    }

    public function isConfigured(): bool
    {
        return ! empty($this->url) && ! empty($this->apiKey);
    }

    private function generateSignature(int $timestamp): string
    {
        $payload = $timestamp . '.' . $this->kodeDesa;
        return hash_hmac('sha256', $payload, $this->apiKey);
    }

    public function buildPayload(): array
    {
        $config = DB::table('config')->first();

        $profil = [
            'nama_desa'      => $config->nama_desa ?? '',
            'kode_desa'      => $config->kode_desa ?? $this->kodeDesa,
            'nama_kecamatan' => $config->nama_kecamatan ?? '',
            'nama_kabupaten' => $config->nama_kabupaten ?? '',
            'nama_provinsi'  => $config->nama_propinsi ?? '',
            'kode_pos'       => $config->kode_pos ?? '',
            'alamat_kantor'  => $config->alamat_kantor ?? '',
            'email_desa'     => $config->email_desa ?? '',
            'telepon'        => $config->telepon ?? '',
        ];

        $kependudukan = [
            'total_penduduk' => Penduduk::withoutGlobalScopes()->where('status_dasar', 1)->count(),
            'total_keluarga' => DB::table('tweb_keluarga')->count(),
            'laki_laki'     => Penduduk::withoutGlobalScopes()->where('status_dasar', 1)->where('sex', 1)->count(),
            'perempuan'     => Penduduk::withoutGlobalScopes()->where('status_dasar', 1)->where('sex', 2)->count(),
            'warga_mandiri'  => DB::table('tweb_penduduk_mandiri')->count(),
        ];

        $layanan = [
            'total_log_surat'   => DB::table('log_surat')->count(),
            'permohonan_online' => DB::table('permohonan_surat')->count(),
        ];

        return [
            'kode_desa'    => $this->kodeDesa,
            'timestamp'    => time(),
            'desa'         => ['kode_desa' => $this->kodeDesa],
            'profil'       => $profil,
            'statistik'    => $kependudukan,
            'kependudukan' => $kependudukan,
            'layanan'      => $layanan,
        ];
    }

    public function sync(): array
    {
        return $this->sendSyncData();
    }

    public function sendSyncData(): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'OpenDK API belum dikonfigurasi. Isi OPENDK_URL dan OPENDK_API_KEY di file .env terlebih dahulu.',
            ];
        }

        $timestamp = time();
        $signature = $this->generateSignature($timestamp);
        $payload   = $this->buildPayload();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'X-Kode-Desa'   => $this->kodeDesa,
                'X-Timestamp'   => $timestamp,
                'X-Signature'   => $signature,
                'Accept'        => 'application/json',
            ])
            ->timeout($this->timeout)
            ->post($this->url, $payload);

            $statusCode = $response->status();
            $responseData = $response->json() ?? [];

            if ($response->successful()) {
                Log::info("OpenDK Sync Successful for Desa {$this->kodeDesa}", ['status' => $statusCode]);

                return [
                    'success'     => true,
                    'message'     => 'Data desa berhasil disinkronkan ke server OpenDK Kecamatan.',
                    'status_code' => $statusCode,
                    'response'    => $responseData,
                ];
            }

            return [
                'success'     => false,
                'message'     => "HTTP {$statusCode}: " . ($responseData['message'] ?? $response->body()),
                'status_code' => $statusCode,
                'response'    => $responseData,
            ];

        } catch (\Exception $e) {
            Log::error("OpenDK Sync Exception: " . $e->getMessage());

            return [
                'success'     => false,
                'message'     => $e->getMessage(),
                'status_code' => 500,
                'response'    => [],
            ];
        }
    }
}
