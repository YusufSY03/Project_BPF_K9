<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MenuManagementController;
use App\Http\Controllers\OrderController; // <-- Pastikan ini ada

// Rute Halaman Publik
Route::get('/home', [PageController::class, 'home'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Rute Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Rute Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard.adminDashboard');
    })->name('admin');
});

// Rute Owner (Termasuk Manajemen Menu)
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner', function () {
        return view('dashboard.ownerDashboard');
    })->name('owner');

    // Manajemen Menu
    Route::get('/owner/menu', [MenuManagementController::class, 'index'])->name('owner.menu.index');
    Route::get('/owner/menu/create', [MenuManagementController::class, 'create'])->name('owner.menu.create');
    Route::post('/owner/menu', [MenuManagementController::class, 'store'])->name('owner.menu.store');
    Route::get('/owner/menu/{menuItem}/edit', [MenuManagementController::class, 'edit'])->name('owner.menu.edit');
    Route::put('/owner/menu/{menuItem}', [MenuManagementController::class, 'update'])->name('owner.menu.update');
    Route::delete('/owner/menu/{menuItem}', [MenuManagementController::class, 'destroy'])->name('owner.menu.destroy');
});

// Rute Manajemen User (Admin & Owner)
Route::middleware(['auth', 'role:owner,admin'])->group(function () {
    Route::get('/management/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/management/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/management/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/management/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/management/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/management/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});
// -- TAMBAHAN BARU: MANAJEMEN ORDER --
    Route::get('/management/orders', [App\Http\Controllers\OrderManagementController::class, 'index'])
         ->name('orders.index');
// == GRUP KHUSUS MEMBER (USER LOGIN) ==
Route::middleware(['auth'])->group(function () {
    // Keranjang Belanja
    Route::post('/order/add/{id}', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
    Route::delete('/remove-from-cart', [OrderController::class, 'remove'])->name('cart.remove');
    
    // Checkout (BARU)
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
});

// Redirect Awal
Route::get('/', function () {
    return redirect()->route('home');
});