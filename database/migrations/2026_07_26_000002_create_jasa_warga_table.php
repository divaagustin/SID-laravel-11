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
        Schema::create('jasa_warga', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('config_id')->default(1);
            $table->integer('pembuat_id')->nullable()->index();
            $table->integer('pekerja_id')->nullable()->index();
            $table->string('nik_pembuat', 20)->index();
            $table->string('nik_pekerja', 20)->nullable()->index();
            $table->string('judul_pekerjaan', 150);
            $table->string('kategori', 50)->default('Kebersihan'); // Kebersihan, Pertukangan, Anter_Jemput, Pertanian, Akademik_Tugas, Lainnya
            $table->text('deskripsi_tugas');
            $table->decimal('fee_insentif', 12, 2)->default(0);
            $table->dateTime('tenggat_waktu')->nullable();
            $table->string('lokasi_dusun_rt', 100);
            $table->text('alamat_detail')->nullable(); // Privat, hanya untuk pekerja yang mengklaim job
            $table->string('status_job', 20)->default('open'); // open, in_progress, completed, cancelled
            $table->string('status_moderasi', 20)->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jasa_warga');
    }
};
