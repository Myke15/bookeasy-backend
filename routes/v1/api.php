<?php

use App\Http\Controllers\Api\V1\Booking\BookingController;
use App\Http\Controllers\Api\V1\Booking\BookingSlotController;
use App\Http\Controllers\Api\V1\Service\WorkingHourController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1', 
    'namespace' => 'V1',
    'middleware' => ['api', 'throttle:60,1']
], function () {
    Route::apiResource('booking', BookingController::class)->only(['index', 'store']);
    Route::apiResource('slot', BookingSlotController::class)->only(['index']);
    Route::apiResource('working-hour', WorkingHourController::class)->only(['index', 'store']);
});
