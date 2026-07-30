<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    protected $table = 'tweb_keluarga';

    public $timestamps = false;

    protected $fillable = [
        'config_id', 'no_kk', 'nik_kepala', 'tgl_daftar',
        'kelas_sosial', 'alamat', 'id_cluster',
    ];

    protected $casts = [
        'tgl_daftar' => 'datetime',
        'tgl_cetak_kk' => 'datetime',
    ];

    public function wilayah(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_cluster');
    }

    public function anggota(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Penduduk::class, 'id_kk')->withoutGlobalScopes();
    }

    public function kepala(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'nik_kepala', 'nik')->withoutGlobalScopes();
    }
}
