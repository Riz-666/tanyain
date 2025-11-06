<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarVote extends Model
{
    protected $table = 'komentar_vote';
    protected $fillable = ['komentar_id', 'user_id', 'vote_type'];

    // Relasi ke komentar
    public function komentar()
    {
        return $this->belongsTo(Komentar::class, 'komentar_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
