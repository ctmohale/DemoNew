<?php

use App\Http\Controllers\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Get all cars
Route::get('/cars', [Cars::class, 'get_all_cars'])->name('cars.get');

// Create new car
Route::post('/cars_add', [Cars::class, 'add_cars'])->name('cars.add');

Route::get('/cars_get/{id}', [Cars::class, 'get_car'])->name('cars.get');

Route::put('/cars_update/{id}', [Cars::class, 'update_car'])->name('cars.update');
