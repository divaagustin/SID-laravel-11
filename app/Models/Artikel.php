<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Model Artikel — Berita, Pengumuman & Konten Web Desa
 *
 * Memetakan ke tabel artikel
 */
class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel';
    public $timestamps = false; // Menggunakan tgl_upload bawaan MySQL

    protected $fillable = [
        'config_id',
        'gambar',
        'isi',
        'enabled',
        'tgl_upload',
        'id_kategori',
        'id_user',
        'judul',
        'headline',
        'gambar1',
        'gambar2',
        'gambar3',
        'dokumen',
        'link_dokumen',
        'boleh_komentar',
        'slug',
        'hit',
        'tampilan',
        'slider',
        'tipe',
        'urut',
        'jenis_widget',
    ];

    protected function casts(): array
    {
        return [
            'tgl_upload'     => 'datetime',
            'enabled'        => 'boolean',
            'headline'       => 'boolean',
            'slider'         => 'boolean',
            'boleh_komentar' => 'boolean',
            'hit'            => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (empty($model->slug) && ! empty($model->judul)) {
                $model->slug = Str::slug($model->judul);
            }
            if (empty($model->config_id)) {
                $model->config_id = 1;
            }
            if (empty($model->tgl_upload)) {
                $model->tgl_upload = now();
            }
        });
    }

    /**
     * Relasi ke kategori artikel
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id');
    }

    /**
     * Relasi ke user penulis
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * URL Gambar Thumbnail
     */
    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar) {
            if (str_starts_with($this->gambar, 'http')) {
                return $this->gambar;
            }
            return asset('storage/' . $this->gambar);
        }

        return 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&q=80&w=800';
    }
}
