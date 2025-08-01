<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repositori extends Model
{

    protected $table = 'repositori';
    protected $fillable = [
        'user_id',
        'judul_repo',
        'deskripsi',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileRepo()
    {
        return $this->hasMany(FileRepo::class);
    }

    public function artikel()
    {
        return $this->hasMany(Artikel::class);
    }
}
