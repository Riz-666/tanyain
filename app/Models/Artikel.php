<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;
    protected $table = 'artikel';
    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'isi',
        'status',
        'views'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repositori()
    {
        return $this->hasMany(Repositori::class);
    }

    public function viewArtikel()
    {
        return $this->hasMany(ViewArtikel::class);
    }
}
