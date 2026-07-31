<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (Schema::hasTable('ref_syarat_surat')) {
            try {
                DB::statement("ALTER TABLE `ref_syarat_surat` MODIFY COLUMN `config_id` INT NULL DEFAULT 1");
            } catch (\Throwable $e) {}
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // No down needed
    }
};
