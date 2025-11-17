<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;

// Rute Halaman Publik (Nyamaw)
Route::get('/home', [PageController::class, 'home'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/about', [PageController::class, 'about'])->name('about');


// Rute Autentikasi (Login & Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Rute Halaman Admin yang Dilindungi
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard.adminDashboard');
    })->name('admin');
    
    // Nanti, rute lain khusus admin bisa ditaruh di sini
});

// Rute Halaman Owner yang Dilindungi
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner', function () {
        return view('dashboard.ownerDashboard');
    })->name('owner');

    // Di Langkah 7, rute CRUD User akan kita taruh di sini
});

// Rute Awal, SEKARANG ke Halaman Home
Route::get('/', function () {
    return redirect()->route('home');
});
