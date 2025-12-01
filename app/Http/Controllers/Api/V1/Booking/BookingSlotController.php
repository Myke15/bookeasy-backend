<?php

namespace App\Http\Controllers\Api\V1\Booking;

use App\Contracts\Booking\BookingServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class BookingSlotController extends Controller
{
    public function __construct(
        protected BookingServiceInterface $bookingService
    ) { }
    
    /**
     * List booking slots.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $date = $request->date ?? today()->format('Y-m-d');
            
            $slots = $this->bookingService->getSlots($date);
            
            return $this->responseSuccessWithData([
                'slots' => $slots
            ]);

        } catch (Exception $e) {

            Log::error('Error while listing slot information', [
                'request' => $request->all(), 
                'error' => $e->getMessage(), 
                'stack' => $e->getTraceAsString()
            ]);
            
            return $this->responseInternalServerError('Unable to load booking slots');
        }
    }
}
