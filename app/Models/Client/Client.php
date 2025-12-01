<?php

namespace App\Models\Client;

use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
