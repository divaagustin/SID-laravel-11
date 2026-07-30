<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukDesa extends Model
{
    protected $table = 'produk';
    protected $guarded = [];

    public function pelapak(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_pelapak', 'id')->withoutGlobalScopes();
    }
}
