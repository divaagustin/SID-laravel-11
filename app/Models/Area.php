<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Area — Area / Polygon Batas Wilayah GIS Desa
 *
 * Memetakan ke tabel area
 */
class Area extends Model
{
    use HasFactory;

    protected $table = 'area';
    public $timestamps = false;

    protected $fillable = [
        'config_id',
        'nama',
        'path',
        'enabled',
        'ref_polygon',
        'foto',
        'id_cluster',
        'desk',
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
}
