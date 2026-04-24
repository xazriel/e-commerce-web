<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\SizeGuideController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
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
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
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

        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', compact('orders'));
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // UPDATED: Route update alamat di dashboard (Tanpa {id} untuk keamanan & kemudahan Blade)
    Route::patch('/profile/address-update', [AddressController::class, 'update'])->name('profile.address.update');
    
    // Order Routes (Customer Side)
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{order_number}', [ProfileController::class, 'orderDetail'])->name('profile.orders.detail');
    
    // Alamat CRUD (Klasik)
    Route::prefix('profile/addresses')->name('address.')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('index'); 
        Route::get('/create', [AddressController::class, 'create'])->name('create');
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::post('/{id}/select', [AddressController::class, 'select'])->name('select');
        Route::delete('/{id}', [AddressController::class, 'destroy'])->name('destroy');
    });

    // Checkout Routes
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/store', [CheckoutController::class, 'store'])->name('store');
        Route::get('/waiting/{order_number}', [CheckoutController::class, 'waiting'])->name('waiting');
        Route::post('/simulate-pay/{id}', [CheckoutController::class, 'simulatePay'])->name('simulatePay');
    });

    // API Routes for Shipping
    Route::get('/api/locations', [CheckoutController::class, 'searchLocation'])->name('api.locations');
    Route::post('/api/shipping-cost', [CheckoutController::class, 'calculateShipping'])->name('api.shipping');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Resources
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('size-guides', SizeGuideController::class);

    // Product Image Management
    Route::delete('/product-images/{id}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::patch('/product-images/{id}/set-primary', [ProductController::class, 'setPrimary'])->name('products.images.setPrimary');
    
    // Order Management
    Route::name('admin.')->group(function () {
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order_number}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order_number}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});

require __DIR__.'/auth.php';