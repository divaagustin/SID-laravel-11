<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $dbName = DB::getDatabaseName();
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . $dbName;

        foreach ($tables as $table) {
            if (! isset($table->{$key})) {
                continue;
            }

            $tableName = $table->{$key};

            if (! Schema::hasColumn($tableName, 'id')) {
                continue;
            }

            $columnInfo = DB::select("SHOW COLUMNS FROM `{$tableName}` LIKE 'id'");
            if (empty($columnInfo)) {
                continue;
            }

            $col = $columnInfo[0];
            if (strpos($col->Extra, 'auto_increment') === false) {
                try {
                    $maxId = (int) (DB::table($tableName)->max('id') ?? 0);
                    $nextId = max($maxId + 1, 1);

                    if ($col->Key === 'PRI') {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
                    } else {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)");
                    }

                    DB::statement("ALTER TABLE `{$tableName}` AUTO_INCREMENT = {$nextId}");
                } catch (\Throwable $e) {
                    try {
                        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `id` INT NOT NULL AUTO_INCREMENT");
                    } catch (\Throwable $ex) {
                        // Ignore legacy views or special tables
                    }
                }
            }
        }
    }

    public function down(): void
    {
        // No down needed
    }
};
