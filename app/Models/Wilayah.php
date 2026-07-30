<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'tweb_wil_clusterdesa';

    public $timestamps = false;

    protected $fillable = [
        'config_id', 'rt', 'rw', 'dusun', 'id_kepala', 'lat', 'lng',
    ];

    public function keluarga(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Keluarga::class, 'id_cluster');
    }

    public function penduduk(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Penduduk::class, 'id_cluster');
    }

    public function kepalaWilayah(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_kepala');
    }

    public function getNamaLengkapAttribute(): string
    {
        $parts = array_filter([
            $this->rt !== '0' ? 'RT ' . $this->rt : null,
            $this->rw !== '0' ? 'RW ' . $this->rw : null,
            $this->dusun !== '0' ? 'Dusun ' . $this->dusun : null,
        ]);
        return implode(' / ', $parts) ?: 'Wilayah ' . $this->id;
    }
}
