<?php

namespace App\Repositories\Service;

use App\Contracts\Service\WorkingHourRepoInterface;
use App\Models\Service\WorkingHour;
use Illuminate\Database\Eloquent\Collection;

class WorkingHourRepository implements WorkingHourRepoInterface
{
    /**
     * Update or create working hour record.
     *
     * @param array $attributes
     * @param array $values
     * @return WorkingHour
     */
    public function updateOrCreate(array $attributes, array $values): WorkingHour
    {
        return WorkingHour::updateOrCreate($attributes, $values);
    }

    /**
     * Get working hours timing for a day
     *
     * @param int $day
     * @return WorkingHour|null
     */
    public function findByDay(int $day): ?WorkingHour
    {
        return WorkingHour::where('day', $day)->first();
    }

    /**
     * Get all working hours.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return WorkingHour::select('day', 'start_time', 'end_time', 'duration')->get();
    }

    /**
     * Remove working hours
     *
     * @param array $days
     * @return bool
     */
    public function remove($days): bool
    {
        return WorkingHour::whereNotIn('day', $days)->delete();
    }
}
    
