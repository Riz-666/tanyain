<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saran extends Model
{
    protected $table = 'saran';
    protected $fillable = [
        'user_id',
        'nama',
        'ip_address',
        'pesan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
