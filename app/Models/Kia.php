<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kia extends Model
{
    protected $table = 'kia';
    protected $guarded = [];

    protected $casts = [
        'hari_perkiraan_lahir' => 'date',
    ];

    public function ibu(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'ibu_id', 'id')->withoutGlobalScopes();
    }

    public function anak(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'anak_id', 'id')->withoutGlobalScopes();
    }
}
