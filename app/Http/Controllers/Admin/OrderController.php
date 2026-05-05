<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index()
    {
        // Mengambil semua order dari semua user, urutkan dari yang terbaru
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($order_number)
    {
        $order = Order::with(['user', 'items.product'])
            ->where('order_number', $order_number)
            ->firstOrFail();
            
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100' // Untuk nomor resi JNE/Biteship
        ]);

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number
        ]);

        return back()->with('success', 'Order status updated successfully!');
    }

    public function trackResi($resi)
{
    // Menggunakan URL Sandbox JNE
    $url = "https://apiv2.jne.co.id:10202/tracing/api/list/v1/cnote/" . $resi;

    // HTTP Request harus menggunakan asForm() untuk x-www-form-urlencoded
    $response = Http::asForm()->post($url, [
        'username' => 'TESTAPI',
        'api_key'  => '25c898a9faea1a100859ecd9ef674548'
    ]);

    $data = $response->json();

    // 1. Tangani Error dari API JNE (misal: Resi tidak ditemukan)
    if (isset($data['status']) && $data['status'] === false) {
        return response()->json([
            'success' => false, 
            'message' => $data['error'] // Akan berisi "Cnote No. Not Found." dll
        ]);
    }

    // 2. Tangani jika response sukses
    if (isset($data['cnote'])) {
        return response()->json([
            'success' => true,
            'status'  => $data['cnote']['pod_status'], // Contoh: DELIVERED
            'history' => $data['history'] // Array riwayat perjalanan
        ]);
    }

    // 3. Tangani jika server JNE down / response tidak terduga
    return response()->json([
        'success' => false, 
        'message' => 'Terjadi kesalahan pada server JNE.'
    ], 500);
}
}