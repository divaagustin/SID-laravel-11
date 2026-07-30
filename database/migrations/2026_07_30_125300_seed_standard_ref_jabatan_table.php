<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $jabatans = [
            'Kepala Desa',
            'Sekretaris Desa',
            'Kepala Urusan Keuangan (Bendahara Desa)',
            'Kepala Urusan Umum & Perencanaan',
            'Kepala Urusan Tata Usaha & Umum',
            'Kepala Urusan Perencanaan',
            'Kepala Seksi Pemerintahan',
            'Kepala Seksi Kesejahteraan',
            'Kepala Seksi Pelayanan',
            'Kepala Dusun / Kepala Kewilayahan',
            'Staf Administrasi Desa / Operator',
            'Ketua BPD (Badan Permusyawaratan Desa)',
            'Wakil Ketua BPD',
            'Sekretaris BPD',
            'Anggota BPD',
            'Ketua LPM Desa',
            'Ketua TP-PKK Desa',
            'Ketua Karang Taruna Desa',
            'Ketua RW',
            'Ketua RT',
        ];

        foreach ($jabatans as $index => $nama) {
            DB::table('ref_jabatan')->updateOrInsert(
                ['nama' => $nama],
                [
                    'config_id'  => 1,
                    'jenis'      => $index < 2 ? ($index + 1) : 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        // No destruct needed
    }
};
