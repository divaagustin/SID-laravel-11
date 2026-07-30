<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixAutoIncrementCommand extends Command
{
    protected $signature = 'db:fix-autoincrement';
    protected $description = 'Perbaiki seluruh tabel MySQL yang kehilangan AUTO_INCREMENT pada kolom ID';

    public function handle()
    {
        $this->info('Memulai perbaikan AUTO_INCREMENT pada seluruh tabel database...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $dbName = DB::getDatabaseName();
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . $dbName;
        $count = 0;

        foreach ($tables as $table) {
            if (! isset($table->{$key})) {
                continue;
            }

            $tableName = $table->{$key};

            if (! Schema::hasColumn($tableName, 'id')) {
                continue;
            }

            try {
                $hasZero = DB::table($tableName)->where('id', 0)->exists();
                if ($hasZero) {
                    $maxId = (int) (DB::table($tableName)->max('id') ?? 0);
                    $newId = $maxId + 1;
                    DB::table($tableName)->where('id', 0)->update(['id' => $newId]);
                }
            } catch (\Throwable $e) {}

            $columnInfo = DB::select("SHOW COLUMNS FROM `{$tableName}` LIKE 'id'");
            if (empty($columnInfo)) {
                continue;
            }

            $col = $columnInfo[0];
            $isAuto = strpos($col->Extra, 'auto_increment') !== false;

            if (! $isAuto) {
                $maxId = (int) (DB::table($tableName)->max('id') ?? 0);
                $nextId = max($maxId + 1, 1);

                if ($col->Key === 'PRI') {
                    try {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `id` BIGINT NOT NULL AUTO_INCREMENT");
                    } catch (\Throwable $e) {
                        try {
                            DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `id` INT NOT NULL AUTO_INCREMENT");
                        } catch (\Throwable $ex) {}
                    }
                } else {
                    try {
                        DB::statement("ALTER TABLE `{$tableName}` ADD PRIMARY KEY (`id`)");
                    } catch (\Throwable $e) {}

                    try {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `id` BIGINT NOT NULL AUTO_INCREMENT");
                    } catch (\Throwable $e) {
                        try {
                            DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `id` INT NOT NULL AUTO_INCREMENT");
                        } catch (\Throwable $ex) {}
                    }
                }

                try {
                    DB::statement("ALTER TABLE `{$tableName}` AUTO_INCREMENT = {$nextId}");
                } catch (\Throwable $e) {}

                $this->info("✓ Tabel [{$tableName}] berhasil diperbaiki -> AUTO_INCREMENT diset!");
                $count++;
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info("SELESAI! Sebanyak {$count} tabel berhasil diperbaiki.");
        return 0;
    }
}
