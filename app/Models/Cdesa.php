<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cdesa extends Model
{
    protected $table = 'cdesa';
    protected $guarded = [];

    public function penduduk(): BelongsToMany
    {
        return $this->belongsToMany(Penduduk::class, 'cdesa_penduduk', 'id_cdesa', 'id_pend')
            ->withoutGlobalScopes()
            ->withPivot('config_id');
    }

    public function mutasi(): HasMany
    {
        return $this->hasMany(MutasiCdesa::class, 'id_cdesa_masuk');
    }
}
