<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Persil extends Model
{
    protected $table = 'persil';
    public $timestamps = false;
    protected $guarded = [];

    public function cdesa(): BelongsTo
    {
        return $this->belongsTo(Cdesa::class, 'cdesa_awal');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }
}
