<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;



Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::apiResource('products', ProductApiController::class);
});
