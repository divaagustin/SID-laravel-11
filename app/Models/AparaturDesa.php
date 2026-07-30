<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AparaturDesa extends Model
{
    protected $table = 'tweb_desa_pamong';
    protected $primaryKey = 'pamong_id';
    public $timestamps = false;
    protected $guarded = [];

    // Relasi ke tabel kependudukan (inti)
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend', 'id');
    }

    // Relasi ke tabel jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }

    // Relasi hierarki — atasan (parent)
    public function atasanRelasi()
    {
        return $this->belongsTo(AparaturDesa::class, 'atasan', 'pamong_id');
    }

    // Relasi hierarki — bawahan (children)
    public function bawahan()
    {
        return $this->hasMany(AparaturDesa::class, 'atasan', 'pamong_id')
            ->orderBy('urut');
    }

    // Accessor foto URL
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->pamong_nama) . '&background=1a5c2d&color=ffffff&size=120';
    }
}
