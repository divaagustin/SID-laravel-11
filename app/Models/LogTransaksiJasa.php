<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogTransaksiJasa extends Model
{
    use HasFactory;

    protected $table = 'log_transaksi_jasa';

    protected $fillable = [
        'jasa_id',
        'penduduk_id',
        'aksi',
        'keterangan',
    ];

    public function jasa(): BelongsTo
    {
        return $this->belongsTo(JasaWarga::class, 'jasa_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }
}
