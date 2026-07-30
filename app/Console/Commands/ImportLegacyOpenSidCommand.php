<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Penduduk;
use App\Models\Keluarga;

class ImportLegacyOpenSidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opensid:import-legacy {--db= : Database connection name or legacy DB name} {--file= : Path to CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data penduduk dan keluarga dari OpenSID lama (Database / File CSV)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== TOOLS MIGRASI & IMPOR DATA OPENID LAMA ===");

        $file = $this->option('file');
        $dbName = $this->option('db');

        if ($file && file_exists($file)) {
            $this->importFromCsv($file);
            return 0;
        }

        if ($dbName) {
            $this->importFromDatabase($dbName);
            return 0;
        }

        $this->warn("Penggunaan:");
        $this->line("1. Impor dari CSV:  php artisan opensid:import-legacy --file=storage/app/penduduk_legacy.csv");
        $this->line("2. Impor dari DB:   php artisan opensid:import-legacy --db=opensid_legacy_db");
        
        return 0;
    }

    /**
     * Import population records from legacy CSV export.
     */
    protected function importFromCsv(string $filePath)
    {
        $this->info("Membaca file CSV: {$filePath}");
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Gagal membuka file CSV.");
            return;
        }

        $header = fgetcsv($handle, 1000, ',');
        $imported = 0;

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if (count($data) < 3) continue;

            // Basic mapping
            $nik = trim($data[0] ?? '');
            $nama = trim($data[1] ?? '');
            $sex = (trim($data[2] ?? '') == 'L' || trim($data[2] ?? '') == '1') ? 1 : 2;
            $tempatLahir = trim($data[3] ?? 'Serdang');
            $tglLahir = trim($data[4] ?? '1990-01-01');

            if ($nik && strlen($nik) >= 15) {
                Penduduk::withoutGlobalScopes()->updateOrCreate(
                    ['nik' => $nik],
                    [
                        'nama' => $nama,
                        'sex' => $sex,
                        'tempatlahir' => $tempatLahir,
                        'tanggallahir' => $tglLahir,
                        'status_dasar' => 1,
                    ]
                );
                $imported++;
            }
        }

        fclose($handle);
        $this->info("✅ Berhasil mengimpor {$imported} data penduduk dari CSV.");
    }

    /**
     * Import population records directly from a legacy MySQL database.
     */
    protected function importFromDatabase(string $dbName)
    {
        $this->info("Mencoba koneksi ke database legacy: {$dbName}");

        try {
            $legacyPenduduk = DB::table("{$dbName}.tweb_penduduk")->get();
            $imported = 0;

            foreach ($legacyPenduduk as $p) {
                Penduduk::withoutGlobalScopes()->updateOrCreate(
                    ['nik' => $p->nik],
                    [
                        'nama' => $p->nama,
                        'id_kk' => $p->id_kk ?? null,
                        'kk_level' => $p->kk_level ?? 1,
                        'sex' => $p->sex ?? 1,
                        'tempatlahir' => $p->tempatlahir ?? 'Serdang',
                        'tanggallahir' => $p->tanggallahir ?? '1990-01-01',
                        'agama_id' => $p->agama_id ?? 1,
                        'pekerjaan_id' => $p->pekerjaan_id ?? 1,
                        'pendidikan_kk_id' => $p->pendidikan_kk_id ?? 1,
                        'status_kawin' => $p->status_kawin ?? 1,
                        'alamat_sekarang' => $p->alamat_sekarang ?? 'Desa Serdang',
                        'status_dasar' => $p->status_dasar ?? 1,
                    ]
                );
                $imported++;
            }

            $this->info("✅ Berhasil mengimpor {$imported} data penduduk langsung dari database {$dbName}.");
        } catch (\Exception $e) {
            $this->error("Gagal mengimpor dari database: " . $e->getMessage());
        }
    }
}
