<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisposisiSuratMasuk extends Model
{
    protected $table = 'disposisi_surat_masuk';
    protected $primaryKey = 'id_disposisi';
    public $timestamps = false;

    protected $fillable = [
        'config_id',
        'id_surat_masuk',
        'id_desa_pamong',
        'disposisi_ke',
    ];

    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'id_surat_masuk', 'id');
    }

    public function pamong(): BelongsTo
    {
        return $this->belongsTo(Pamong::class, 'id_desa_pamong', 'pamong_id');
    }
}
