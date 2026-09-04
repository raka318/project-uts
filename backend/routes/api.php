<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\DompetController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\AnggaranController;
use App\Http\Controllers\Api\TujuanTabunganController;

// Publik -- belum login boleh akses
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Butuh login -- harus kirim token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('dompet', DompetController::class);
    Route::apiResource('transaksi', TransaksiController::class)->except(['update']);
    Route::apiResource('anggaran', AnggaranController::class);
    Route::apiResource('tujuan-tabungan', TujuanTabunganController::class);
});