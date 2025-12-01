<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

class BookingLock extends Model
{
    //

    protected $fillable = [
        'date',
        'slot_start_at',
    ];
}
