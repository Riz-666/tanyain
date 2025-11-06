<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $fillable = [
        'file_repo_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    public function file()
    {
        return $this->belongsTo(FileRepo::class, 'file_repo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
