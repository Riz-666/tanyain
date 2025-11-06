<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileRepo extends Model
{
    use SoftDeletes;
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
        return $this->belongsTo(Repositori::class, 'repositori_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function downloadLogs()
    {
        return $this->hasMany(DownloadLog::class, 'file_repo_id');
    }
    public function scopeCsv($query)
    {
        return $query->where('ekstensi', 'csv');
    }
}
