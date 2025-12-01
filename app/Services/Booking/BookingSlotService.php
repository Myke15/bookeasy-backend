<?php

namespace App\Services\Booking;

use App\Contracts\Booking\BookingSlotServiceInterface;
use Carbon\Carbon;

class BookingSlotService implements BookingSlotServiceInterface
{
    /**
     * Generate time slots between start and end time.
     *
     * @param string $startTime
     * @param string $endTime
     * @param int $intervalMinutes
     * @return array
     */
    public function generateSlots(string $startTime, string $endTime, int $intervalMinutes = 60): array
    {
        $slots = [];
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        while ($start->lessThan($end)) {
            $slotEnd = $start->copy()->addMinutes($intervalMinutes);
            
            // Don't create slot if it goes beyond end time
            if ($slotEnd->greaterThan($end)) {
                break;
            }

            $slots[] = $start->format('H:i');

            $start->addMinutes($intervalMinutes);
        }

        return $slots;
    }

    /**
     * Calculate total number of slots between start and end time.
     *
     * @param string $startTime
     * @param string $endTime
     * @param int $intervalMinutes
     * @return int
     */

    public function totalSlots(string $startTime, string $endTime, int $intervalMinutes = 60): int
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $totalMinutes = $start->diffInMinutes($end);
        return (int) floor($totalMinutes / $intervalMinutes);
    }
}
