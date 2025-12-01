<?php

namespace App\Http\Controllers\Api\V1\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Contracts\Booking\BookingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingController extends Controller
{
    /**
     * BookingController constructor.
     *
     * @param BookingServiceInterface $bookingService
     */
    public function __construct(
        protected BookingServiceInterface $bookingService
    ) { }


    /**
     * List all bookings.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            //return date wise booking counts
            $bookings = $this->bookingService->getDateWiseBookingCounts();

            return $this->responseSuccessWithData([
                'bookings' => $bookings
            ]);

        } catch (Exception $e) {

            Log::error('Error while listing bookings', [
                'error' => $e->getMessage(), 
                'stack' => $e->getTraceAsString()
            ]);
            
            return $this->responseInternalServerError('Unable to load bookings');
        }
    }
    /**
     * Store a new booking.
     *
     * @param StoreBookingRequest $request
     * @return JsonResponse
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            
            $this->bookingService->createBooking($request->validated());
            
            return $this->responseSuccess('Booking created successfully');

        } catch (Exception $e) {

            Log::error('Error while creating booking', [
                'request' => $request->all(), 
                'error' => $e->getMessage(), 
                'stack' => $e->getTraceAsString()
            ]);
            
            return $this->responseInternalServerError($e->getMessage());
        }
    }
    
}
