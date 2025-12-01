<?php

namespace Database\Seeders\Booking;

use App\Models\Booking\Booking;
use App\Models\Client\Client;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startTime = Carbon::createFromTime(9, 0);
        //For red slot indication
        foreach (range(0, 7) as $index) {
            Booking::factory()->for(Client::factory())->create([
                'date' => today()->toDateString(),
                'start_at' => $startTime->copy()->addHours($index)->format('H:i:s'),
                'end_at' => $startTime->copy()->addHours($index + 1)->format('H:i:s'),
            ]);
        }

        //For red slot indication
        foreach (range(0, 7) as $index) {
            Booking::factory()->for(Client::factory())->create([
                'date' => today()->addDay()->toDateString(),
                'start_at' => $startTime->copy()->addHours($index)->format('H:i:s'),
                'end_at' => $startTime->copy()->addHours($index + 1)->format('H:i:s'),
            ]);
        }

        //For yellow slot indication
        foreach (range(0, 5) as $index) {
            Booking::factory()->for(Client::factory())->create([
                'date' => today()->addDays(2)->toDateString(),
                'start_at' => $startTime->copy()->addHours($index)->format('H:i:s'),
                'end_at' => $startTime->copy()->addHours($index + 1)->format('H:i:s'),
            ]);
        }
        
    }
}
