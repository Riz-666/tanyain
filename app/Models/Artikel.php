<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artikel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'artikel';
    protected $dates = ['deleted_at', 'deleted_until'];
    protected $fillable = ['user_id', 'repositori_id', 'judul', 'slug', 'isi', 'file', 'status', 'cover', 'views', 'deleted_until'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userTrash()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function repositori()
    {
        return $this->belongsTo(Repositori::class, 'repositori_id');
    }

    public function viewArtikel()
    {
        return $this->hasMany(viewArtikel::class);
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class, 'artikel_tag', 'artikel_id', 'tag_id');
    }

    public function repositoriSoftDelete()
    {
        return $this->belongsTo(Repositori::class, 'repositori_id')->withTrashed();
    }
    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'artikel_id')->whereNull('parent_id');
    }

}
