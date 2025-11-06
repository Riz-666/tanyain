<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarTag extends Model
{
    protected $table = 'komentar_tag';
    protected $fillable = ['komentar_id', 'tagged_user_id'];

    public function komentar()
    {
        return $this->belongsTo(Komentar::class, 'komentar_id');
    }

    public function taggedUser()
    {
        return $this->belongsTo(User::class, 'tagged_user_id');
    }
}
