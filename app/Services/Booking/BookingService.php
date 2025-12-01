<?php

namespace App\Services\Booking;

use App\Contracts\Booking\BookingServiceInterface;
use App\Contracts\Booking\BookingRepoInterface;
use App\Contracts\Booking\BookingSlotServiceInterface;
use App\Contracts\Client\ClientRepoInterface;
use App\Contracts\Service\WorkingHourRepoInterface;
use App\Events\Booking\BookingCreated;
use App\Models\Booking\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Collection as SupportCollection;

class BookingService implements BookingServiceInterface
{
    /**
     * BookingService constructor.
     *
     * @param BookingRepoInterface $bookingRepository
     */
    public function __construct(
        protected BookingRepoInterface $bookingRepo,
        protected WorkingHourRepoInterface $workingRepo,
        protected ClientRepoInterface $clientRepo,
        protected BookingSlotServiceInterface $slotService,

    ) { }

    /**
     * Create a new booking with validation and locking mechanism.
     *
     * @param array $data
     * @return Booking
     * @throws Exception
     */
    public function createBooking(array $data): Booking
    {
        // Check booking day is working day or not
        $date = Carbon::parse($data['date']);
        $workingHours = $this->workingRepo->findByDay($date->dayOfWeek());
        if (!$workingHours) {
            throw new Exception('No working hours found for the selected date.');
        }

        // Check booking date cannot be in the past
        if ($date->lt(today())) {
            throw new Exception('Bookings cannot be made for past dates.');
        }

        // Check booking date can be maximum upto configured days in future
        $maxBookingDays = (int) config('constant.max_booking_days_in_future', 30);
        if ($date->gt(today()->addDays($maxBookingDays))) {
            throw new Exception('Bookings can only be made up to ' . $maxBookingDays . ' days in advance.');
        }

        // Check booking slot time is within working hours
        $slotStartTime = Carbon::createFromFormat('H:i', $data['start_at']);
        if ($slotStartTime->lt($workingHours->start_time) || $slotStartTime->gt($workingHours->end_time)) {
            throw new Exception('The selected time slot is outside of working hours.');
        }
        //Add end time to data
        $slotEndAt = $slotStartTime->copy()->addMinutes($workingHours->duration)->format('H:i:s');

        // Check if slot is available
        if (!$this->bookingRepo->isSlotAvailable($data['date'], $data['start_at'])) {
            throw new Exception('The selected time slot is not available.');
        }

        
        $data['end_at'] = $slotEndAt;

        // Create cache lock to prevent race conditions
        $lockKey = 'booking_lock_' . $data['date'] . '_' . $data['start_at'];
        $lock = Cache::lock($lockKey, 5); // 5 second lock

        if (!$lock->get()) {
            throw new Exception('Unable to acquire booking lock. Please try again.');
        }

        try {
            $booking = DB::transaction(function () use ($data) {

                //create the client
                $clientId = $this->clientRepo->findOrCreate([
                    'name'  => $data['name'],
                    'email' => $data['email'],
                ])?->id ?? null;

                if (!$clientId) {
                    throw new Exception('Unable to create or find client.');
                }

                $data['client_id'] = $clientId;

                // Create the booking
                $booking = $this->bookingRepo->create($data);

                // Fire event for sending email/SMS notification
                event(new BookingCreated($booking));

                return $booking;
            });

            return $booking;

        } finally {
            $lock->release();
        }
    }

    /**
     * Get booking slots for the date
     *
     * @param string $date
     * @return array
     */
    public function getSlots($date): array
    {
        $date = Carbon::parse($date);
        $workingHours = $this->workingRepo->findByDay($date->dayOfWeek());
        
        //TODO:: Throw exception if working hours not found for the day
        if (!$workingHours) {
            return [
                'available'       => [],
                'not_available'   => []
            ];
        }

        $availableSlots = $this->availableSlots($workingHours);
        $notAvailableSlots = $this->notAvailableSlots($date, $workingHours);

        return [
            'available'       => $availableSlots,
            'not_available'   => $notAvailableSlots
        ];
    }

    /**
     * Get date wise booking counts.
     * @return SupportCollection
     */
    public function getDateWiseBookingCounts(): SupportCollection
    {
        return $this->bookingRepo->getAll()->groupBy('date')->map(function ($bookings, $date) {
            return [
                'date'  => Carbon::parse($date)->toDateString(),
                'count' => $bookings->count(),
            ];
        })->values();
    }

    /**
     * Get available time slots for the given working hours.
     * Returns all slots that are generated from the working hours start and end time.
     *
     * @param \App\Models\Service\WorkingHour $workingHours
     * @return array
     */
    private function availableSlots($workingHours): array
    {
        return $this->slotService->generateSlots(
            $workingHours->start_time,
            $workingHours->end_time,
            $workingHours->duration
        );
    }

    /**
     * Get unavailable time slots for the given date and working hours.
     * 
     * Logic:
     * - If date is in the past: All slots are unavailable
     * - If date is today: Slots before current time + already booked slots are unavailable
     * - If date is in the future: Only already booked slots are unavailable
     *
     * @param \Carbon\Carbon $date
     * @param \App\Models\Service\WorkingHour $workingHours
     * @return array
     */
    private function notAvailableSlots($date, $workingHours): array
    {
        // if date is past then all slot generated for the date will not be available,
        if ($date->isPast()) {
            return $this->slotService->generateSlots(
                $workingHours->start_time,
                $workingHours->end_time,
                $workingHours->duration
            );
        }
        // if date is today, then all slots which are less then current time will not be available and also block the slots which is already booked for the day
        $slots = [];
        if ($date->isToday()) {
            $currentTime = Carbon::now()->format('H:i:s');
            $slots = $this->slotService->generateSlots(
                $workingHours->start_time,
                $currentTime,
                $workingHours->duration
            );
            $bookedSlots = $this->bookingRepo->getBookedSlotsForDate($date->toDateString());
            foreach ($bookedSlots as $bookedSlot) {
                $slots[] = $bookedSlot->start_at->format('H:i');
            }
            return array_unique($slots);
        }

        // if date is future then only block the slots which is already booked for the day
        $bookedSlots = $this->bookingRepo->getBookedSlotsForDate($date->toDateString());
        foreach ($bookedSlots as $bookedSlot) {
            $slots[] = $bookedSlot->start_at->format('H:i');
        }

        return array_unique($slots);
    }
}
