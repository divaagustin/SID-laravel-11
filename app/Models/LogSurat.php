<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSurat extends Model
{
    protected $table = 'log_surat';

    public $timestamps = false; // table uses custom timestamp columns or created manually

    protected $fillable = [
        'config_id', 'id_format_surat', 'id_pend', 'id_pamong',
        'nama_pamong', 'nama_jabatan', 'id_user', 'tanggal',
        'bulan', 'tahun', 'no_surat', 'nama_surat', 'lampiran',
        'nik_non_warga', 'nama_non_warga', 'keterangan',
        'lokasi_arsip', 'urls_id', 'status', 'log_verifikasi',
        'tte', 'verifikasi_operator', 'verifikasi_kades',
        'verifikasi_sekdes', 'isi_surat', 'isi_surat_temp',
        'kecamatan', 'pemohon', 'input', 'lock',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'isi_surat' => 'array',
        'input' => 'array',
        'tte' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->config_id = 1;
            $model->id_user = auth()->id() ?? 1;
            $model->bulan = date('m');
            $model->tahun = date('Y');
            $model->kecamatan = 1;
            $model->status = 1; // 1 = aktif/selesai cetak
            
            // Simpan isian mentah ke kolom input untuk kompatibilitas OpenSID
            if ($model->isi_surat) {
                $model->input = $model->isi_surat;
            }
        });
    }

    public function penduduk(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_pend')->withoutGlobalScopes();
    }

    public function formatSurat(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SuratFormat::class, 'id_format_surat');
    }
}
