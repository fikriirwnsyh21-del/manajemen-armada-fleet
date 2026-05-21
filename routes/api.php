<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KendaraanController;

Route::apiResource('kendaraan', KendaraanController::class);
