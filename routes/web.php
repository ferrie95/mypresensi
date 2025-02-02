<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiOnlineController;

// Route untuk halaman login dan register (tanpa middleware 'auth')
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Route dengan middleware 'auth'
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Halaman default (redirect ke login)
Route::get('/', function () {
    return redirect()->route('login');
});


//Absensi Online
Route::get('/absensi/absensi_online', [AbsensiOnlineController::class, 'index'])->name('index');
Route::post('/absensi/absensi_online/store', [AbsensiOnlineController::class, 'store'])->name('store');
