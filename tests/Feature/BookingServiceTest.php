<?php

use App\Models\Booking\Booking;
use App\Models\Client\Client;
use App\Models\Service\WorkingHour;
use App\Services\Booking\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

beforeEach(function () {
    WorkingHour::factory()->allDays();
});

$baseBookingData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'service' => 'haircut',
    'date' => now()->next('Monday')->toDateString(),
    'start_at' => '10:00'
];

test('can create booking with valid data', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;

    $booking = (app(BookingService::class))->createBooking($bookingData);

    expect($booking)->toBeInstanceOf(Booking::class);
    expect($booking->client->email)->toBe('john@example.com');
    expect($booking->date->toDateString())->toBe($bookingData['date']);

    // Verify database records
    $this->assertDatabaseHas('bookings', [
        'date' => $bookingData['date'],
        'start_at' => '10:00'
    ]);

    $this->assertDatabaseHas('bookings', [
        'date' => $bookingData['date'],
        'end_at' => '11:00:00'
    ]);

    $this->assertDatabaseHas('clients', [
        'email' => 'john@example.com'
    ]);
});

test('validates booking data through API endpoint', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;
    // Test the full HTTP request/response cycle
    $response = $this->postJson('/api/v1/booking', $bookingData, [
        'Accept'        => 'application/json',
        'X-App-Token'   => config('app.app_token'),
    ]);

    $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message'
            ]);
});

test('should throw exception for non working day booking', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;
    $bookingData['date'] = now()->next('Sunday')->toDateString(); // Sunday - non-working day

    // Act & Assert
    $bookingService = app(BookingService::class);

    expect(fn() => $bookingService->createBooking($bookingData))
        ->toThrow(Exception::class, 'No working hours found for the selected date.');
});

test('should throw exception for past day bookings', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;
    $bookingData['date'] = now()->previousWeekday('Monday')->toDateString();

    // Act & Assert
    $bookingService = app(BookingService::class);

    expect(fn() => $bookingService->createBooking($bookingData))
        ->toThrow(Exception::class, 'Bookings cannot be made for past dates.');
});

test('should throw exception for future days bookings beyond allowed days', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;
    $bookingData['date'] = now()->addDays(50)->toDateString(); // 50 days in future
    
    config(['constant.max_booking_days_in_future' => 30]);
    // Act & Assert
    $bookingService = app(BookingService::class);

    expect(fn() => $bookingService->createBooking($bookingData))
        ->toThrow(Exception::class, 'Bookings can only be made up to 30 days in advance.');
});

test('should throw exception for booking out of working hours', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;
    $bookingData['start_at'] = '18:00'; // Outside working hours

    // Act & Assert
    $bookingService = app(BookingService::class);

    expect(fn() => $bookingService->createBooking($bookingData))
        ->toThrow(Exception::class, 'The selected time slot is outside of working hours.');
});

test('should throw exception when slot is already booked', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;

    // Create first booking
    app(BookingService::class)->createBooking($bookingData);

    // Try to create second booking at same time with different client
    $bookingData2 = $bookingData;
    $bookingData2['name'] = 'Jane Doe';
    $bookingData2['email'] = 'jane@example.com';

    // Act & Assert
    $bookingService = app(BookingService::class);

    expect(fn() => $bookingService->createBooking($bookingData2))
        ->toThrow(Exception::class, 'The selected time slot is not available.');
});

test('cache lock being released on successful bookings', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;

    // Act
    $booking = app(BookingService::class)->createBooking($bookingData);

    // Assert
    expect($booking)->toBeInstanceOf(Booking::class);

    // Check that lock is released (cache should not have the lock key)
    $lockKey = 'booking_lock_' . $bookingData['date'] . '_' . $bookingData['start_at'];
    expect(Cache::has($lockKey))->toBeFalse();
});

test('prevent booking for concurrent request', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;

    $lockKey = 'booking_lock_' . $bookingData['date'] . '_' . $bookingData['start_at'];
    $lock = Cache::lock($lockKey, 5);

    // Acquire lock manually
    $lock->get();

    // Now try to create booking - should fail because lock is held
    $bookingService = app(BookingService::class);

    expect(fn() => $bookingService->createBooking($bookingData))
        ->toThrow(Exception::class, 'Unable to acquire booking lock. Please try again.');

    // Release lock
    $lock->release();
});

test('on successful booking it fire event', function () use ($baseBookingData) {
    $bookingData = $baseBookingData;
    Event::fake();

    // Act
    app(BookingService::class)->createBooking($bookingData);

    // Assert
    Event::assertDispatched(\App\Events\Booking\BookingCreated::class);
});