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

Route::get('/run-migrations', function () {
    try {
        $output = '';
        // Check if database connection works
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        
        if (request()->has('fresh')) {
            \Illuminate\Support\Facades\Artisan::call('db:wipe', ['--force' => true]);
            $output .= "Database wiped successfully!\n\n";
        }
        
        $params = ['--force' => true];
        if (request()->has('seed')) {
            $params['--seed'] = true;
        }
        
        \Illuminate\Support\Facades\Artisan::call('migrate', $params);
        $output .= \Illuminate\Support\Facades\Artisan::output();
        
        $status = 'success';
        $message = 'Database migrations completed successfully!';
    } catch (\Exception $e) {
        $status = 'error';
        $message = 'Failed to run migrations: ' . $e->getMessage();
        $output = $e->getMessage() . "\n\n" . $e->getTraceAsString();
    }
    
    $themeColor = $status === 'success' ? '#10B981' : '#EF4444';
    
    return "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Migration Status</title>
        <link href='https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap' rel='stylesheet'>
        <style>
            body {
                font-family: 'Outfit', sans-serif;
                background: #0f172a;
                color: #f8fafc;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
                box-sizing: border-box;
            }
            .card {
                background: rgba(30, 41, 59, 0.7);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 24px;
                padding: 40px;
                max-width: 650px;
                width: 100%;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                text-align: center;
            }
            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 8px 20px;
                border-radius: 9999px;
                font-weight: 600;
                font-size: 0.875rem;
                background: {$themeColor}20;
                color: {$themeColor};
                border: 1px solid {$themeColor}40;
                margin-bottom: 24px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            h1 {
                font-size: 1.875rem;
                font-weight: 700;
                margin: 0 0 16px 0;
                color: #ffffff;
            }
            p {
                color: #94a3b8;
                font-size: 1rem;
                line-height: 1.6;
                margin: 0 0 32px 0;
            }
            .output-box {
                text-align: left;
                background: #090d16;
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 12px;
                padding: 20px;
                font-family: 'Courier New', Courier, monospace;
                font-size: 0.875rem;
                color: #e2e8f0;
                overflow-x: auto;
                max-height: 300px;
                white-space: pre-wrap;
            }
            .btn {
                display: inline-block;
                margin-top: 32px;
                padding: 12px 32px;
                background: #3b82f6;
                color: white;
                text-decoration: none;
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.2s ease;
                box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.4);
            }
            .btn:hover {
                background: #2563eb;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px 0 rgba(59, 130, 246, 0.6);
            }
        </style>
    </head>
    <body>
        <div class='card'>
            <div class='status-badge'>{$status}</div>
            <h1>{$message}</h1>
            <p>Database connection type: <strong>" . config('database.default') . "</strong></p>
            <div class='output-box'>" . htmlspecialchars($output ?: 'No output returned.') . "</div>
            <a href='/' class='btn'>Go to Homepage</a>
        </div>
    </body>
    </html>
    ";
});

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