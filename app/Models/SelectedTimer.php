<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedTimer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'timer_id',
        'start_time',
        'passed_time',
        'status',
    ];
}
