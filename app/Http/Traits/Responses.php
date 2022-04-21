<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;

trait Responses
{
    public function SuccessResponse($response_data, $status_code=Response::HTTP_OK)
    {
        return response(['status' => 'OK' , 'data' => $response_data] , $status_code);
    }

    public function FailResponse($response_data, $status_code=Response::HTTP_BAD_REQUEST)
    {
        return response(['status' => 'OK' , 'data' => $response_data] , $status_code);
    }
}
