<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function successResponse($code,$data)
    {

        return response()->json($data,$code);


    }

    public function errorResponse($code = 500,$message)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,

        ],$code);
    }
}
