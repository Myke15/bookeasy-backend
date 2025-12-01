<?php

namespace App\Services\Service;

use App\Contracts\Booking\BookingSlotServiceInterface;
use App\Contracts\Service\WorkingHourServiceInterface;
use App\Contracts\Service\WorkingHourRepoInterface;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;


class WorkingHourService implements WorkingHourServiceInterface
{
    /**
     * WorkingHourService constructor.
     *
     * @param WorkingHourRepoInterface $workingHourRepo
     */
    public function __construct(
        protected WorkingHourRepoInterface $workingHourRepo,
        protected BookingSlotServiceInterface $slotService
    ) { }

    /**
     * Store or update working hours for multiple days.
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function storeOrUpdate(array $data): bool
    {
        return DB::transaction(function () use ($data) {
            $days = $data['days'];
            
            foreach ($days as $day) {
                $this->workingHourRepo->updateOrCreate(
                    ['day' => $day],
                    [
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                    ]
                );
            }

            //remove unchcked day's working hours for not working days
            $this->workingHourRepo->remove($days);

            return true;
        });
    }

    /**
     * Get all working hours.
     *
     * @return SupportCollection
     */
    public function getAll(): SupportCollection
    {
        return $this->workingHourRepo->getAll()->map(function ($workingHour) {

            $totalSlots = $this->slotService->totalSlots(
                $workingHour->start_time,
                $workingHour->end_time,
                $workingHour->duration
            );

            return [
                'day'         => $workingHour->day,
                'start_time'  => Carbon::parse($workingHour->start_time)->format('H:i'),
                'end_time'    => Carbon::parse($workingHour->end_time)->format('H:i'),
                'duration'    => $workingHour->duration,
                'total_slots' => $totalSlots,
            ];
        });
    }
}
