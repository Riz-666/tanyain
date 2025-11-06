<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtikelTag extends Model
{
    protected $table = 'artikel_tag';

    protected $fillable = [
        'artikel_id',
        'tag_id'
    ];
}
