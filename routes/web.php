<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SliderController; // Import Controller Slider
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Jalur Publik (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/
// Gunakan HomeController agar logika data sliders/produk terpusat di satu tempat
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.details');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

Route::get('/cart', function () {
    $cart = session()->get('cart', []);
    return view('cart.index', compact('cart'));
})->name('cart.index');

Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');


/*
|--------------------------------------------------------------------------
| Jalur Customer / User Terautentikasi (Breeze Dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Jalur Khusus Admin (Prefix: /admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // Dashboard Utama Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Route Resource untuk CRUD Kategori, Produk, dan Slider
    // Nama route akan otomatis: categories.index, products.index, sliders.index (TANPA admin.)
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('sliders', SliderController::class);

    // Manajemen Spesifik Galeri Produk
    Route::delete('/product-images/{id}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::patch('/product-images/{id}/set-primary', [ProductController::class, 'setPrimary'])->name('products.images.setPrimary');
    
});

require __DIR__.'/auth.php';