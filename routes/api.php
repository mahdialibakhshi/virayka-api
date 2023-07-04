<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/token/GetServiceToken',[\App\Http\Controllers\Api\AuthController::class,'register']);

Route::middleware('auth:api')->get('/Speed/GetProvince',[\App\Http\Controllers\Api\ApiController::class,'province']);
