<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
}