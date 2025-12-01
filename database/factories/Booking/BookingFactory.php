<?php

namespace Database\Factories\Booking;

use App\Models\Client\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking\Booking>
 */
class BookingFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::now()->addDays(fake()->numberBetween(0, 30));
        $startTime = $date->copy()->startOfDay()->addHours(9)->startOfHour();
        $endTime = $startTime->copy()->addMinutes(60);

        return [
            'client_id'     => Client::factory(),
            'service'       => array_rand(config('constant.services')),
            'date'          => $date->format('Y-m-d'),
            'start_at'      => $startTime->format('H:i:s'),
            'end_at'        => $endTime->format('H:i:s'),
        ];
    }
}
