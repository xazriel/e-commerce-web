<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-12 md:py-20 font-sans tracking-tight text-gray-900">
    
        {{-- Header: ID, Date & Status --}}
        <div class="mb-16">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 pb-8 border-b border-gray-100">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2 font-bold">My Order</p>
                    <h1 class="text-xl font-bold tracking-[0.2em] uppercase text-gray-900">#{{ $order->order_number }}</h1>
                </div>
                <div class="text-left md:text-right space-y-2">
                    <div class="flex md:justify-end items-center gap-3">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">Status</p>
                        
                        {{-- Logika Warna Status History --}}
                        @php
                            $status = strtolower($order->status);
                            if ($status == 'completed') {
                                $statusClass = 'bg-[#5A5A00] text-white'; // Hijau Olive (Selesai)
                            } elseif (in_array($status, ['canceled', 'returned'])) {
                                $statusClass = 'bg-red-600 text-white'; // Merah (Batal/Retur)
                            } else {
                                $statusClass = 'bg-gray-900 text-white'; // Hitam (Unpaid, To Ship, Shipped)
                            }
                        @endphp

                        <span class="px-3 py-1 {{ $statusClass }} text-[9px] font-black uppercase tracking-[0.2em] rounded transition-colors duration-300">
                            {{ $order->status }}
                        </span>
                    </div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                        {{ $order->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-12">
            {{-- Items List --}}
            <section>
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-900">Order Summary</h2>
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">{{ $order->items->count() }} Item(s)</span>
                </div>
                
                <div class="space-y-8">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-4 group">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-20 bg-gray-50 overflow-hidden border border-gray-100 transition-transform group-hover:scale-105 shadow-sm">
                                @php $primaryImage = $item->product->images->where('is_primary', 1)->first(); @endphp
                                @if($item->product && $primaryImage)
                                    <img src="{{ asset('storage/' . $primaryImage->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-200 uppercase text-[8px] font-bold">No Image</div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold uppercase tracking-tight text-gray-900">{{ $item->product->name ?? 'Unknown Product' }}</p>
                                
                                {{-- Warna Produk Dinamis --}}
                                @if($item->color)
                                    <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-0.5"> {{ $item->color }}</p>
                                @endif

                                <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mt-1 font-bold">{{ $item->variant_name }}  {{ $item->quantity }}x</p>
                            </div>
                        </div>
                        <p class="text-sm font-black text-gray-900 tracking-tighter">IDR {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- Price Totals --}}
            <section class="bg-gray-50 p-10 space-y-4 border border-gray-100 rounded-sm">
                <div class="flex justify-between text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">
                    <span>Subtotal</span>
                    <span class="text-gray-900">IDR {{ number_format($order->total_amount - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">
                    <span>Shipping Fee</span>
                    <span class="text-gray-900">IDR {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-black pt-6 border-t border-gray-200">
                    <span class="uppercase tracking-[0.3em] text-[10px] text-gray-400 font-bold">Total Payment</span>
                    <span class="tracking-tighter text-gray-900 font-black">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </section>

            {{-- Order Details --}}
            <section class="pt-8 border-t border-gray-100">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-8 underline decoration-gray-200 underline-offset-8">Order Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    {{-- Column 1: Recipient --}}
                    <div class="text-sm space-y-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Recipient</p>
                        <p class="font-black uppercase tracking-tight leading-none text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-gray-500 font-bold text-[13px]">{{ auth()->user()->phone }}</p>
                        <p class="text-gray-400 text-[11px] mt-1 font-medium tracking-tight">{{ auth()->user()->email }}</p>
                    </div>

                    {{-- Column 2: Address --}}
                    <div class="text-sm space-y-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Shipping Address</p>
                        <div class="bg-gray-50/50 p-4 rounded-sm">
                            <p class="text-[13px] leading-relaxed text-gray-600 font-medium uppercase tracking-tight">
                                {{ auth()->user()->address }}
                            </p>
                        </div>
                        <p class="text-[10px] font-black pt-2 uppercase tracking-widest text-gray-900 underline underline-offset-4 decoration-gray-200">
                            {{ auth()->user()->destination_name }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- CTA --}}
            <div class="pt-20 flex flex-col items-center gap-8 text-center border-t border-gray-50">
                <a href="{{ route('dashboard') }}" 
                   style="background-color: #5A5A00;" 
                   class="group relative inline-flex items-center justify-center px-16 py-4 text-white hover:opacity-90 transition-all w-full md:w-auto shadow-sm">
                    <span class="relative text-[10px] uppercase tracking-[0.4em] font-black">Continue Shopping</span>
                </a>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-bold">
                    Need help? <a href="#" class="text-gray-900 font-black border-b border-gray-900 pb-0.5">Contact Us</a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>