<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function index()
    {
        // 1. CEK: Jika user punya order pending yang belum expired, paksa kembali ke waiting page
        $activeOrder = Order::where('user_id', Auth::id())
                            ->where('status', 'pending')
                            ->where('payment_deadline', '>', now())
                            ->first();

        if ($activeOrder) {
            return redirect()->route('checkout.waiting', $activeOrder->order_number)
                             ->with('info', 'Selesaikan pembayaran pesanan Anda sebelumnya.');
        }

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Keranjang belanja kosong.');
        }

        $totalAmount = 0;
        foreach($cart as $variantId => $item) {
            $variant = ProductVariant::find($variantId);
            if (!$variant || $variant->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', "Stok tidak mencukupi.");
            }
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $user = Auth::user();
        return view('checkout.index', compact('cart', 'totalAmount', 'user'));
    }

    /**
     * API: Mencari Lokasi (Data Statis/Dummy)
     */
    public function searchLocation(Request $request)
    {
        $results = [
            ['id' => 'LOC001', 'text' => 'Pancoran, Jakarta Selatan, DKI Jakarta'],
            ['id' => 'LOC002', 'text' => 'Senayan, Jakarta Pusat, DKI Jakarta'],
            ['id' => 'LOC003', 'text' => 'Cinere, Depok, Jawa Barat'],
            ['id' => 'LOC004', 'text' => 'Cengkareng, Jakarta Barat, DKI Jakarta'],
        ];
        return response()->json(['results' => $results]);
    }

    /**
     * API: Menghitung Biaya Ongkir (Data Statis/Dummy)
     */
    public function calculateShipping(Request $request)
    {
        return response()->json([
            'success' => true,
            'pricing' => [
                ['courier_name' => 'JNE', 'courier_service_name' => 'Reguler', 'price' => 12000, 'duration' => '1-2 Days'],
                ['courier_name' => 'J&T', 'courier_service_name' => 'EZ', 'price' => 10000, 'duration' => '2-3 Days'],
                ['courier_name' => 'SiCepat', 'courier_service_name' => 'BEST', 'price' => 15000, 'duration' => '1 Day'],
            ]
        ]);
    }

    /**
     * Memproses pesanan, potong stok, dan set timer 2 menit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_name'    => 'required|string|max:255',
            'receiver_phone'   => 'required|string|max:20',
            'receiver_address' => 'required|string',
            'destination_id'   => 'required',
            'shipping_cost'    => 'required|numeric',
            'courier_name'     => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('home');

        try {
            return DB::transaction(function () use ($request, $cart) {
                
                $totalAmount = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

                // Buat Order dengan Deadline 2 Menit ke depan
                $order = Order::create([
                    'order_number'     => 'INV-' . strtoupper(uniqid()),
                    'user_id'          => Auth::id(),
                    'total_amount'     => $totalAmount,
                    'shipping_cost'    => $request->shipping_cost,
                    'grand_total'      => $totalAmount + $request->shipping_cost,
                    'status'           => 'pending',
                    'payment_method'   => 'QRIS',
                    'receiver_name'    => $request->receiver_name,
                    'receiver_phone'   => $request->receiver_phone,
                    'receiver_address' => $request->receiver_address,
                    'destination_id'   => $request->destination_id,
                    'courier_name'     => $request->courier_name,
                    'payment_deadline' => now()->addMinutes(2), 
                ]);

                foreach ($cart as $variantId => $details) {
                    $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->first();

                    if (!$variant || $variant->stock < $details['quantity']) {
                        throw new \Exception("Maaf, stok {$details['name']} baru saja habis.");
                    }

                    $order->items()->create([
                        'product_id' => $variant->product_id,
                        'quantity'   => $details['quantity'],
                        'price'      => $details['price'],
                        'size'       => $variant->size,
                        'color'      => $variant->color,
                    ]);

                    $variant->decrement('stock', $details['quantity']);
                }

                session()->forget('cart');
                return redirect()->route('checkout.waiting', $order->order_number);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Halaman instruksi pembayaran & Cek kadaluwarsa stok.
     */
    public function waiting($order_number)
    {
        $order = Order::where('order_number', $order_number)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        // Jika user memaksa buka halaman waiting tapi status sudah success/cancelled
        if ($order->status !== 'pending') {
            return redirect()->route('home');
        }

        // Jika waktu habis saat halaman diakses, kembalikan stok
        if (now()->gt($order->payment_deadline)) {
            $this->restoreStockAndCancel($order);
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis.');
        }

        return view('checkout.waiting', compact('order'));
    }

    /**
     * Simulasi Pembayaran Berhasil.
     */
    public function simulatePay($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if (now()->gt($order->payment_deadline)) {
            $this->restoreStockAndCancel($order);
            return redirect()->route('home')->with('error', 'Maaf, waktu pembayaran sudah habis.');
        }

        if ($order->status !== 'pending') {
            return redirect()->route('home');
        }

        $order->update(['status' => 'success']);
        return redirect()->route('home')->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    /**
     * Helper: Kembalikan stok jika order batal/expired.
     */
    private function restoreStockAndCancel($order)
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);
            foreach ($order->items as $item) {
                $variant = ProductVariant::where('product_id', $item->product_id)
                    ->where('color', $item->color)
                    ->where('size', $item->size)
                    ->first();
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            }
        });
    }
}