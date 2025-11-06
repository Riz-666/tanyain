<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorSummary extends Model
{
    use HasFactory;

    protected $table = 'visitor_summary';
    protected $fillable = [
        'year',
        'month',
        'total_visitors',
    ];
}
