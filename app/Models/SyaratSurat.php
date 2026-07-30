<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyaratSurat extends Model
{
    protected $table = 'ref_syarat_surat';

    protected $primaryKey = 'ref_syarat_id';

    public $timestamps = false;

    protected $fillable = [
        'config_id', 'ref_syarat_nama',
    ];
}
