<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * TteService — Layanan Tanda Tangan Elektronik (TTE) BSrE
 *
 * Integrasi dengan API BSrE (Balai Sertifikasi Elektronik)
 * milik Badan Siber dan Sandi Negara (BSSN).
 *
 * Dokumentasi API BSrE: https://tte.bssn.go.id
 *
 * Konfigurasi required di .env:
 *   BSRE_URL=https://tte.bssn.go.id
 *   BSRE_USERNAME=username_tte_anda
 *   BSRE_PASSWORD=password_tte_anda
 *   BSRE_TIMEOUT=30
 *   BSRE_NIK_KEPALA_DESA=nik_kepala_desa (untuk menentukan penandatangan)
 *
 * Cara mendaftar BSrE:
 *   1. Kunjungi https://tte.bssn.go.id
 *   2. Daftar akun instansi pemerintah desa
 *   3. Upload dokumen legalitas desa
 *   4. Setelah disetujui, dapatkan credentials dan gunakan di .env
 */
class TteService
{
    private string $baseUrl;
    private string $username;
    private string $password;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl  = rtrim(config('services.bsre.url', ''), '/');
        $this->username = config('services.bsre.username', '');
        $this->password = config('services.bsre.password', '');
        $this->timeout  = (int) config('services.bsre.timeout', 30);
    }

    /**
     * Kirim PDF ke BSrE untuk ditandatangani secara elektronik.
     *
     * @param  string  $pdfPath   Path absolut ke file PDF yang akan ditandatangani
     * @param  string  $nik       NIK penandatangan (Kepala Desa)
     * @param  array   $options   Opsi tambahan: ['tampilan' => 'visible'|'invisible', 'halaman' => 1, ...]
     * @return array   ['success' => bool, 'signed_pdf' => binary|null, 'message' => string]
     */
    public function signPdf(string $pdfPath, string $nik, array $options = []): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'signed_pdf' => null,
                'message' => 'Layanan TTE BSrE belum dikonfigurasi. Isi BSRE_URL, BSRE_USERNAME, dan BSRE_PASSWORD di file .env.',
            ];
        }

        if (! file_exists($pdfPath)) {
            return [
                'success' => false,
                'signed_pdf' => null,
                'message' => "File PDF tidak ditemukan: {$pdfPath}",
            ];
        }

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->timeout($this->timeout)
                ->attach('pdf', file_get_contents($pdfPath), basename($pdfPath))
                ->post("{$this->baseUrl}/api/sign/pdf", array_merge([
                    'nik'      => $nik,
                    'passphrase' => $this->password,
                    'tampilan' => $options['tampilan'] ?? 'visible',
                    'image'    => $options['image'] ?? true,
                    'halaman'  => $options['halaman'] ?? 'all',
                    'tag_koordinat' => $options['tag_koordinat'] ?? '[ttd]',
                ], $options));

            if ($response->successful()) {
                Log::info('TTE BSrE: Penandatanganan berhasil', [
                    'nik'  => $nik,
                    'file' => basename($pdfPath),
                ]);

                return [
                    'success'    => true,
                    'signed_pdf' => $response->body(), // binary PDF
                    'message'    => 'Berhasil ditandatangani',
                ];
            }

            $errorBody = $response->json();
            Log::error('TTE BSrE: Gagal menandatangani PDF', [
                'status'   => $response->status(),
                'response' => $errorBody,
            ]);

            return [
                'success'    => false,
                'signed_pdf' => null,
                'message'    => $errorBody['message'] ?? "Error HTTP {$response->status()} dari BSrE",
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('TTE BSrE: Gagal terhubung ke server', ['error' => $e->getMessage()]);

            return [
                'success'    => false,
                'signed_pdf' => null,
                'message'    => 'Tidak dapat terhubung ke server BSrE. Periksa koneksi internet dan URL BSrE.',
            ];
        }
    }

    /**
     * Cek status verifikasi tanda tangan PDF menggunakan BSrE.
     *
     * @param  string  $pdfPath  Path absolut ke file PDF yang akan diverifikasi
     * @return array  ['valid' => bool, 'details' => array, 'message' => string]
     */
    public function verifyPdf(string $pdfPath): array
    {
        if (! $this->isConfigured()) {
            return [
                'valid'   => false,
                'details' => [],
                'message' => 'Layanan TTE BSrE belum dikonfigurasi.',
            ];
        }

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->timeout($this->timeout)
                ->attach('pdf', file_get_contents($pdfPath), basename($pdfPath))
                ->post("{$this->baseUrl}/api/verify/pdf");

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'valid'   => ($data['SignatureInfo'][0]['valid'] ?? false) === true,
                    'details' => $data['SignatureInfo'] ?? [],
                    'message' => 'Verifikasi selesai',
                ];
            }

            return [
                'valid'   => false,
                'details' => [],
                'message' => "Error HTTP {$response->status()} dari BSrE",
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return [
                'valid'   => false,
                'details' => [],
                'message' => 'Tidak dapat terhubung ke server BSrE.',
            ];
        }
    }

    /**
     * Cek apakah service sudah dikonfigurasi dengan benar.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->baseUrl)
            && ! empty($this->username)
            && ! empty($this->password);
    }

    /**
     * Ambil info akun dari BSrE (untuk verifikasi koneksi).
     *
     * @return array ['connected' => bool, 'info' => array]
     */
    public function checkConnection(): array
    {
        if (! $this->isConfigured()) {
            return ['connected' => false, 'info' => [], 'message' => 'Belum dikonfigurasi'];
        }

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->timeout(10)
                ->get("{$this->baseUrl}/api/user/profile");

            return [
                'connected' => $response->successful(),
                'info'      => $response->json() ?? [],
                'message'   => $response->successful() ? 'Terhubung' : "HTTP {$response->status()}",
            ];
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'info'      => [],
                'message'   => $e->getMessage(),
            ];
        }
    }
}
