<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MenuManagementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderManagementController; // Pastikan ini ada
use App\Http\Controllers\DashboardController;

Route::get('/home', [PageController::class, 'home'])->name('home');
Route::get('/menu', [PageController::class, 'menu'])->name('menu');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
   Route::get('/owner', [DashboardController::class, 'index'])->name('owner');
    Route::get('/owner/menu', [MenuManagementController::class, 'index'])->name('owner.menu.index');
    Route::get('/owner/menu/create', [MenuManagementController::class, 'create'])->name('owner.menu.create');
    Route::post('/owner/menu', [MenuManagementController::class, 'store'])->name('owner.menu.store');
    Route::get('/owner/menu/{menuItem}/edit', [MenuManagementController::class, 'edit'])->name('owner.menu.edit');
    Route::put('/owner/menu/{menuItem}', [MenuManagementController::class, 'update'])->name('owner.menu.update');
    Route::delete('/owner/menu/{menuItem}', [MenuManagementController::class, 'destroy'])->name('owner.menu.destroy');
});

Route::middleware(['auth', 'role:owner,admin'])->group(function () {
    Route::get('/management/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/management/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/management/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/management/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/management/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/management/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    Route::get('/management/orders', [OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/management/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
    Route::patch('/management/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.updateStatus');
});

// == GRUP KHUSUS MEMBER (USER LOGIN) ==
Route::middleware(['auth'])->group(function () {
    // Keranjang Belanja
    Route::post('/order/add/{id}', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
    Route::delete('/remove-from-cart', [OrderController::class, 'remove'])->name('cart.remove');
    
    // Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::post('/check-shipping', [OrderController::class, 'checkShipping'])->name('check.shipping');

    // Riwayat & Upload Bukti
    Route::get('/my-orders', [OrderController::class, 'history'])->name('orders.history');
    Route::post('/my-orders/{order}/upload-proof', [OrderController::class, 'uploadProof'])->name('orders.uploadProof');

    // REVIEW (BARU)
    Route::get('/my-orders/{order}/review', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/my-orders/{order}/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/', function () { return redirect()->route('home'); });

Route::get('/auth/redirect-google', [AuthController::class, 'redirectToGoogle'])->name('redirect.google');
Route::get('/oauthcallback', [AuthController::class, 'handleGoogleCallback']);

// // Rute Tes Khusus
// Route::get('/owner', function () {
//     return view('owner.dashboard');
// });