<?php

namespace App\Models\Booking;

use App\Models\Client\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id',
        'service',
        'date',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'date'      => 'datetime:Y-m-d',
        'start_at'  => 'datetime:H:i',
        'end_at'    => 'datetime:H:i',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
}
