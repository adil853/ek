<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $table = 'times';

    protected $fillable = [
        'start_time',
        'end_time',
        'time_expressions',
    ];

    protected $casts = [
        'time_expressions' => 'json',
    ];
}
