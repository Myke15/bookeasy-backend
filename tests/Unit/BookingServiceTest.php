<?php

use App\Services\Booking\BookingService;
use App\Contracts\Booking\BookingRepoInterface;
use App\Contracts\Service\WorkingHourRepoInterface;
use App\Contracts\Client\ClientRepoInterface;
use App\Contracts\Booking\BookingSlotServiceInterface;
use App\Models\Service\WorkingHour;
use App\Models\Booking\Booking;
use Carbon\Carbon;

beforeEach(function () {
    // Mock all dependencies
    $this->bookingRepo = Mockery::mock(BookingRepoInterface::class);
    $this->workingRepo = Mockery::mock(WorkingHourRepoInterface::class);
    $this->clientRepo = Mockery::mock(ClientRepoInterface::class);
    $this->slotService = Mockery::mock(BookingSlotServiceInterface::class);

    // Create service instance with mocked dependencies
    $this->bookingService = new BookingService(
        $this->bookingRepo,
        $this->workingRepo,
        $this->clientRepo,
        $this->slotService
    );
});

afterEach(function () {
    Mockery::close();
});
