<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Fix tweb_keluarga (nik_kepala NIK out of range & no_kk)
        if (Schema::hasTable('tweb_keluarga')) {
            try {
                DB::statement("ALTER TABLE `tweb_keluarga` MODIFY COLUMN `nik_kepala` VARCHAR(30) NULL");
                DB::statement("ALTER TABLE `tweb_keluarga` MODIFY COLUMN `no_kk` VARCHAR(30) NULL");
            } catch (\Throwable $e) {}
        }

        // 2. Fix kelompok (id_master default value & nullables)
        if (Schema::hasTable('kelompok')) {
            try {
                DB::statement("ALTER TABLE `kelompok` MODIFY COLUMN `id_master` INT NULL DEFAULT 1");
                DB::statement("ALTER TABLE `kelompok` MODIFY COLUMN `keterangan` TEXT NULL");
                DB::statement("ALTER TABLE `kelompok` MODIFY COLUMN `no_sk_pendirian` VARCHAR(100) NULL");
                DB::statement("ALTER TABLE `kelompok` MODIFY COLUMN `config_id` INT NULL DEFAULT 1");
            } catch (\Throwable $e) {}
        }

        // 3. Fix lokasi (desk cannot be null)
        if (Schema::hasTable('lokasi')) {
            try {
                DB::statement("ALTER TABLE `lokasi` MODIFY COLUMN `desk` TEXT NULL");
                DB::statement("ALTER TABLE `lokasi` MODIFY COLUMN `ref_point` INT NULL");
                DB::statement("ALTER TABLE `lokasi` MODIFY COLUMN `foto` VARCHAR(255) NULL");
                DB::statement("ALTER TABLE `lokasi` MODIFY COLUMN `config_id` INT NULL DEFAULT 1");
            } catch (\Throwable $e) {}
        }

        // 4. Fix area (desk cannot be null)
        if (Schema::hasTable('area')) {
            try {
                DB::statement("ALTER TABLE `area` MODIFY COLUMN `desk` TEXT NULL");
                DB::statement("ALTER TABLE `area` MODIFY COLUMN `path` LONGTEXT NULL");
                DB::statement("ALTER TABLE `area` MODIFY COLUMN `config_id` INT NULL DEFAULT 1");
            } catch (\Throwable $e) {}
        }

        // 5. Fix inventaris_asset (keterangan cannot be null)
        if (Schema::hasTable('inventaris_asset')) {
            try {
                DB::statement("ALTER TABLE `inventaris_asset` MODIFY COLUMN `keterangan` TEXT NULL");
                DB::statement("ALTER TABLE `inventaris_asset` MODIFY COLUMN `kode_barang` VARCHAR(100) NULL");
                DB::statement("ALTER TABLE `inventaris_asset` MODIFY COLUMN `register` VARCHAR(100) NULL");
                DB::statement("ALTER TABLE `inventaris_asset` MODIFY COLUMN `asal` VARCHAR(100) NULL");
                DB::statement("ALTER TABLE `inventaris_asset` MODIFY COLUMN `config_id` INT NULL DEFAULT 1");
            } catch (\Throwable $e) {}
        }

        // 6. Fix tweb_surat_format (config_id default value)
        if (Schema::hasTable('tweb_surat_format')) {
            try {
                DB::statement("ALTER TABLE `tweb_surat_format` MODIFY COLUMN `config_id` INT NULL DEFAULT 1");
                DB::statement("ALTER TABLE `tweb_surat_format` MODIFY COLUMN `kode_surat` VARCHAR(100) NULL");
                DB::statement("ALTER TABLE `tweb_surat_format` MODIFY COLUMN `format_nomor` VARCHAR(100) NULL");
                DB::statement("ALTER TABLE `tweb_surat_format` MODIFY COLUMN `form_isian` LONGTEXT NULL");
            } catch (\Throwable $e) {}
        }

        // 7. Fix tweb_penduduk NIK & NO_KK fields
        if (Schema::hasTable('tweb_penduduk')) {
            try {
                DB::statement("ALTER TABLE `tweb_penduduk` MODIFY COLUMN `nik` VARCHAR(30) NULL");
                DB::statement("ALTER TABLE `tweb_penduduk` MODIFY COLUMN `ayah_nik` VARCHAR(30) NULL");
                DB::statement("ALTER TABLE `tweb_penduduk` MODIFY COLUMN `ibu_nik` VARCHAR(30) NULL");
                DB::statement("ALTER TABLE `tweb_penduduk` MODIFY COLUMN `no_kk_sebelumnya` VARCHAR(30) NULL");
            } catch (\Throwable $e) {}
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // No down needed
    }
};
