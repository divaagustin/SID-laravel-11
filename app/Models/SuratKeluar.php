<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    protected $primaryKey = 'id';
    // surat_keluar memiliki created_at & updated_at
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'config_id',
        'nomor_urut',
        'nomor_surat',
        'kode_surat',
        'tanggal_surat',
        'tanggal_catat',
        'tujuan',
        'isi_singkat',
        'berkas_scan',
        'ekspedisi',
        'tanggal_pengiriman',
        'tanda_terima',
        'keterangan',
        'lokasi_arsip',
        'arsip_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_surat'       => 'date',
        'tanggal_catat'       => 'datetime',
        'tanggal_pengiriman'  => 'date',
        'ekspedisi'           => 'boolean',
    ];
}
