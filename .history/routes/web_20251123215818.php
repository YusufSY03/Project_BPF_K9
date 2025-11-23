<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MenuManagementController;

// Rute Halaman Publik (Nyamaw)
Route::get('/home', [PageController::class, 'home'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/about', [PageController::class, 'about'])->name('about');

// GRUP KHUSUS MEMBER (USER LOGIN)
// Middleware 'auth' artinya: "Cek dulu, kalau belum login, tendang ke halaman login"
Route::middleware(['auth'])->group(function () {
    
    // Rute untuk proses pemesanan
    Route::post('/order/add/{id}', [App\Http\Controllers\OrderController::class, 'addToCart'])
         ->name('cart.add');
         
    // Nanti kita tambah rute 'checkout', 'history', dll di sini
});
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

    // Rute READ (yang sudah ada)
    Route::get('/owner/menu', [MenuManagementController::class, 'index'])
         ->name('owner.menu.index');

    // Rute CREATE (yang sudah ada)
    Route::get('/owner/menu/create', [MenuManagementController::class, 'create'])
         ->name('owner.menu.create');

    // Rute STORE (yang sudah ada)
    Route::post('/owner/menu', [MenuManagementController::class, 'store'])
         ->name('owner.menu.store');

    // -- TAMBAHKAN 3 RUTE BARU DI BAWAH INI --

    // Rute EDIT (Menampilkan form edit)
    Route::get('/owner/menu/{menuItem}/edit', [MenuManagementController::class, 'edit'])
         ->name('owner.menu.edit');

    // Rute UPDATE (Menyimpan perubahan)
    Route::put('/owner/menu/{menuItem}', [MenuManagementController::class, 'update'])
         ->name('owner.menu.update');

    // Rute DELETE (Menghapus data)
    Route::delete('/owner/menu/{menuItem}', [MenuManagementController::class, 'destroy'])
         ->name('owner.menu.destroy');
});

// == GRUP UNTUK MANAJEMEN USER (Admin & Owner) ==
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
