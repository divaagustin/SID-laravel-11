<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmWarga extends Model
{
    use HasFactory;

    protected $table = 'umkm_warga';

    protected $fillable = [
        'config_id',
        'penduduk_id',
        'nik_pemilik',
        'nama_usaha',
        'kategori_usaha',
        'deskripsi_produk',
        'foto_usaha',
        'jam_operasional',
        'no_whatsapp',
        'alamat_usaha',
        'status_operasional',
        'status_moderasi',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (empty($model->config_id)) {
                $model->config_id = 1;
            }
        });
    }

    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    public function getFotoUrlAttribute(): string
    {
        return get_media_url($this->foto_usaha, 'galeri');
    }

    public function getWhatsappLinkAttribute(): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->no_whatsapp);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $text = rawurlencode("Halo Kak " . $this->nama_usaha . ", saya tertarik dengan produk/jasa Anda melalui Portal Desa " . ($this->nama_usaha ?? ''));
        return "https://wa.me/" . $cleanPhone . "?text=" . $text;
    }
}
