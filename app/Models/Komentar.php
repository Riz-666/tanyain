<?php

namespace App\Models;

use App\Models\Artikel;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $fillable = ['artikel_id', 'user_id', 'parent_id', 'isi'];

    // Relasi ke artikel
    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'artikel_id');
    }

    // Relasi ke user yang bikin komentar
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke komentar induk (jika ini balasan)
    public function parent()
    {
        return $this->belongsTo(Komentar::class, 'parent_id');
    }

    // Relasi ke balasan (komentar anak)
    public function children()
    {
        return $this->hasMany(Komentar::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    // Relasi ke vote
    public function votes()
    { 
        return $this->hasMany(KomentarVote::class, 'komentar_id');
    }

    // Relasi ke file
    public function files()
    {
        return $this->hasMany(KomentarFile::class, 'komentar_id');
    }

    // Relasi ke tag
    public function tags()
    {
        return $this->hasMany(KomentarTag::class, 'komentar_id');
    }
    public function allReplies()
    {
        return $this->hasMany(Komentar::class, 'parent_id')
                    ->with('allReplies')
                    ->orderBy('created_at', 'asc');
    }

}
