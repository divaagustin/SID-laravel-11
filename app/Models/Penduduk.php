<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Penduduk extends Model
{
    protected $table = 'tweb_penduduk';

    public $timestamps = false;

    protected $fillable = [
        'config_id', 'nama', 'nik', 'id_kk', 'kk_level', 'sex',
        'tempatlahir', 'tanggallahir', 'agama_id', 'pendidikan_kk_id',
        'pekerjaan_id', 'status_kawin', 'warganegara_id', 'foto',
        'golongan_darah_id', 'id_cluster', 'status', 'alamat_sekarang',
        'status_dasar', 'telepon', 'email', 'ktp_el', 'nama_ayah', 'nama_ibu',
        'ayah_nik', 'ibu_nik', 'hubung_warga', 'suku',
    ];

    protected $casts = [
        'tanggallahir' => 'date',
    ];

    /**
     * Hitung Usia Penduduk dalam Tahun secara Otomatis
     */
    public function getUsiaAttribute(): ?int
    {
        if (! $this->tanggallahir || $this->tanggallahir->format('Y-m-d') === '0000-00-00') {
            return null;
        }

        return (int) $this->tanggallahir->age;
    }

    public function getUsiaFormattedAttribute(): string
    {
        $age = $this->usia;

        return $age !== null ? "{$age} Thn" : '-';
    }

    public function getNamaNikAttribute(): string
    {
        return "{$this->nama} (NIK: {$this->nik})";
    }

    public function getJenisWargaAttribute(): string
    {
        if ((int) $this->warganegara_id === 2) {
            return 'WNA (Warga Negara Asing)';
        }

        if ((int) $this->status === 2) {
            return 'Warga Luar Desa (Tidak Tetap)';
        }

        return 'Warga Tetap (WNI Desa)';
    }

    // Hanya tampilkan penduduk yang masih hidup (status_dasar = 1)
    protected static function booted(): void
    {
        static::addGlobalScope('hidup', function (Builder $builder) {
            $builder->where('status_dasar', 1);
        });

        static::updated(function (Penduduk $penduduk) {
            if ($penduduk->isDirty('status_dasar') && (int) $penduduk->status_dasar !== 1) {
                PendudukMandiri::where('id_pend', $penduduk->id)->update(['aktif' => 0]);
            }
        });

        static::deleting(function (Penduduk $penduduk) {
            PendudukMandiri::where('id_pend', $penduduk->id)->delete();
            UmkmWarga::where('penduduk_id', $penduduk->id)->delete();
            JasaWarga::where('pembuat_id', $penduduk->id)->orWhere('pekerja_id', $penduduk->id)->delete();
        });
    }

    public function keluarga(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Keluarga::class, 'id_kk');
    }

    public function wilayah(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_cluster');
    }

    public function bantuanSocial(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PesertaBantuan::class, 'kartu_id_pend');
    }

    /**
     * Scope Daftar Pemilih Tetap (DPT): Warga berusia >= 17 tahun ATAU sudah pernah kawin.
     */
    public function scopeDpt(Builder $query, ?string $tanggalPemilihan = null): Builder
    {
        $targetDate = $tanggalPemilihan ? \Carbon\Carbon::parse($tanggalPemilihan) : now();
        $cutoffBirthDate = $targetDate->copy()->subYears(17)->format('Y-m-d');

        return $query->where(function ($q) use ($cutoffBirthDate) {
            $q->where(function ($sub) use ($cutoffBirthDate) {
                $sub->whereNotNull('tanggallahir')
                    ->where('tanggallahir', '!=', '0000-00-00')
                    ->where('tanggallahir', '<=', $cutoffBirthDate);
            })->orWhere(function ($sub) {
                $sub->whereNotNull('status_kawin')
                    ->whereIn('status_kawin', [2, 3, 4]); // 2: Kawin, 3: Cerai Hidup, 4: Cerai Mati
            });
        });
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return match ((int) $this->sex) {
            1 => 'Laki-laki',
            2 => 'Perempuan',
            default => 'Tidak Diketahui',
        };
    }

    public function getUmurAttribute(): int
    {
        return $this->tanggallahir ? $this->tanggallahir->age : 0;
    }
}
