<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JasaWarga extends Model
{
    use HasFactory;

    protected $table = 'jasa_warga';

    protected $fillable = [
        'config_id',
        'pembuat_id',
        'pekerja_id',
        'nik_pembuat',
        'nik_pekerja',
        'judul_pekerjaan',
        'kategori',
        'deskripsi_tugas',
        'fee_insentif',
        'tenggat_waktu',
        'lokasi_dusun_rt',
        'alamat_detail',
        'status_job',
        'status_moderasi',
    ];

    protected function casts(): array
    {
        return [
            'fee_insentif' => 'decimal:2',
            'tenggat_waktu' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (empty($model->config_id)) {
                $model->config_id = 1;
            }
        });
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'pembuat_id');
    }

    public function pekerja(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'pekerja_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        switch ($this->status_job) {
            case 'open':
                return 'OPEN (Belum Di-take)';
            case 'in_progress':
                return 'IN PROGRESS (Sedang Dikerjakan)';
            case 'completed':
                return 'COMPLETED (Selesai)';
            case 'cancelled':
                return 'CANCELLED (Dibatalkan)';
            default:
                return ucfirst($this->status_job);
        }
    }
}
