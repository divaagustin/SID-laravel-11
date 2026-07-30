<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Galeri — Photo Gallery Kegiatan Desa
 *
 * Memetakan ke tabel gambar_gallery
 */
class Galeri extends Model
{
    use HasFactory;

    protected $table = 'gambar_gallery';
    public $timestamps = false;

    protected $fillable = [
        'config_id',
        'parrent',
        'gambar',
        'nama',
        'enabled',
        'tgl_upload',
        'tipe',
        'slider',
        'urut',
        'jenis',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'slider'  => 'boolean',
            'tgl_upload' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (empty($model->config_id)) {
                $model->config_id = 1;
            }
            if (empty($model->tgl_upload)) {
                $model->tgl_upload = now();
            }
        });
    }

    /**
     * URL Gambar Photo
     */
    public function getGambarUrlAttribute(): string
    {
        return get_media_url($this->gambar, 'galeri');
    }
}
