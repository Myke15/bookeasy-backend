<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'start_time',
        'end_time',
        'duration'
    ];

    protected $casts = [
        'day'           => 'int',
        'start_time'    => 'datetime:H:i',
        'end_time'      => 'datetime:H:i',
    ];

}
