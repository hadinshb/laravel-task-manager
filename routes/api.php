<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    // Define routes for authentication
    Route::post('login', [App\Http\Controllers\Api\V1\AuthController::class, 'login']);
    Route::post('register', [App\Http\Controllers\Api\V1\AuthController::class, 'register']);

    // Protected by Sanctum authentication
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('tasks', App\Http\Controllers\Api\V1\TaskController::class);
    });
});
