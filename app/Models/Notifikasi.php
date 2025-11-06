<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $fillable = ['user_id', 'tipe', 'referensi_id', 'pesan', 'status', 'pengirim_id'];
    protected $casts = [
        'tipe' => 'string',
        'status' => 'string',
    ];

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'pengirim_id');
    }

    public function artikel()
    {
        return $this->belongsTo(\App\Models\Artikel::class, 'referensi_id');
    }

    public function komentar()
    {
        return $this->belongsTo(\App\Models\Komentar::class, 'referensi_id');
    }

    public function komentarTag()
    {
        return $this->belongsTo(\App\Models\KomentarTag::class, 'referensi_id');
    }
}
