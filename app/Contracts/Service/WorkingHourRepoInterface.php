<?php

namespace App\Contracts\Service;

use App\Models\Service\WorkingHour;
use Illuminate\Database\Eloquent\Collection;

interface WorkingHourRepoInterface
{
    /**
     * Update or create working hour record.
     *
     * @param array $attributes
     * @param array $values
     * @return WorkingHour
     */
    public function updateOrCreate(array $attributes, array $values): WorkingHour;

    /**
     * Find working hour by day.
     *
     * @param int $day
     * @return WorkingHour|null
     */
    public function findByDay(int $day): ?WorkingHour;

    /**
     * Get all working hours.
     *
     * @return Collection
     */
    public function getAll(): Collection;


    /**
     * Remove working hours
     *
     * @param array $days
     * @return bool
     */
    public function remove($days): bool;

    
}
