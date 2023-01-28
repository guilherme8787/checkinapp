<?php
namespace App\Traits;

use Illuminate\Http\Response;

trait ResponseHttpStatusCode
{

    protected function success($message, $data = [], $status = Response::HTTP_OK)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function failure($message, $status = Response::HTTP_NOT_FOUND)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

}
