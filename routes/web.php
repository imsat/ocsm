<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChargingStationController;

Route::get('/', [ChargingStationController::class, 'dashboard'])->name('dashboard');
Route::post('/charging-stations/{station}/toggle-status', [ChargingStationController::class, 'toggleStatus'])->name('charging-stations.toggle-status');
Route::resource('charging-stations', ChargingStationController::class);




