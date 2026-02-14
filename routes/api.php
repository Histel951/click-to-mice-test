<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->middleware(['api', 'auth:sanctum'])->group(function () {
    Route::get('', [OrderController::class, 'index']);
    Route::get('{order}', [OrderController::class, 'show']);
    Route::post('', [OrderController::class, 'create']);
    // uuid а не order, чтобы не отправлять в базу лишний запрос
    Route::delete('{uuid}', [OrderController::class, 'destroy']);
    Route::patch('{uuid}/cancel', [OrderController::class, 'cancel']);
    Route::patch('{uuid}/services', [OrderController::class, 'updateServices']);
    Route::post('{uuid}/start-processing', [OrderController::class, 'startProcessing']);
});

Route::prefix('auth')->middleware(['api'])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
});

Route::middleware(['api', 'auth:sanctum'])->get('services', [ServiceController::class, 'index']);
