<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChargingStationController;

Route::get('/', [ChargingStationController::class, 'dashboard'])->name('dashboard');

Route::prefix('charging-stations')->name('charging-stations.')->group(function () {
    Route::get('/', [ChargingStationController::class, 'index'])->name('index');
    Route::get('/{station}', [ChargingStationController::class, 'show'])->name('show');
    Route::post('/{station}/command', [ChargingStationController::class, 'sendCommand'])->name('command');
});
