<?php

namespace App\Providers;

use App\Contracts\Booking\BookingRepoInterface;
use App\Contracts\Booking\BookingServiceInterface;
use App\Contracts\Booking\BookingSlotServiceInterface;
use App\Contracts\Client\ClientRepoInterface;
use App\Contracts\Service\WorkingHourRepoInterface;
use App\Contracts\Service\WorkingHourServiceInterface;
use App\Events\Booking\BookingCreated;
use App\Listeners\Booking\SendBookingConfirmationNotification;
use App\Repositories\Booking\BookingRepository;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Service\WorkingHourRepository;
use App\Services\Booking\BookingService;
use App\Services\Booking\BookingSlotService;
use App\Services\Service\WorkingHourService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookingServiceInterface::class, BookingService::class);
        $this->app->bind(BookingRepoInterface::class, BookingRepository::class);
        $this->app->bind(BookingSlotServiceInterface::class, BookingSlotService::class);
        $this->app->bind(WorkingHourRepoInterface::class, WorkingHourRepository::class);
        $this->app->bind(WorkingHourServiceInterface::class, WorkingHourService::class);
        $this->app->bind(ClientRepoInterface::class, ClientRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
