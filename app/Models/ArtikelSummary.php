<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtikelSummary extends Model
{
    use HasFactory;

    protected $table = 'artikel_summary';
    protected $fillable = [
        'year',
        'month',
        'total_artikel',
    ];
}
