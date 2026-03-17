<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.details');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); 
    Route::post('/add/{id}', [CartController::class, 'add'])->name('add');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Customer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Logic
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- TAMBAHKAN ROUTE ALAMAT DI SINI ---
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');

    // --- CHECKOUT ROUTES (UPDATED WITH API) ---
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/store', [CheckoutController::class, 'store'])->name('store');
        Route::get('/waiting/{order_number}', [CheckoutController::class, 'waiting'])->name('waiting');
        Route::post('/simulate-pay/{id}', [CheckoutController::class, 'simulatePay'])->name('simulatePay');
    });

    // --- API ROUTES FOR SHIPPING (KOMERCE) ---
    Route::get('/api/locations', [CheckoutController::class, 'searchLocation'])->name('api.locations');
    Route::post('/api/shipping-cost', [CheckoutController::class, 'calculateShipping'])->name('api.shipping');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('sliders', SliderController::class);

    Route::delete('/product-images/{id}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::patch('/product-images/{id}/set-primary', [ProductController::class, 'setPrimary'])->name('products.images.setPrimary');
});

require __DIR__.'/auth.php';