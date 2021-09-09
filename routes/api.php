<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Users\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register',            [AuthController::class, 'register']);
Route::post('/login',               [AuthController::class, 'login']);
Route::post('/refresh_token',       [AuthController::class, 'refresh_token']);

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('/user_information',             [UsersController::class, 'user_information']);
    Route::post('logout',                       [UsersController::class, 'logout']);

});
