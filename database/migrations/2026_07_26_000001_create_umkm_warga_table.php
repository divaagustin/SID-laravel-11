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
        Schema::create('umkm_warga', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('config_id')->default(1);
            $table->integer('penduduk_id')->nullable()->index();
            $table->string('nik_pemilik', 20)->index();
            $table->string('nama_usaha', 150);
            $table->string('kategori_usaha', 50)->default('Kuliner'); // Kuliner, Sembako, Elektronik/Konter, Pertanian, Pabrik/Manufaktur, Jasa_Tetap, Lainnya
            $table->text('deskripsi_produk')->nullable();
            $table->string('foto_usaha')->nullable();
            $table->string('jam_operasional', 100)->default('08.00 - 17.00 WIB');
            $table->string('no_whatsapp', 25);
            $table->text('alamat_usaha')->nullable();
            $table->string('status_operasional', 20)->default('buka'); // buka, tutup
            $table->string('status_moderasi', 20)->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm_warga');
    }
};
