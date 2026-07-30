<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tweb_desa_pamong', function (Blueprint $table) {
            $table->tinyInteger('tampilkan_beranda')->default(1)->after('pamong_status');
            $table->tinyInteger('tampilkan_struktur')->default(1)->after('tampilkan_beranda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tweb_desa_pamong', function (Blueprint $table) {
            $table->dropColumn(['tampilkan_beranda', 'tampilkan_struktur']);
        });
    }
};
