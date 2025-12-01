<?php

namespace App\Repositories\Booking;

use App\Contracts\Booking\BookingRepoInterface;
use App\Models\Booking\Booking;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository implements BookingRepoInterface
{
    /**
     * Create a new booking.
     *
     * @param array $data
     * @return Booking
     */
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    /**
     * Check if a time slot is available.
     *
     * @param string $date
     * @param string $startAt
     * @return bool
     */
    public function isSlotAvailable(string $date, string $startAt): bool
    {
        return Booking::where('date', $date)->where('start_at', $startAt)->doesntExist();
    }

    /**
     * Get booked slots for a specific date.
     *
     * @param string $date
     * @return Collection
     */
    public function getBookedSlotsForDate(string $date): Collection
    {
        return Booking::where('date', $date)
            ->select('start_at')
            ->get();
    }

    /**
     * Get all bookings.
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Booking::select('client_id', 'date', 'start_at', 'end_at')->with(['client:id,name,email'])->get();
    }
}
