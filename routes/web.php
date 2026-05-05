<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Petani\DashboardController;
use App\Http\Controllers\Petani\ProfilLahanController;
use App\Http\Controllers\Petani\RiwayatPanenController;
use App\Http\Controllers\Petani\SetoranController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('lahan', ProfilLahanController::class);
    Route::resource('panen', RiwayatPanenController::class);
    Route::resource('setoran', SetoranController::class);
});