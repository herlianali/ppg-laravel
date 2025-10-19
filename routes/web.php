<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporDiriController;
use App\Http\Controllers\VerifikasiController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Routes
Route::get('/', function () {
    return redirect('/login');
});

// Protected Routes dengan Middleware Auth
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - bisa diakses semua role yang login
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');

    // ========== ROUTES UNTUK ADMIN ==========
    Route::middleware(['administrator'])->group(function () {
        Route::get('/masterdata', function () {
            return view('masterdata.index');
        })->name('masterdata.index');

        Route::get('/setting', function () {
            // return view('setting.index');
            // sementara nanti bakalan dropdown
            return redirect()->route('admin.menus.index');
        })->name('setting.index');

        // Admin bisa lihat semua data lapor diri
        Route::get('/lapor/admin', [LaporDiriController::class, 'index'])->name('lapor.admin.index');
        Route::middleware(['auth', 'administrator'])->prefix('admin')->name('admin.')->group(function () {
            Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);
            
            // Routes untuk status menu
            Route::post('menus/{menu}/toggle-status', [\App\Http\Controllers\Admin\MenuController::class, 'toggleStatus'])
                ->name('menus.toggle-status');
            Route::post('menus/activate-all', [\App\Http\Controllers\Admin\MenuController::class, 'activateAll'])
                ->name('menus.activate-all');
            Route::post('menus/deactivate-all', [\App\Http\Controllers\Admin\MenuController::class, 'deactivateAll'])
                ->name('menus.deactivate-all');
        });
    });

    // ========== ROUTES UNTUK VERIFIKATOR ==========
    Route::middleware(['verifikator'])->group(function () {
        Route::get('/verifikasi', [VerifikasiController::class, 'listVerifikasi'])->name('verifikasi.index');
        Route::post('/lapor/{id}/verifikasi', [VerifikasiController::class, 'verifikasi'])->name('lapor.verifikasi');
        
        Route::get('/datamhs', function () {
            return view('datamhs.index');
        })->name('datamhs.index');

        // Verifikator bisa lihat semua data lapor diri
        Route::get('/lapor/verifikator', [LaporDiriController::class, 'list'])->name('lapor.verifikator.index');
    });

    // ========== ROUTES UNTUK USER BIASA ==========
    Route::middleware(['mahasiswa'])->group(function () {
        // Lapor Diri - hanya user biasa yang bisa buat/edit
        Route::get('/lapor/create', [LaporDiriController::class, 'create'])->name('lapor.create');
        Route::post('/lapor', [LaporDiriController::class, 'store'])->name('lapor.store');
        Route::get('/lapor/{id}/edit', [LaporDiriController::class, 'edit'])->name('lapor.edit');
        Route::put('/lapor/{id}', [LaporDiriController::class, 'update'])->name('lapor.update');
        Route::delete('/lapor/{id}', [LaporDiriController::class, 'destroy'])->name('lapor.destroy');

        // User biasa hanya bisa lihat data sendiri
        Route::get('/lapor/my', [LaporDiriController::class, 'myData'])->name('lapor.my.index');
        Route::get('/lapor/my/{id}', [LaporDiriController::class, 'showMyData'])->name('lapor.my.show');
    });

    // ========== ROUTES YANG BISA DIAKSES MULTI ROLE ==========
    
    // View file dan download - bisa diakses semua role yang memiliki akses ke data
    Route::get('/lapor/{id}/view/{field}', [LaporDiriController::class, 'viewFile'])->name('lapor.view');
    Route::get('/lapor/{id}/download/{field}', [LaporDiriController::class, 'downloadFile'])->name('lapor.download');

    // Menu lainnya yang bisa diakses semua role
    Route::get('/ppl', function () {
        return view('ppl.index');
    })->name('ppl.index');

    Route::get('/matkur', function () {
        return view('matkur.index');
    })->name('matkur.index');

    Route::get('/laporan', function () {
        return view('laporan.index');
    })->name('laporan.index');
});