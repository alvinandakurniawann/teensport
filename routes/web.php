<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register'); // Changed from regis to register
    Route::post('register', [RegisterController::class, 'store']);
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    
    Route::middleware('admin')->group(function () {
        Route::get('admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    });
});
