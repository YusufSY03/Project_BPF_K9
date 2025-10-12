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


// Rute Halaman Admin yang Dilindungi
Route::get('/admin', function () {
    if (session('role') === 'admin') {
        return view('dashboard.adminDashboard');
    }
    return redirect()->route('login')->withErrors(['auth' => 'Silakan login sebagai admin.']);
})->name('admin');
Route::get('/owner', function () {
    if (session('role') === 'owner') {
        return view('ownerDashboard');
    }
    return redirect()->route('login')->withErrors(['auth' => 'Silakan login sebagai owner.']);
})->name('owner');


// Rute Awal, Redirect ke Halaman Login
Route::get('/', function () {
    return redirect()->route('login');
});
