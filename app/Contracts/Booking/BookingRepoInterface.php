<?php

namespace App\Contracts\Booking;

use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Collection;

interface BookingRepoInterface
{
    /**
     * Create a new booking.
     *
     * @param array $data
     * @return Booking
     */
    public function create(array $data): Booking;

    /**
     * Check if a time slot is available.
     *
     * @param string $date
     * @param string $startAt
     * @return bool
     */
    public function isSlotAvailable(string $date, string $startAt): bool;

    /**
     * Get booked slots for a specific date.
     *
     * @param string $date
     * @return Collection
     */
    public function getBookedSlotsForDate(string $date): Collection;

    /**
     * Get all bookings.
     * @return Collection
     */
    public function getAll(): Collection;
}
