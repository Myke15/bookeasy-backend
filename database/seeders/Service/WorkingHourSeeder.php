<?php

namespace Database\Seeders\Service;

use App\Models\Service\WorkingHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkingHour::factory()->allDays();
    }
}
