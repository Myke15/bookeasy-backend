<?php

namespace App\Listeners\Booking;

use App\Events\Booking\BookingCreated;
use App\Notifications\BookingConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingConfirmationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        $event->booking->client->notify(new BookingConfirmed($event->booking));
    }
}