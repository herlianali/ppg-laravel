<?php

use App\Http\Controllers\Admin\LaporVervalIdController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporDiriController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BidangStudiController;
use App\Http\Controllers\MahasiswaController;

// =======================
// AUTHENTICATION
// =======================
Route::get('/', fn() => redirect('/login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// =======================
// ROUTE UTAMA (SEMUA ROLE)
// =======================
Route::middleware(['auth', 'check.menu'])->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');

    // =======================
    // DATA MASTER (Admin)
    // =======================
    Route::get('/masterdata', fn() => view('masterdata.index'))->name('masterdata.index');

    // =======================
    // VERIFIKASI (Admin & Verifikator)
    // =======================
    Route::get('/verifikasi', [VerifikasiController::class, 'listVerifikasi'])->name('verifikasi.index');
    Route::post('/lapor/{id}/verifikasi', [VerifikasiController::class, 'verifikasi'])->name('lapor.verifikasi');

    // Data Saya
    Route::get('/lapor/my', [LaporDiriController::class, 'myData'])->name('lapor.my.index');
    Route::get('/lapor/my/{id}', [LaporDiriController::class, 'showMyData'])->name('lapor.my.show');

    // =======================
    // LAPOR DIRI VERVAL ID (Admin)
    // =======================
    Route::get('/lapor/admin', [LaporVervalIdController::class, 'index'])->name('lapor.admin.index');

    // =======================
    // LAPOR DIRI (Mahasiswa)
    // =======================
    Route::get('/lapor/create', [LaporDiriController::class, 'create'])->name('lapor.create');
    Route::post('/lapor', [LaporDiriController::class, 'store'])->name('lapor.store');
    Route::get('/lapor/{id}', [LaporDiriController::class, 'show'])->name('lapor.show');
    Route::get('/lapor/{id}/edit', [LaporDiriController::class, 'edit'])->name('lapor.edit');
    Route::put('/lapor/{id}', [LaporDiriController::class, 'update'])->name('lapor.update');
    Route::delete('/lapor/{id}', [LaporDiriController::class, 'destroy'])->name('lapor.destroy');

    // =======================
    // MENU MANAGEMENT (Admin)
    // =======================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('menus', MenuController::class);
        Route::post('menus/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menus.toggle-status');
        Route::post('menus/activate-all', [MenuController::class, 'activateAll'])->name('menus.activate-all');
        Route::post('menus/deactivate-all', [MenuController::class, 'deactivateAll'])->name('menus.deactivate-all');

        Route::resource('users', UserController::class);
        Route::post('users/import', [UserController::class, 'import'])->name('users.import');
        Route::get('users/download-template', [UserController::class, 'downloadTemplate'])->name('users.download-template');

        Route::resource('bidang-studi', BidangStudiController::class);
        Route::get('mahasiswa', [UserController::class, 'mahasiswa'])->name('mahasiswa.index');
    });

    // =======================
    // LAPORAN (Admin)
    // =======================
    Route::get('/laporan', fn() => view('laporan.index'))->name('laporan.index');
});

// =======================
// COMMON (SEMUA ROLE LOGIN)
// =======================
Route::middleware(['auth'])->group(function () {
    // File View & Download
    Route::get('/lapor/{id}/view/{field}', [LaporDiriController::class, 'viewFile'])->name('lapor.view');
    Route::get('/lapor/{id}/download/{field}', [LaporDiriController::class, 'downloadFile'])->name('lapor.download');
});
