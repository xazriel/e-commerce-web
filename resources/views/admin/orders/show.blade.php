<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="mb-8 flex justify-between items-center">
            <a href="{{ route('admin.orders.index') }}" class="text-[10px] uppercase tracking-widest text-gray-400 hover:text-black">
                ← Back to All Orders
            </a>
            <h1 class="text-xl font-bold uppercase tracking-widest">Process Order #{{ $order->order_number }}</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                {{-- Detail Barang --}}
                <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
                    <h3 class="text-[11px] font-bold uppercase tracking-widest mb-4 border-b pb-2">Items Purchased</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex justify-between items-center text-[12px]">
                            <div>
                                <p class="font-bold text-black uppercase">{{ $item->product->name }}</p>
                                <p class="text-gray-400">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="font-medium">IDR {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                        <div class="pt-4 border-t border-gray-100 flex justify-between font-bold">
                            <span class="text-[10px] uppercase tracking-widest">Total Amount</span>
                            <span class="italic text-black">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Update Status Form --}}
                <div class="bg-black text-white rounded-xl p-8 shadow-lg">
                    <h3 class="text-[11px] font-bold uppercase tracking-[0.3em] mb-6 text-gray-400">Update Order Status</h3>
                    <form action="{{ route('admin.orders.updateStatus', $order->order_number) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="text-[9px] uppercase tracking-widest text-gray-500 block mb-2">Order Status</label>
                            <select name="status" class="w-full bg-zinc-900 border-zinc-800 text-white rounded-lg text-[12px] focus:ring-white focus:border-white capitalize">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid (Confirmed)</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-[9px] uppercase tracking-widest text-gray-500 block mb-2">Tracking Number (Resi)</label>
                            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Contoh: JNE12345678"
                                class="w-full bg-zinc-900 border-zinc-800 text-white rounded-lg text-[12px] focus:ring-white focus:border-white">
                        </div>

                        <button type="submit" class="w-full bg-white text-black py-3 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-200 transition">
                            Update Transaction
                        </button>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Customer Info --}}
            <div class="space-y-6 text-[11px]">
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                    <h4 class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-4">Customer Info</h4>
                    <p class="font-bold text-black uppercase mb-1">{{ $order->user->name }}</p>
                    <p class="text-gray-500">{{ $order->user->email }}</p>
                    <p class="text-gray-500">{{ $order->user->phone }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                    <h4 class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-4">Shipping Address</h4>
                    <p class="leading-relaxed text-gray-600 italic">
                        {{ $order->user->address }}<br>
                        <span class="font-bold text-black uppercase not-italic">{{ $order->user->destination_name }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>