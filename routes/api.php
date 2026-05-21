<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\SupirController;
use App\Http\Controllers\RuteController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\BahanBakarController;

/*
|--------------------------------------------------------------------------
| API Routes - Sistem Manajemen Armada
|--------------------------------------------------------------------------
*/

// Kendaraan Module
Route::apiResource('kendaraan', KendaraanController::class);
Route::get('kendaraan-list', [KendaraanController::class, 'list']);

// Supir Module
Route::apiResource('supir', SupirController::class);
Route::get('supir-list', [SupirController::class, 'list']);

// Rute Module
Route::apiResource('rute', RuteController::class);

// Perawatan Module
Route::apiResource('perawatan', PerawatanController::class);

// Bahan Bakar Module
Route::apiResource('bahan-bakar', BahanBakarController::class);
