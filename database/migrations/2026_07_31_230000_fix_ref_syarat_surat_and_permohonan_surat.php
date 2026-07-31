<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Fix ref_syarat_surat primary key AUTO_INCREMENT
        if (Schema::hasTable('ref_syarat_surat')) {
            try {
                $hasZero = DB::table('ref_syarat_surat')->where('ref_syarat_id', 0)->exists();
                if ($hasZero) {
                    $maxId = (int) (DB::table('ref_syarat_surat')->max('ref_syarat_id') ?? 0);
                    DB::table('ref_syarat_surat')->where('ref_syarat_id', 0)->update(['ref_syarat_id' => $maxId + 1]);
                }
            } catch (\Throwable $e) {}

            try {
                DB::statement("ALTER TABLE `ref_syarat_surat` MODIFY COLUMN `ref_syarat_id` INT NOT NULL AUTO_INCREMENT");
            } catch (\Throwable $e) {
                try {
                    DB::statement("ALTER TABLE `ref_syarat_surat` ADD PRIMARY KEY (`ref_syarat_id`)");
                    DB::statement("ALTER TABLE `ref_syarat_surat` MODIFY COLUMN `ref_syarat_id` INT NOT NULL AUTO_INCREMENT");
                } catch (\Throwable $ex) {}
            }
        }

        // 2. Fix permohonan_surat config_id & nullables
        if (Schema::hasTable('permohonan_surat')) {
            try {
                DB::statement("ALTER TABLE `permohonan_surat` MODIFY COLUMN `config_id` INT NULL DEFAULT 1");
                DB::statement("ALTER TABLE `permohonan_surat` MODIFY COLUMN `alasan` TEXT NULL");
                DB::statement("ALTER TABLE `permohonan_surat` MODIFY COLUMN `keterangan` TEXT NULL");
            } catch (\Throwable $e) {}
        }

        // 3. Scan & fix ANY remaining table with custom primary key ending in _id
        $dbName = DB::getDatabaseName();
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . $dbName;

        foreach ($tables as $table) {
            if (! isset($table->{$key})) {
                continue;
            }

            $tableName = $table->{$key};
            $primaryKeys = DB::select("SHOW KEYS FROM `{$tableName}` WHERE Key_name = 'PRIMARY'");
            
            if (empty($primaryKeys)) {
                continue;
            }

            $pkCol = $primaryKeys[0]->Column_name;
            $columnInfo = DB::select("SHOW COLUMNS FROM `{$tableName}` LIKE '{$pkCol}'");
            if (empty($columnInfo)) {
                continue;
            }

            $col = $columnInfo[0];
            $isAuto = strpos($col->Extra, 'auto_increment') !== false;

            if (! $isAuto && strpos($col->Type, 'int') !== false) {
                try {
                    $maxId = (int) (DB::table($tableName)->max($pkCol) ?? 0);
                    $nextId = max($maxId + 1, 1);
                    DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `{$pkCol}` BIGINT NOT NULL AUTO_INCREMENT");
                    DB::statement("ALTER TABLE `{$tableName}` AUTO_INCREMENT = {$nextId}");
                } catch (\Throwable $e) {}
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // No down needed
    }
};
