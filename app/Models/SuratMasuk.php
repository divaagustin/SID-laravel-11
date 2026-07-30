<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    protected $primaryKey = 'id';
    public $timestamps = false; // tabel tidak memiliki kolom timestamps standar Laravel

    protected $fillable = [
        'config_id',
        'nomor_urut',
        'tanggal_penerimaan',
        'nomor_surat',
        'kode_surat',
        'tanggal_surat',
        'pengirim',
        'isi_singkat',
        'isi_disposisi',
        'berkas_scan',
        'lokasi_arsip',
    ];

    protected $casts = [
        'tanggal_penerimaan' => 'date',
        'tanggal_surat'      => 'date',
    ];

    // Relasi ke disposisi
    public function disposisi(): HasMany
    {
        return $this->hasMany(DisposisiSuratMasuk::class, 'id_surat_masuk', 'id');
    }
}
