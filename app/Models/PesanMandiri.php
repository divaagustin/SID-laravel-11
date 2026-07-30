<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Model PesanMandiri — Pengaduan & Aspirasi Warga (Lapor Desa)
 *
 * Memetakan ke tabel pesan_mandiri
 */
class PesanMandiri extends Model
{
    use HasFactory;

    protected $table = 'pesan_mandiri';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'config_id',
        'owner',
        'penduduk_id',
        'subjek',
        'komentar',
        'status',
        'tipe',
        'permohonan',
        'is_archived',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->config_id)) {
                $model->config_id = 1;
            }
        });
    }

    public function getStatusLabelAttribute(): string
    {
        return match ((int) $this->status) {
            1 => '📩 Baru / Belum Dibaca',
            2 => '💬 Sudah Dibalas',
            3 => '✅ Selesai / Ditindaklanjuti',
            default => 'Pending',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ((int) $this->status) {
            1 => 'warning',
            2 => 'info',
            3 => 'success',
            default => 'gray',
        };
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id', 'id')->withoutGlobalScopes();
    }
}
