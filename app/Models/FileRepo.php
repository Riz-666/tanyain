<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileRepo extends Model
{
    protected $table = 'file_repo';

    protected $fillable = [
        'repositori_id',
        'nama_file',
        'path',
        'ekstensi',
        'ukuran'
    ];

    public function repositori()
    {
        return $this->belongsTo(Repositori::class);
    }
}
