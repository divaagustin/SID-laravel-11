<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentitasDesa extends Model
{
    protected $table = 'config';
    protected $guarded = [];

    // Jika legacy `config` table tidak pakai created_at/updated_at default laravel, disable timestamps:
    // public $timestamps = false; 
    // Tapi karena tabel config punya kolom created_at dan updated_at, biarkan default true.
}
