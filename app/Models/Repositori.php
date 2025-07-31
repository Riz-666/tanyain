<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repositori extends Model
{
    protected $table = 'repositori';
    protected $fillable = [
        'artikel_id',
        'nama_file',
        'tipe_file'
    ];

    public function artikel(): BelongsTo
    {
        return $this->belongsTo(Artikel::class);
    }
}
