<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;

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

    public function province()
    {

        $province_new = [];
        $provinces = Province::select('id','name')->get();

        foreach ($provinces as $province){
            $p=[
                'ProvinceID'=>$province->id,
                'ProvinceName'=>$province->name
            ];
            $province_new[]=$p;
        }



        return $this->successResponse(200,
            $province_new
        );
    }
}
