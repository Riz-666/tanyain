<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'artikel_id',
        'judul',
        'tags',
        'content',
        'cover_image',
        'repository',
        'files',
        'expires_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'files' => 'array', // Pastikan ini ada untuk menyimpan JSON
        'expires_at' => 'datetime',
    ];
}
