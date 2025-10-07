<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


Route::get('/', function () {
    return view('user.home');
})->name('user.home');
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');



Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::post('/login', [LoginController::class, 'handleLogin'])->name('login')->middleware('guest');


Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth','admin'])->prefix('/master-data')->as('master-data.')->group(function (){
        Route::prefix('kategori')->as('kategori.')->controller(KategoriController::class)->group(function (){
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/{id}/destroy', 'destroy')->name('destroy');
        });

        Route::prefix('product')->as('product.')->controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/{id}/destroy', 'destroy')->name('destroy');
        });
    });
    
    Route::prefix('users')->as('users.')->controller(UserController::class)->group(function(){
         Route::get('/', 'index')->name('index');
         Route::post('/', 'store')->name('store');
         Route::delete('/{id}/destroy', 'destroy')->name('destroy');
         Route::post('/ganti-password', 'gantiPassword')->name('ganti-password');
         Route::post('/reset-password', 'resetPassword')->name('reset-password');
    });

});
