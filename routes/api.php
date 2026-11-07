<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;

// Route tes/default
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route API untuk controller Anda
Route::apiResource('siswa', SiswaController::class);
Route::apiResource('pembayaran', PembayaranController::class);
Route::apiResource('pengeluaran', PengeluaranController::class);
use App\Http\Controllers\Api\KasKelasController;

// Route untuk Kas Kelas Digital
Route::get('/kas-kelas', [KasKelasController::class, 'index']);
Route::post('/kas-masuk', [KasKelasController::class, 'storeKasMasuk']);
Route::post('/upload-bukti', [KasKelasController::class, 'storeUploadProof']);
