<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function returnResponse(array $parameters, int $statusCode) {
        return response()->json($parameters, $statusCode);
    }

    public function responseSuccess($message) {
        return $this->returnResponse([
            'result'    => true,
            'message'   => is_null($message) ? 'N/A' : $message
        ], 200);
    }

    public function responseSuccessWithData($additionalData = []) {
        return $this->returnResponse(array_merge([
            'result'    => true
        ], $additionalData), 200);
    }

    public function responseNotFound($message, $additionalData = []) {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), 404);
    }

    public function responseFail($message, $additionalData = []) {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), 200);
    }

    public function responseFailValidation($message, $additionalData = []) {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), 422);
    }

    public function responseInternalServerError($message, $additionalData = [], $statusCode = 501){
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), $statusCode);
    }
}
