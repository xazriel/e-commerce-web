<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Services\JneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    protected $jneService;

    public function __construct(JneService $jneService)
    {
        $this->jneService = $jneService;
    }

    public function index(Request $request)
    {
        $activeOrder = Order::where('user_id', Auth::id())
                            ->where('status', 'pending')
                            ->where('payment_deadline', '>', now())
                            ->first();

        if ($activeOrder) {
            return redirect()->route('checkout.waiting', $activeOrder->order_number)
                             ->with('info', 'Selesaikan pembayaran pesanan Anda sebelumnya.');
        }

        // ✅ Bedakan sumber item: buy_now atau cart biasa
        $isBuyNow = $request->query('mode') === 'buy_now';

        if ($isBuyNow) {
            $cart = session()->get('buy_now', []);
        } else {
            $cart = session()->get('cart', []);
        }

        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Keranjang belanja kosong.');
        }

        $totalAmount = 0;
        foreach ($cart as $variantId => $item) {
            $variant    = ProductVariant::find($variantId);
            $isPreorder = $item['is_preorder'] ?? false;

            if (!$variant || (! $isPreorder && $variant->stock < $item['quantity'])) {
                return redirect()->route('cart.index')->with('error', 'Stok tidak mencukupi.');
            }
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $user        = Auth::user();
        $addresses   = $user->addresses()->orderBy('is_default', 'desc')->get();
        $defaultAddr = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        return view('checkout.index', compact('cart', 'totalAmount', 'user', 'addresses', 'defaultAddr', 'isBuyNow'));
    }

    public function calculateShipping(Request $request)
    {
        $request->validate([
            'destination_id' => 'required',
            'weight'         => 'required|numeric',
        ]);

        $response = $this->jneService->getTariff($request->destination_id, $request->weight);

        if (isset($response['price'])) {
            return response()->json([
                'success' => true,
                'pricing' => collect($response['price'])->map(function ($item) {
                    return [
                        'courier_name'         => 'JNE',
                        'courier_service_name' => $item['service_display'],
                        'service_code'         => $item['service_code'],
                        'price'                => (int) $item['price'],
                        'duration'             => $item['etd_from'] . '-' . $item['etd_thru'] . ' ' . $item['times'],
                    ];
                }),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Tarif tidak ditemukan'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_name'    => 'required|string|max:255',
            'receiver_phone'   => 'required|string|max:20',
            'receiver_address' => 'required|string',
            'destination_id'   => 'required|string',
            'shipping_cost'    => 'required|numeric',
            'courier_name'     => 'required|string',
            'service_code'     => 'required|string',
            'payment_method'   => 'required|string',
            'receiver_city'    => 'nullable|string|max:100',
            'receiver_zip'     => 'nullable|string|max:10',
        ]);

        // ✅ Ambil dari session yang tepat sesuai mode
        $isBuyNow = $request->boolean('buy_now_mode');
        $cart     = $isBuyNow
            ? session()->get('buy_now', [])
            : session()->get('cart', []);

        if (empty($cart)) return redirect()->route('home');

        try {
            $order = DB::transaction(function () use ($request, $cart) {

                $totalAmount = array_sum(
                    array_map(fn($item) => $item['price'] * $item['quantity'], $cart)
                );
                $grandTotal = $totalAmount + (int) $request->shipping_cost;

                $order = Order::create([
                    'user_id'          => Auth::id(),
                    'total_amount'     => $totalAmount,
                    'shipping_cost'    => $request->shipping_cost,
                    'grand_total'      => $grandTotal,
                    'status'           => 'pending',
                    'payment_method'   => $request->payment_method,
                    'receiver_name'    => $request->receiver_name,
                    'receiver_phone'   => $request->receiver_phone,
                    'receiver_address' => $request->receiver_address,
                    'destination_id'   => $request->destination_id,
                    'courier_name'     => $request->courier_name,
                    'service_code'     => $request->service_code,
                    'receiver_city'    => $request->receiver_city,
                    'receiver_zip'     => $request->receiver_zip,
                    'payment_deadline' => now()->addHours(2),
                ]);

                foreach ($cart as $variantId => $details) {
                    $variant    = ProductVariant::with('product')
                                    ->where('id', $variantId)
                                    ->lockForUpdate()
                                    ->first();
                    $isPreorder = $details['is_preorder'] ?? false;

                    if (!$variant || (! $isPreorder && $variant->stock < $details['quantity'])) {
                        throw new \Exception("Maaf, stok {$details['name']} baru saja habis.");
                    }

                    if ($isPreorder) {
                        $order->is_preorder           = true;
                        $order->preorder_release_date = $variant->product->release_date;
                        $order->save();
                    }

                    $order->items()->create([
                        'product_id' => $variant->product_id,
                        'quantity'   => $details['quantity'],
                        'price'      => $details['price'],
                        'size'       => $variant->size,
                        'color'      => $variant->color,
                    ]);

                    if (! $isPreorder) {
                        $variant->decrement('stock', $details['quantity']);
                    }
                }

                $this->setupMidtrans();

                $itemDetails = [];
                foreach ($cart as $variantId => $item) {
                    $itemDetails[] = [
                        'id'       => (string) $variantId,
                        'price'    => (int) $item['price'],
                        'quantity' => (int) $item['quantity'],
                        'name'     => substr($item['name'], 0, 50),
                    ];
                }
                $itemDetails[] = [
                    'id'       => 'SHIPPING',
                    'price'    => (int) $request->shipping_cost,
                    'quantity' => 1,
                    'name'     => 'Ongkir - ' . $request->courier_name,
                ];

                $payload = [
                    'transaction_details' => [
                        'order_id'     => $order->order_number,
                        'gross_amount' => $grandTotal,
                    ],
                    'customer_details' => [
                        'first_name' => $request->receiver_name,
                        'phone'      => $request->receiver_phone,
                        'email'      => Auth::user()->email,
                    ],
                    'item_details' => $itemDetails,
                    'expiry'       => [
                        'unit'     => 'hours',
                        'duration' => 2,
                    ],
                ];

                $snapToken = Snap::getSnapToken($payload);
                $order->update(['payment_token' => $snapToken]);

                return $order;
            });

            // ✅ Hapus session yang tepat
            if ($isBuyNow) {
                session()->forget('buy_now');
            } else {
                session()->forget('cart');
            }

            return redirect()->route('checkout.waiting', $order->order_number);

        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function waiting($order_number)
    {
        $order = Order::where('order_number', $order_number)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        if ($order->status === 'success') {
            return redirect()->route('checkout.success', $order->order_number);
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('home')->with('error', 'Pesanan telah dibatalkan.');
        }

        if (now()->gt($order->payment_deadline)) {
            $this->restoreStockAndCancel($order);
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis.');
        }

        $clientKey = config('midtrans.client_key');

        return view('checkout.waiting', compact('order', 'clientKey'));
    }

    public function success($order_number)
    {
        $order = Order::where('order_number', $order_number)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    public function cancel($order_number)
    {
        $order = Order::where('order_number', $order_number)
                      ->where('user_id', Auth::id())
                      ->where('status', 'pending')
                      ->firstOrFail();

        $this->restoreStockAndCancel($order);

        return redirect()->route('home')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function midtransCallback(Request $request)
    {
        $this->setupMidtrans();

        try {
            $notif       = new Notification();
            $orderId     = $notif->order_id;
            $transaction = $notif->transaction_status;
            $fraud       = $notif->fraud_status;

            Log::info('Midtrans callback received', [
                'order_id'    => $orderId,
                'transaction' => $transaction,
                'fraud'       => $fraud,
            ]);

            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found, ignored'], 200);
            }

            if ($transaction === 'capture') {
                $status = ($fraud === 'challenge') ? 'pending' : 'success';
            } elseif ($transaction === 'settlement') {
                $status = 'success';
            } elseif (in_array($transaction, ['cancel', 'deny', 'expire'])) {
                $status = 'cancelled';
            } else {
                $status = 'pending';
            }

            if ($status === 'success' && $order->status !== 'success') {
                if (! $order->is_preorder) {
                    $this->generateAwb($order);
                }
                $order->update(['status' => 'success']);
            } elseif ($status === 'cancelled' && $order->status !== 'cancelled') {
                $this->restoreStockAndCancel($order);
            } else {
                $order->update(['status' => $status]);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'OK']);
    }

    // ── Private helpers ───────────────────────────────────────

    private function setupMidtrans(): void
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }

    private function parseServiceCode(string $raw): string
    {
        preg_match('/^([A-Z]+)/', strtoupper($raw), $m);
        return $m[1] ?? 'REG';
    }

    private function generateAwb(Order $order): void
    {
        try {
            $order->loadMissing('items.product');

            $goodsDesc = $order->items
                ->map(fn($item) => $item->product->name ?? 'Produk Farhana')
                ->implode(', ');

            $jneData = [
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
                'OLSHOP_ORIG'           => config('jne.origin_code'),
                'OLSHOP_DEST'           => $order->destination_id,
                'OLSHOP_SERVICE'        => $this->parseServiceCode($order->service_code ?? 'REG'),
                'OLSHOP_COD_FLAG'       => 'N',
                'OLSHOP_COD_AMOUNT'     => 0,
            ];

            Log::info('JNE AWB data dikirim', $jneData);

            $res = $this->jneService->createAirwaybill($jneData);

            Log::info('JNE AWB response', $res);

            if (isset($res['detail'][0]['cnote_no']) && ($res['detail'][0]['status'] ?? '') === 'sukses') {
                $order->update(['tracking_number' => $res['detail'][0]['cnote_no']]);
                Log::info('JNE AWB berhasil', ['cnote_no' => $res['detail'][0]['cnote_no']]);
            } else {
                Log::warning('JNE AWB tidak dibuat', [
                    'order'  => $order->order_number,
                    'reason' => $res['detail'][0]['reason'] ?? json_encode($res),
                ]);
            }

        } catch (\Exception $e) {
            Log::warning('JNE AWB gagal: ' . $e->getMessage(), ['order' => $order->order_number]);
        }
    }

    private function restoreStockAndCancel(Order $order): void
    {
        if ($order->status === 'cancelled') return;

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);

            if ($order->is_preorder) return;

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