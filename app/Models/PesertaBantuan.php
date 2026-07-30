<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesertaBantuan extends Model
{
    protected $table = 'program_peserta';
    protected $guarded = [];

    protected $casts = [
        'kartu_tanggal_lahir' => 'date',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramBantuan::class, 'program_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kartu_id_pend', 'id')->withoutGlobalScopes();
    }
}
