<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->longText('sejarah_desa')->nullable()->after('jabatan_kontak');
            $table->text('visi')->nullable()->after('sejarah_desa');
            $table->longText('misi')->nullable()->after('visi');
        });
    }

    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn(['sejarah_desa', 'visi', 'misi']);
        });
    }
};
