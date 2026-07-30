<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelompok extends Model
{
    protected $table = 'kelompok';
    public $timestamps = false;
    protected $guarded = [];

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_ketua', 'id')->withoutGlobalScopes();
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(Penduduk::class, 'kelompok_anggota', 'id_kelompok', 'id_penduduk')
            ->withoutGlobalScopes()
            ->withPivot(['jabatan', 'no_anggota', 'foto', 'periode', 'keterangan'])
            ->orderByPivot('no_anggota');
    }

    public function anggotaRecords(): HasMany
    {
        return $this->hasMany(KelompokAnggota::class, 'id_kelompok', 'id')
            ->orderBy('no_anggota');
    }
}
