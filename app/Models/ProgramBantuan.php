<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramBantuan extends Model
{
    protected $table = 'program';
    protected $guarded = [];

    protected $casts = [
        'sdate' => 'date',
        'edate' => 'date',
    ];

    public function peserta(): HasMany
    {
        return $this->hasMany(PesertaBantuan::class, 'program_id');
    }

    public function getSasaranLabelAttribute(): string
    {
        return match ((int) $this->sasaran) {
            1 => 'Penduduk / Perorangan',
            2 => 'Keluarga (Kartu Keluarga)',
            3 => 'Rumah Tangga',
            4 => 'Kelompok / Lembaga Warga',
            default => 'Lainnya',
        };
    }
}
