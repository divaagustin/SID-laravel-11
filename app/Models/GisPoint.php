<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model GisPoint — Kategori / Tipe Penanda Lokasi GIS
 *
 * Memetakan ke tabel point
 */
class GisPoint extends Model
{
    use HasFactory;

    protected $table = 'point';
    public $timestamps = false;

    protected $fillable = [
        'config_id',
        'nama',
        'simbol',
        'tipe',
        'parrent',
        'enabled',
        'sumber',
    ];
}
