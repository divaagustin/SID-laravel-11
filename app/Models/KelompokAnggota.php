<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelompokAnggota extends Model
{
    protected $table = 'kelompok_anggota';
    public $timestamps = false;
    protected $guarded = [];

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_penduduk', 'id')->withoutGlobalScopes();
    }
}
