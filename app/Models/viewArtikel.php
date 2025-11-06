<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewArtikel extends Model
{
    use HasFactory;
    protected $table = 'view_artikel';
    public $timestamps = false;

    protected $fillable = [
        'artikel_id', 'user_id','user_agent' ,'ip_address'
    ];

    public function artikel()
    {
        return $this->belongsTo(Artikel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
