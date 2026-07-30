<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model PendudukMandiri — Akun Layanan Mandiri Warga Desa
 *
 * Memetakan ke tabel tweb_penduduk_mandiri
 */
class PendudukMandiri extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tweb_penduduk_mandiri';
    protected $primaryKey = 'id_pend';
    public $incrementing = false;
    public $timestamps = false; // Menggunakan updated_at bawaan MySQL

    protected $fillable = [
        'id_pend',
        'config_id',
        'pin',
        'last_login',
        'tanggal_buat',
        'aktif',
        'scan_ktp',
        'scan_kk',
        'foto_selfie',
        'ganti_pin',
        'remember_token',
    ];

    protected $hidden = [
        'pin',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'last_login'   => 'datetime',
            'tanggal_buat' => 'datetime',
            'ganti_pin'    => 'boolean',
            'aktif'        => 'boolean',
        ];
    }

    /**
     * Auth password method override to use `pin` column.
     */
    public function getAuthPasswordName(): string
    {
        return 'pin';
    }

    public function getAuthPassword(): string
    {
        return $this->pin;
    }

    /**
     * Relasi ke data penduduk utama
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_pend', 'id')->withoutGlobalScopes();
    }
}
