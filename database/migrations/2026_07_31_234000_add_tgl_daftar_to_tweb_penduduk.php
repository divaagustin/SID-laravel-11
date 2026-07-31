<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tweb_penduduk')) {
            Schema::table('tweb_penduduk', function (Blueprint $table) {
                if (! Schema::hasColumn('tweb_penduduk', 'tgl_daftar')) {
                    $table->dateTime('tgl_daftar')->nullable()->useCurrent();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tweb_penduduk')) {
            Schema::table('tweb_penduduk', function (Blueprint $table) {
                if (Schema::hasColumn('tweb_penduduk', 'tgl_daftar')) {
                    $table->dropColumn('tgl_daftar');
                }
            });
        }
    }
};
