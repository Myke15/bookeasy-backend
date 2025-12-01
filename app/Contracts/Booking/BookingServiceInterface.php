<?php

namespace App\Contracts\Booking;

use App\Models\Booking\Booking;
use Illuminate\Support\Collection as SupportCollection;

interface BookingServiceInterface
{
    /**
     * Create a new booking.
     *
     * @param array $data
     * @return Booking
     */
    public function createBooking(array $data): Booking;

    /**
     * Get booking slots for the date
     *
     * @param string $date
     * @return array
     */
    public function getSlots(string $date): array;

    /**
     * Get date wise booking counts.
     * @return SupportCollection
     */
    public function getDateWiseBookingCounts(): SupportCollection;
}
