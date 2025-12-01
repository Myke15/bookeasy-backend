<?php

namespace Database\Factories\Service;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection as SupportCollection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service\WorkingHour>
 */
class WorkingHourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'day'           => 0,
            'start_time'    => '09:00:00',
            'end_time'      => '17:00:00',
            'duration'      => 60,
        ];
    }

    /**
     * Create working hours for working days of the week.
     */
    public function allDays(): SupportCollection
    {
        $days = [];
        for ($i = 1; $i < 6; $i++) {
            $days[] = $this->create([
                'day' => $i,
                'start_time'    => '09:00:00',
                'end_time'      => '17:00:00',
                'duration'      => 60,
            ]);
        }
        
        return collect($days);
    }
}
