<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pamong extends Model
{
    protected $table = 'tweb_desa_pamong';

    protected $primaryKey = 'pamong_id';

    public $timestamps = false;

    protected $fillable = [
        'config_id', 'pamong_nama', 'pamong_nip', 'pamong_nik',
        'pamong_status', 'jabatan_id',
    ];

    public function getNamaLengkapAttribute(): string
    {
        return $this->pamong_nama . ($this->pamong_nip ? ' (NIP: ' . $this->pamong_nip . ')' : '');
    }
}
