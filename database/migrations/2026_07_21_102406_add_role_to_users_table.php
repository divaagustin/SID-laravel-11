<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom role ke tabel users untuk RBAC OpenSID.
 *
 * Role dipetakan dari user_grup OpenSID:
 *   - administrator : Full access
 *   - operator      : Buat & cetak surat, entry data kependudukan
 *   - sekretaris_desa : Review/paraf surat (verifikasi_sekdes = 1)
 *   - kepala_desa    : Tanda tangan final & TTE BSrE
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'administrator',
                'operator',
                'sekretaris_desa',
                'kepala_desa',
            ])->default('operator')->after('email');

            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
