<?php

use App\Services\Booking\BookingSlotService;


beforeEach(function () {
    $this->service = new BookingSlotService();
});

// GenerateSlots should return an array of "H:i" strings between start and end
it('generates correct slots for given interval', function () {
    // Example: from 09:00 to 11:00 with 30 minutes interval
    $slots = $this->service->generateSlots('09:00', '11:00', 30);

    // expected: 09:00, 09:30, 10:00, 10:30
    expect($slots)->toBeArray()
                   ->toHaveCount(4)
                   ->toContain('09:00')
                   ->toContain('10:30');
});

it('generates slots within the range', function () {
    $slots = $this->service->generateSlots('08:15', '10:45', 45);
    expect($slots)->not()->toContain('11:00');
});

it('work with any minute of the hour', function () {
    $slots = $this->service->generateSlots('09:02', '12:02', 60);
    expect($slots)->toEqual(['09:02', '10:02', '11:02']);
});

it('returns empty when end time equals or before start time', function () {
    expect($this->service->generateSlots('10:00', '10:00', 30))->toBe([]);
    expect($this->service->generateSlots('11:00', '10:00', 30))->toBe([]);
});

it('calculates total slots correctly', function () {
    $count = $this->service->totalSlots('09:00', '12:00', 30);
    expect($count)->toEqual(6); // 3 hours / 30min = 6
});