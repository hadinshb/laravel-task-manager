<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('tasks', App\Http\Controllers\Api\V1\TaskController::class);
});
