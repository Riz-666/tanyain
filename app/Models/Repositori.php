<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repositori extends Model
{
    use SoftDeletes, HasFactory;
    protected $table = 'repositori';
    protected $dates = ['deleted_at', 'deleted_until'];
    protected $fillable = [
        'user_id',
        'judul_repo',
        'deskripsi',
        'status',
        'deleted_until'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userTrash()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function fileRepo()
    {
        return $this->hasMany(FileRepo::class, 'repositori_id');
    }

    public function artikel()
    {
        return $this->hasMany(Artikel::class);
    }
    public function artikelTrash()
    {
        return $this->hasMany(Artikel::class)->withTrashed();
    }
    public function fileCsv()
    {
        return $this->hasMany(FileRepo::class, 'repositori_id')->where('ekstensi', 'csv');
    }

}
