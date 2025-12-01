<?php

namespace App\Contracts\Service;

use App\Models\Service\WorkingHour;
use Illuminate\Support\Collection as SupportCollection;

interface WorkingHourServiceInterface
{
    /**
     * Store or update working hours for multiple days.
     *
     * @param array $data
     * @return bool
     */
    public function storeOrUpdate(array $data): bool;

    /**
     * Get all working hours.
     *
     * @return SupportCollection
     */
    public function getAll(): SupportCollection;
}
