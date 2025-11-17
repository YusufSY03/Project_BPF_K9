<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserManagementController;

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
    Route::get('/owner/users', [UserManagementController::class, 'index'])
         ->name('owner.users.index');
    // Rute CREATE (Menampilkan form)
    Route::get('/owner/users/create', [UserManagementController::class, 'create'])
         ->name('owner.users.create');
         
    // Rute STORE (Menyimpan data dari form)
    Route::post('/owner/users', [UserManagementController::class, 'store'])
         ->name('owner.users.store');
    

    
    // Di Langkah 7, rute CRUD User akan kita taruh di sini
});

// Rute Awal, SEKARANG ke Halaman Home
Route::get('/', function () {
    return redirect()->route('home');
});
