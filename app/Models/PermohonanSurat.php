<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PermohonanSurat — Pengajuan Surat Mandiri Online oleh Warga
 *
 * Memetakan ke tabel permohonan_surat
 */
class PermohonanSurat extends Model
{
    use HasFactory;

    protected $table = 'permohonan_surat';
    protected $primaryKey = 'id';

    protected $fillable = [
        'config_id',
        'id_pemohon',
        'id_surat',
        'isian_form',
        'status',
        'alasan',
        'keterangan',
        'no_hp_aktif',
        'syarat',
        'no_antrian',
    ];

    protected function casts(): array
    {
        return [
            'isian_form' => 'array',
            'syarat'     => 'array',
            'status'     => 'integer',
        ];
    }

    /**
     * Label status permohonan surat
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            0 => '⏳ Menunggu Verifikasi',
            1 => '📝 Diproses Operator',
            2 => '❌ Ditolak / Perlu Revisi',
            3 => '✅ Selesai (Siap Diunduh)',
            default => 'Pending',
        };
    }

    /**
     * Warna badge status permohonan
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            0 => 'bg-amber-100 text-amber-800 border-amber-300',
            1 => 'bg-blue-100 text-blue-800 border-blue-300',
            2 => 'bg-red-100 text-red-800 border-red-300',
            3 => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    /**
     * Relasi ke warga pemohon
     */
    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_pemohon', 'id')->withoutGlobalScopes();
    }

    /**
     * Relasi ke format surat (Dukungan alias suratFormat dan formatSurat)
     */
    public function suratFormat(): BelongsTo
    {
        return $this->belongsTo(SuratFormat::class, 'id_surat', 'id');
    }

    public function formatSurat(): BelongsTo
    {
        return $this->belongsTo(SuratFormat::class, 'id_surat', 'id');
    }
}
