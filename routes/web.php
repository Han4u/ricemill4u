<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Petani\DashboardController     as PetaniDashboard;
use App\Http\Controllers\Petani\ProfilLahanController;
use App\Http\Controllers\Petani\RiwayatPanenController;
use App\Http\Controllers\Petani\SetoranController;
use App\Http\Controllers\RiceMill\DashboardController  as RiceMillDashboard;
use App\Http\Controllers\RiceMill\PenerimaanGabahController;
use App\Http\Controllers\RiceMill\OperasionalController;
use App\Http\Controllers\RiceMill\ProduksiController;
use App\Http\Controllers\RiceMill\PengirimanController;
use App\Http\Controllers\RiceMill\KeuanganController;
use App\Http\Controllers\Packager\DashboardController  as PackagerDashboard;
use App\Http\Controllers\Packager\PenerimaanBerasController;
use App\Http\Controllers\Packager\PengemasanController;
use App\Http\Controllers\Packager\PesananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'petani') {
            return redirect()->route('petani.dashboard');
        } elseif ($user->role === 'rice_mill') {
            return redirect()->route('ricemill.dashboard');
        } elseif ($user->role === 'packager') {
            return redirect()->route('packager.dashboard');
        }
        return redirect()->route('home');
    }
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ── Petani Routes ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:petani'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [PetaniDashboard::class, 'index'])->name('dashboard');
    Route::resource('lahan',   ProfilLahanController::class);
    Route::resource('panen',   RiwayatPanenController::class);
    Route::resource('setoran', SetoranController::class)->except(['show']);
});

// ── Rice Mill Routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'role:rice_mill'])->prefix('ricemill')->name('ricemill.')->group(function () {
    Route::get('/dashboard', [RiceMillDashboard::class, 'index'])->name('dashboard');

    Route::resource('penerimaan-gabah', PenerimaanGabahController::class);
    Route::resource('operasional',      OperasionalController::class);
    Route::resource('produksi',         ProduksiController::class)->except(['edit', 'update']);
    Route::resource('pengiriman',       PengirimanController::class);
    Route::resource('keuangan',         KeuanganController::class)->except(['show', 'edit', 'update']);
});

// ── Packager Routes ────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:packager'])->prefix('packager')->name('packager.')->group(function () {
    Route::get('/dashboard', [PackagerDashboard::class, 'index'])->name('dashboard');

    Route::resource('penerimaan-beras', PenerimaanBerasController::class);
    Route::resource('pengemasan',      PengemasanController::class)->except(['edit', 'update']);
    Route::resource('pesanan',         PesananController::class);
});