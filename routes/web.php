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

// Protected Routes - SEMUA BISA AKSES DULU (testing)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');

    // Menu lainnya
    Route::get('/datamhs', function () {
        return view('datamhs.index');
    })->name('datamhs.index');

    Route::get('/masterdata', function () {
        return view('masterdata.index');
    })->name('masterdata.index');

    Route::get('/ppl', function () {
        return view('ppl.index');
    })->name('ppl.index');

    Route::get('/matkur', function () {
        return view('matkur.index');
    })->name('matkur.index');

    Route::get('/laporan', function () {
        return view('laporan.index');
    })->name('laporan.index');

    Route::get('/setting', function () {
        return view('setting.index');
    })->name('setting.index');

    // Lapor Diri Routes - SEMUA BISA AKSES DULU
    Route::resource('lapor', LaporDiriController::class);
    Route::get('/lapor/{id}/view/{field}', [LaporDiriController::class, 'viewFile'])->name('lapor.view');
    Route::get('/lapor/{id}/download/{field}', [LaporDiriController::class, 'downloadFile'])->name('lapor.download');

    // Verifikasi Routes - SEMUA BISA AKSES DULU
    Route::post('/lapor/{id}/verifikasi', [VerifikasiController::class, 'verifikasi'])->name('lapor.verifikasi');
    Route::get('/verifikasi', [VerifikasiController::class, 'listVerifikasi'])->name('verifikasi.index');
});
