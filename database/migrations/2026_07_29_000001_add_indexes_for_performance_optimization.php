<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tweb_penduduk', function (Blueprint $table) {
            if (Schema::hasColumn('tweb_penduduk', 'status_dasar')) {
                $table->index('status_dasar', 'idx_penduduk_status_dasar');
            }
            if (Schema::hasColumn('tweb_penduduk', 'nik')) {
                $table->index('nik', 'idx_penduduk_nik');
            }
        });

        Schema::table('tweb_keluarga', function (Blueprint $table) {
            if (Schema::hasColumn('tweb_keluarga', 'no_kk')) {
                $table->index('no_kk', 'idx_keluarga_no_kk');
            }
        });

        Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
            if (Schema::hasColumn('tweb_penduduk_mandiri', 'id_pend')) {
                $table->index('id_pend', 'idx_mandiri_id_pend');
            }
            if (Schema::hasColumn('tweb_penduduk_mandiri', 'aktif')) {
                $table->index('aktif', 'idx_mandiri_aktif');
            }
        });

        Schema::table('artikel', function (Blueprint $table) {
            if (Schema::hasColumn('artikel', 'enabled')) {
                $table->index('enabled', 'idx_artikel_enabled');
            }
            if (Schema::hasColumn('artikel', 'slug')) {
                $table->index('slug', 'idx_artikel_slug');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tweb_penduduk', function (Blueprint $table) {
            $table->dropIndex('idx_penduduk_status_dasar');
            $table->dropIndex('idx_penduduk_nik');
        });

        Schema::table('tweb_keluarga', function (Blueprint $table) {
            $table->dropIndex('idx_keluarga_no_kk');
        });

        Schema::table('tweb_penduduk_mandiri', function (Blueprint $table) {
            $table->dropIndex('idx_mandiri_id_pend');
            $table->dropIndex('idx_mandiri_aktif');
        });

        Schema::table('artikel', function (Blueprint $table) {
            $table->dropIndex('idx_artikel_enabled');
            $table->dropIndex('idx_artikel_slug');
        });
    }
};
