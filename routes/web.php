<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

// AUTH
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// ADMIN
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;

// USER
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\MessageController;

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK TAMU (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'handleLogin'])->name('login.handle');

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK USER YANG SUDAH LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Redirect berdasarkan role
    Route::get('/redirect-by-role', function (): RedirectResponse {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.home');
    })->name('redirect.by.role');

    /*
    |--------------------------------------------------------------------------
    | ROUTE KHUSUS ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->prefix('admin')->as('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kategori
        Route::prefix('kategori')->as('kategori.')->controller(KategoriController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/{id}/destroy', 'destroy')->name('destroy');
        });

        // Produk
        Route::prefix('product')->as('product.')->controller(AdminProductController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/{id}/destroy', 'destroy')->name('destroy');
        });

        // User
        Route::prefix('users')->as('users.')->controller(AdminUserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/{id}/destroy', 'destroy')->name('destroy');
            Route::post('/ganti-password', 'gantiPassword')->name('ganti-password');
            Route::post('/reset-password', 'resetPassword')->name('reset-password');
        });

        // Order
        Route::prefix('orders')->as('orders.')->controller(AdminOrderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/{id}/status', 'updateStatus')->name('updateStatus');
        });

        Route::prefix('messages')->as('messages.')->controller(AdminMessageController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::delete('/{id}/destroy', 'destroy')->name('destroy');
            Route::post('/{id}/read', 'markAsRead')->name('markAsRead');
            Route::post('/mark-all-read', 'markAllAsRead')->name('markAllRead');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ROUTE KHUSUS USER
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->as('user.')->group(function () {

        // Halaman utama
        Route::get('/home', [App\Http\Controllers\User\HomeController::class, 'index'])->name('home');
        Route::view('/about', 'user.about')->name('about');
        Route::view('/contact', 'user.contact')->name('contact');

        // Produk
        Route::get('/products', [UserProductController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [UserProductController::class, 'show'])->name('products.show');

        // Keranjang
        Route::prefix('cart')->as('cart.')->controller(CartController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/add/{id}', 'add')->name('add');
            Route::post('/remove/{id}', 'remove')->name('remove');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('/checkout', 'checkout')->name('checkout');
            Route::post('/cart/clear',  'clear')->name('clear');
        });

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // Pesanan 
        Route::prefix('orders')->as('orders.')->controller(UserOrderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/pay/{id}', 'pay')->name('pay');
            Route::post('/confirm/{id}', 'confirm')->name('confirm');
              // Payment routes
            Route::get('/{order}/payment/modal', 'showPaymentModal')->name('payment.modal');
            Route::post('/{order}/payment/process', 'processPayment')->name('payment.process');
            Route::post('/{order}/payment/cod', 'processCodPayment')->name('payment.cod');
        });
        Route::get('/contact', function () {
        return view('user.contact');
        })->name('contact.index');
        
        Route::post('/contact', [MessageController::class, 'store'])->name('contact.store');
    });

});
