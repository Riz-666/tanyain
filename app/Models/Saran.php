<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saran extends Model
{
    protected $fillable = [
        'user_id',
        'pesan'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
