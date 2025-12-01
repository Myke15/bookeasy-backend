<?php

namespace App\Http\Controllers\Api\V1\Service;

use App\Contracts\Service\WorkingHourServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreWorkingHourRequest;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class WorkingHourController extends Controller
{
    /**
     * WorkingHourController constructor.
     *
     * @param WorkingHourServiceInterface $workingHourService
     */
    public function __construct(
        protected WorkingHourServiceInterface $workingHourService
    ) { }

    /**
     * Get all working hours.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $workingHours = $this->workingHourService->getAll();

            return $this->responseSuccessWithData([
                'working_hours' => $workingHours
            ]);

        } catch (Exception $e) {

            Log::error('Error while listing working hours', [
                'error' => $e->getMessage(), 
                'stack' => $e->getTraceAsString()
            ]);
            
            return $this->responseInternalServerError('Unable to load working hours');
        }
    }

    /**
     * Store or update working hour.
     *
     * @param StoreWorkingHourRequest $request
     * @return JsonResponse
     */
    public function store(StoreWorkingHourRequest $request): JsonResponse
    {
        try {
            
            $this->workingHourService->storeOrUpdate($request->validated());
            
            return $this->responseSuccess('Working hours saved successfully');

        } catch (Exception $e) {
            
            Log::error('Error while saving working hours', [
                'request' => $request->all(), 
                'error' => $e->getMessage(), 
                'stack' => $e->getTraceAsString()
            ]);
            
            return $this->responseInternalServerError('Unable to save working hours!');
        }
    }
}
