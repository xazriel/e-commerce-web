<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-10">
        
        {{-- Header: Back Button & Status --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-[10px] uppercase tracking-[0.2em] text-gray-400 hover:text-black transition group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                Back to Dashboard
            </a>
            <div class="flex items-center gap-3">
                <span class="text-[9px] uppercase tracking-[0.3em] text-gray-400 font-bold">Order Status</span>
                <span class="px-5 py-1.5 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full 
                    {{ $order->status == 'paid' ? 'bg-black text-white' : 'bg-gray-100 text-gray-500' }}">
                    {{ $order->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Kolom Kiri: Detail Produk --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-50">
                        <h3 class="text-[11px] font-bold uppercase tracking-[0.3em] text-gray-800">Items Ordered</h3>
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        <div class="p-6 flex items-center gap-6">
                            {{-- Image Placeholder/Product Image --}}
                            <div class="w-20 h-20 bg-gray-50 rounded-xl flex-shrink-0 overflow-hidden border border-gray-100">
                                @if($item->product && $item->product->images->where('is_primary', 1)->first())
                                    <img src="{{ asset('storage/' . $item->product->images->where('is_primary', 1)->first()->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[8px] text-gray-300 italic">No Image</div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <h4 class="text-[12px] font-bold uppercase tracking-wider text-black mb-1">
                                    {{ $item->product->name ?? 'Product Unavailable' }}
                                </h4>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest">
                                    Qty: {{ $item->quantity }} × IDR {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-[12px] font-bold italic text-black">
                                    IDR {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Summary Total --}}
                    <div class="p-6 bg-gray-50 border-t border-gray-100 space-y-3">
                        <div class="flex justify-between text-[10px] uppercase tracking-widest text-gray-500">
                            <span>Subtotal</span>
                            <span>IDR {{ number_format($order->total_amount - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[10px] uppercase tracking-widest text-gray-500">
                            <span>Shipping Cost</span>
                            <span>IDR {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[14px] font-bold uppercase tracking-[0.2em] text-black pt-3 border-t border-gray-200">
                            <span>Total Amount</span>
                            <span>IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Info Pengiriman & Pembayaran --}}
            <div class="space-y-6">
                {{-- Info Pesanan --}}
                <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 mb-4">Order Info</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[9px] uppercase tracking-widest text-gray-400 mb-1">Order Number</p>
                            <p class="text-[12px] font-bold tracking-widest text-black">#{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] uppercase tracking-widest text-gray-400 mb-1">Transaction Date</p>
                            <p class="text-[12px] text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Alamat Pengiriman --}}
                <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400 mb-4">Shipping To</h3>
                    <div class="text-[11px] leading-relaxed text-gray-600">
                        <p class="font-bold text-black uppercase tracking-widest mb-2">{{ auth()->user()->name }}</p>
                        <p>{{ auth()->user()->phone }}</p>
                        <p class="mt-2 italic font-light">{{ auth()->user()->address }}</p>
                        <p class="mt-1 font-bold uppercase text-black tracking-tighter">{{ auth()->user()->destination_name }}</p>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="bg-black text-white p-6 rounded-2xl shadow-lg">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-500 mb-4">Payment Method</h3>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <p class="text-[11px] uppercase tracking-[0.2em] font-bold">Digital Payment</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>