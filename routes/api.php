<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SendWelcomeEmailController;

Route::apiResource('users', UserController::class);
Route::post('users/{user}/send-welcome', SendWelcomeEmailController::class);
