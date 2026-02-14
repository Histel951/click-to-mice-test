<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'create']);
Route::patch('/orders', [OrderController::class, 'update']);
Route::delete('/orders/{uuid}', [OrderController::class, 'destroy']);

Route::get('/services', [ServiceController::class, 'index']);
