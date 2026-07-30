<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratFormat extends Model
{
    protected $table = 'tweb_surat_format';

    protected $fillable = [
        'config_id', 'nama', 'url_surat', 'kode_surat', 'lampiran',
        'kunci', 'favorit', 'jenis', 'mandiri', 'masa_berlaku',
        'satuan_masa_berlaku', 'qr_code', 'qr_code_tte', 'logo_garuda',
        'kecamatan', 'syarat_surat', 'template', 'template_desa',
        'form_isian', 'kode_isian', 'orientasi', 'ukuran', 'margin',
        'margin_global', 'footer', 'header', 'format_nomor',
        'format_nomor_global', 'sumber_penduduk_berulang',
    ];

    protected $casts = [
        'kunci' => 'boolean',
        'favorit' => 'boolean',
        'mandiri' => 'boolean',
        'qr_code' => 'boolean',
        'qr_code_tte' => 'boolean',
        'logo_garuda' => 'boolean',
    ];

    public $timestamps = true; // table has created_at and updated_at
}
