<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $dbName = DB::getDatabaseName();
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . $dbName;

        foreach ($tables as $table) {
            if (! isset($table->{$key})) {
                continue;
            }

            $tableName = $table->{$key};

            $pkCol = null;
            $primaryKeys = DB::select("SHOW KEYS FROM `{$tableName}` WHERE Key_name = 'PRIMARY'");
            
            if (! empty($primaryKeys)) {
                $pkCol = $primaryKeys[0]->Column_name;
            } elseif (Schema::hasColumn($tableName, 'id')) {
                $pkCol = 'id';
            } elseif (Schema::hasColumn($tableName, 'pamong_id')) {
                $pkCol = 'pamong_id';
            }

            if (! $pkCol) {
                continue;
            }

            try {
                $hasZero = DB::table($tableName)->where($pkCol, 0)->exists();
                if ($hasZero) {
                    $maxId = (int) (DB::table($tableName)->max($pkCol) ?? 0);
                    $newId = $maxId + 1;
                    DB::table($tableName)->where($pkCol, 0)->update([$pkCol => $newId]);
                }
            } catch (\Throwable $e) {}

            $columnInfo = DB::select("SHOW COLUMNS FROM `{$tableName}` LIKE '{$pkCol}'");
            if (empty($columnInfo)) {
                continue;
            }

            $col = $columnInfo[0];
            $isAuto = strpos($col->Extra, 'auto_increment') !== false;

            if (! $isAuto) {
                $maxId = (int) (DB::table($tableName)->max($pkCol) ?? 0);
                $nextId = max($maxId + 1, 1);

                if ($col->Key === 'PRI') {
                    try {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `{$pkCol}` BIGINT NOT NULL AUTO_INCREMENT");
                    } catch (\Throwable $e) {
                        try {
                            DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `{$pkCol}` INT NOT NULL AUTO_INCREMENT");
                        } catch (\Throwable $ex) {}
                    }
                } else {
                    try {
                        DB::statement("ALTER TABLE `{$tableName}` ADD PRIMARY KEY (`{$pkCol}`)");
                    } catch (\Throwable $e) {}

                    try {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `{$pkCol}` BIGINT NOT NULL AUTO_INCREMENT");
                    } catch (\Throwable $e) {
                        try {
                            DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `{$pkCol}` INT NOT NULL AUTO_INCREMENT");
                        } catch (\Throwable $ex) {}
                    }
                }

                try {
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
