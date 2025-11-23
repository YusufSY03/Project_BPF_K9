<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserManagementController; // <-- Pastikan ini ada

// Rute Halaman Publik (Nyamaw)
Route::get('/home', [PageController::class, 'home'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/about', [PageController::class, 'about'])->name('about');


// Rute Autentikasi (Login, Register, Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');


// Rute Halaman Admin (HANYA DASBOR)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard.adminDashboard');
    })->name('admin');
});

// Rute Halaman Owner (HANYA DASBOR)
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner', function () {
        return view('dashboard.ownerDashboard');
    })->name('owner');
});

// == GRUP BARU UNTUK MANAJEMEN USER ==
// Bisa diakses oleh 'owner' DAN 'admin'
Route::middleware(['auth', 'role:owner,admin'])->group(function () {
    
    // READ
    Route::get('/management/users', [UserManagementController::class, 'index'])
         ->name('users.index');
         
    // CREATE
    Route::get('/management/users/create', [UserManagementController::class, 'create'])
         ->name('users.create');
         
    // STORE
    Route::post('/management/users', [UserManagementController::class, 'store'])
         ->name('users.store');
         
    // EDIT
    Route::get('/management/users/{user}/edit', [UserManagementController::class, 'edit'])
         ->name('users.edit');
         
    // UPDATE
    Route::put('/management/users/{user}', [UserManagementController::class, 'update'])
         ->name('users.update');
         
    // DELETE
    Route::delete('/management/users/{user}', [UserManagementController::class, 'destroy'])
         ->name('users.destroy');
});


// Rute Awal, SEKARANG ke Halaman Home
Route::get('/', function () {
    return redirect()->route('home');
});