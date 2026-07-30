<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model DokumenPublik — Dokumen Transparansi Publik & Peraturan Desa
 *
 * Memetakan ke tabel dokumen
 */
class DokumenPublik extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'config_id',
        'satuan',
        'nama',
        'enabled',
        'tgl_upload',
        'id_pend',
        'kategori',
        'attr',
        'tipe',
        'url',
        'tahun',
        'kategori_info_publik',
        'deleted',
        'id_syarat',
        'id_parent',
        'created_by',
        'updated_by',
        'dok_warga',
        'lokasi_arsip',
        'published_at',
        'keterangan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'enabled'    => 'boolean',
            'tgl_upload' => 'datetime',
            'tahun'      => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (empty($model->config_id)) {
                $model->config_id = 1;
            }
            if (empty($model->attr)) {
                $model->attr = json_encode([]);
            }
        });
    }

    /**
     * Label Kategori Informasi Publik
     */
    public function getKategoriLabelAttribute(): string
    {
        return match ((int) $this->kategori_info_publik) {
            1       => 'Informasi Berkala',
            2       => 'Informasi Serta-Merta',
            3       => 'Informasi Setiap Saat',
            default => 'Dokumen Umum / Perdes',
        };
    }

    /**
     * URL Berkas Dokumen untuk Diunduh
     */
    public function getDownloadUrlAttribute(): ?string
    {
        if ($this->satuan) {
            if (str_starts_with($this->satuan, 'http')) {
                return $this->satuan;
            }
            return asset('storage/' . $this->satuan);
        }

        if ($this->url) {
            return $this->url;
        }

        return null;
    }
}
