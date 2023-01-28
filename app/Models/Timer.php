<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'memo',
        'set_seconds',
        'total_seconds',
        'used_seconds',
        'status',
    ];
}
