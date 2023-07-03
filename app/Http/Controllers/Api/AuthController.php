<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dotenv\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends ApiController
{
    public function register(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(),[
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails())
            {
                return $this->errorResponse(500,'Authorization has been denied for this request');
            }
            $password = Hash::make($request->password);
            $user_exists = User::where('username',$request->username)->exists();
            if($user_exists){
                $user = User::where('username',$request->username)->first();
                if(Hash::check($request->password , $user->password)){
                    $token = $user->createToken('Auth')->accessToken;

                    return $this->successResponse(200,[
                        'user' => $user->username,
                        'Authorization' => $token,
                    ]);
                }

            }else{
                return $this->errorResponse(500,'Authorization has been denied for this request');
            }


        }catch (\Exception $e){
            $this->errorResponse(500,$e->getMessage());
        }


    }
}
