<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewArtikel extends Model
{
    protected $table = 'view_artikel';
    protected $fillable = [
        'artikel_id',
        'user_id',
        'ip_address',
        'waktu'
    ];

    public function artikel()
    {
        return $this->belongsTo(Artikel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
