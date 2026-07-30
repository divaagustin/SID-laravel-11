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
        Schema::create('log_transaksi_jasa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jasa_id');
            $table->integer('penduduk_id')->nullable()->index();
            $table->string('aksi', 50); // post_created, approved, job_taken, completed, cancelled
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('jasa_id')->references('id')->on('jasa_warga')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_transaksi_jasa');
    }
};
