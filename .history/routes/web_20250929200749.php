<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController; 

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', [PageController::class, 'showAboutPage'])->name('about');

Route::get('/', [HomeController::class, 'index']);
Route::get('/produk/{id_produk?}', [HomeController::class, 'show']);