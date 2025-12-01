<?php

namespace App\Contracts\Booking;

interface BookingSlotServiceInterface
{
    /**
     * Generate time slots between start and end time.
     *
     * @param string $startTime
     * @param string $endTime
     * @param int $intervalMinutes
     * @return array
     */
    public function generateSlots(string $startTime, string $endTime, int $intervalMinutes = 60): array;

    /**
     * Calculate total number of slots between start and end time.
     *
     * @param string $startTime
     * @param string $endTime
     * @param int $intervalMinutes
     * @return int
     */
    public function totalSlots(string $startTime, string $endTime, int $intervalMinutes = 60): int;
}
