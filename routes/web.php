<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\JneController;
use App\Http\Controllers\Admin\SizeGuideController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.details');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',               [CartController::class, 'index'])->name('index');
    Route::post('/add/{id}',      [CartController::class, 'add'])->name('add');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::patch('/update/{id}',  [CartController::class, 'update'])->name('update');
});

Route::post('/midtrans/callback', [CheckoutController::class, 'midtransCallback'])
    ->name('midtrans.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        return view('dashboard', compact('orders'));
    })->name('dashboard');

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/address-update', [AddressController::class, 'update'])->name('profile.address.update');

    Route::get('/profile/orders',                        [ProfileController::class, 'index'])->name('profile.orders');
    Route::get('/profile/orders/{order_number}',         [ProfileController::class, 'orderDetail'])->name('profile.orders.detail');

    // ← Route tracking yang dipanggil dashboard (satu route, satu nama)
    Route::get('/profile/track/{awb}', [ProfileController::class, 'trackResi'])->name('tracking.resi');
    Route::get('/profile/track-detail/{awb}', [ProfileController::class, 'trackResi'])
    ->name('tracking.show');

    Route::prefix('profile/addresses')->name('address.')->group(function () {
    Route::get('/',             [AddressController::class, 'index'])->name('index');
    Route::get('/create',       [AddressController::class, 'create'])->name('create');
    Route::post('/',            [AddressController::class, 'store'])->name('store');
    Route::get('/{id}/edit',    [AddressController::class, 'edit'])->name('edit');
    Route::put('/{id}',         [AddressController::class, 'update'])->name('update');  
    Route::post('/{id}/select', [AddressController::class, 'select'])->name('select');
    Route::delete('/{id}',      [AddressController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/',                        [CheckoutController::class, 'index'])->name('index');
        Route::post('/store',                  [CheckoutController::class, 'store'])->name('store');
        Route::get('/waiting/{order_number}',  [CheckoutController::class, 'waiting'])->name('waiting');
        Route::get('/success/{order_number}',  [CheckoutController::class, 'success'])->name('success');
        Route::patch('/cancel/{order_number}', [CheckoutController::class, 'cancel'])->name('cancel');
    });

    Route::get('/api/locations',      [JneController::class,      'searchLocation'])->name('api.locations');
    Route::post('/api/shipping-cost', [CheckoutController::class, 'calculateShipping'])->name('api.shipping');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories',  CategoryController::class);
    Route::resource('products',    ProductController::class);
    Route::resource('sliders',     SliderController::class);
    Route::resource('size-guides', SizeGuideController::class);

    Route::delete('/product-images/{id}',            [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::patch('/product-images/{id}/set-primary', [ProductController::class, 'setPrimary'])->name('products.images.setPrimary');

    Route::name('admin.')->group(function () {
        Route::get('/orders',                         [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order_number}',          [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order_number}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});

/*
|--------------------------------------------------------------------------
| DEV ONLY — Hapus sebelum production!
|--------------------------------------------------------------------------
*/
Route::get('/dev/simulate-payment/{order_number}', function ($order_number) {
    $order = \App\Models\Order::where('order_number', $order_number)
                ->with('items.product')
                ->firstOrFail();

    if ($order->tracking_number) {
        return redirect()->route('checkout.success', $order->order_number);
    }

    $order->update(['status' => 'success']);

    $jne       = app(\App\Services\JneService::class);
    $goodsDesc = $order->items
        ->map(fn($i) => optional($i->product)->name ?? 'Produk Farhana')
        ->implode(', ');

    preg_match('/^([A-Z]+)/', strtoupper($order->service_code ?? 'REG'), $m);
    $serviceCode = $m[1] ?? 'REG';
    $dest        = $order->destination_id;
    $origin      = config('jne.origin_code', 'DPK10000');

    if (empty($dest) || $dest === $origin) {
        return redirect()->route('checkout.success', $order->order_number)
            ->with('warning', 'AWB tidak dibuat: destination tidak valid.');
    }

    try {
        $res    = $jne->createAirwaybill([
            'OLSHOP_BRANCH'         => config('jne.branch'),
            'OLSHOP_CUST'           => config('jne.cust_no'),
            'OLSHOP_ORDERID'        => $order->order_number,
            'OLSHOP_SHIPPER_NAME'   => 'FARHANA OFFICIAL',
            'OLSHOP_SHIPPER_ADDR1'  => 'Jl. Margonda Raya No. 1',
            'OLSHOP_SHIPPER_ADDR2'  => '-',
            'OLSHOP_SHIPPER_CITY'   => 'DEPOK',
            'OLSHOP_SHIPPER_ZIP'    => '16411',
            'OLSHOP_SHIPPER_PHONE'  => '08123456789',
            'OLSHOP_RECEIVER_NAME'  => $order->receiver_name,
            'OLSHOP_RECEIVER_ADDR1' => $order->receiver_address,
            'OLSHOP_RECEIVER_ADDR2' => '-',
            'OLSHOP_RECEIVER_CITY'  => $order->receiver_city  ?? '-',
            'OLSHOP_RECEIVER_ZIP'   => $order->receiver_zip   ?? '00000',
            'OLSHOP_RECEIVER_PHONE' => $order->receiver_phone,
            'OLSHOP_QTY'            => $order->items->sum('quantity'),
            'OLSHOP_WEIGHT'         => 1,
            'OLSHOP_GOODSDESC'      => substr($goodsDesc, 0, 60),
            'OLSHOP_GOODSVALUE'     => (int) $order->total_amount,
            'OLSHOP_GOODSTYPE'      => '2',
            'OLSHOP_INST'           => '',
            'OLSHOP_INS_FLAG'       => 'N',
            'OLSHOP_ORIG'           => $origin,
            'OLSHOP_DEST'           => $dest,
            'OLSHOP_SERVICE'        => $serviceCode,
            'OLSHOP_COD_FLAG'       => 'N',
            'OLSHOP_COD_AMOUNT'     => 0,
        ]);
        $status = trim($res['detail'][0]['status'] ?? '', "' ");
        if (isset($res['detail'][0]['cnote_no']) && strtolower($status) === 'sukses') {
            $order->update(['tracking_number' => $res['detail'][0]['cnote_no']]);
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::warning('DEV AWB gagal: ' . $e->getMessage());
    }

    return redirect()->route('checkout.success', $order->order_number);
});

require __DIR__.'/auth.php';