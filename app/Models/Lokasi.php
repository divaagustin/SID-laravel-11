<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Lokasi — Penanda Lokasi / Fasilitas GIS Desa
 *
 * Memetakan ke tabel lokasi
 */
class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';
    public $timestamps = false;

    protected $fillable = [
        'config_id',
        'desk',
        'nama',
        'enabled',
        'lat',
        'lng',
        'ref_point',
        'foto',
        'id_cluster',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (empty($model->config_id)) {
                $model->config_id = 1;
            }
        });
    }

    /**
     * Relasi ke kategori point GIS
     */
    public function kategoriPoint(): BelongsTo
    {
        return $this->belongsTo(GisPoint::class, 'ref_point', 'id');
    }

    /**
     * URL Foto Lokasi
     */
    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto) {
            if (str_starts_with($this->foto, 'http')) {
                return $this->foto;
            }
            return asset('storage/' . $this->foto);
        }

        return null;
    }
}
