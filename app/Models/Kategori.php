<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Kategori — Kategori Artikel / Berita Web Desa
 *
 * Memetakan ke tabel kategori
 */
class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    public $timestamps = false;

    protected $fillable = [
        'config_id',
        'kategori',
        'tipe',
        'urut',
        'enabled',
        'parrent',
        'slug',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (empty($model->slug) && ! empty($model->kategori)) {
                $model->slug = Str::slug($model->kategori);
            }
            if (empty($model->config_id)) {
                $model->config_id = 1;
            }
        });
    }

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class, 'id_kategori', 'id');
    }
}
