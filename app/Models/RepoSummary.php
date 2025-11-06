<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepoSummary extends Model
{
    use HasFactory;

    protected $table = 'repo_summary';
    protected $fillable = [
        'year',
        'month',
        'total_repo',
    ];
}
