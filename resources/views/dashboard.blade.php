<x-app-layout>
    {{-- CSS Minimalis untuk tab --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="max-w-4xl mx-auto px-4 py-16" x-data="{ tab: 'orders' }" x-cloak>
        
        {{-- Header Dashboard --}}
        <div class="flex justify-between items-end mb-12 border-b border-gray-100 pb-8">
            <div>
                <span class="text-[10px] uppercase tracking-[0.4em] text-gray-400 block mb-2">Customer Account</span>
                <h1 class="text-2xl font-light tracking-widest uppercase italic">Hi, {{ auth()->user()->name }}</h1>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('profile.edit') }}" class="px-6 py-2 border border-gray-200 text-[9px] uppercase tracking-[0.2em] hover:bg-black hover:text-white transition rounded-full font-bold">
                    Settings
                </a>
            </div>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="mb-8 p-4 bg-[#6B6631] text-white text-[9px] uppercase tracking-[0.3em] font-bold text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="border border-gray-100 bg-white shadow-sm overflow-hidden">
            {{-- Navigation Tabs --}}
            <div class="flex border-b border-gray-100">
                <button @click="tab = 'orders'" 
                    :class="tab === 'orders' ? 'bg-black text-white' : 'text-gray-400 hover:text-black'"
                    class="flex-1 py-5 text-[10px] font-bold uppercase tracking-[0.3em] transition-all">
                    My Orders
                </button>
                <button @click="tab = 'delivery'" 
                    :class="tab === 'delivery' ? 'bg-black text-white' : 'text-gray-400 hover:text-black'"
                    class="flex-1 py-5 text-[10px] font-bold uppercase tracking-[0.3em] transition-all border-x border-gray-50">
                    Delivery Info
                </button>
                <button @click="tab = 'wishlist'" 
                    :class="tab === 'wishlist' ? 'bg-black text-white' : 'text-gray-400 hover:text-black'"
                    class="flex-1 py-5 text-[10px] font-bold uppercase tracking-[0.3em] transition-all">
                    Wishlist
                </button>
            </div>

            <div class="p-8 md:p-12">
                {{-- TAB: ORDERS --}}
                <div x-show="tab === 'orders'">
                    <div class="flex justify-between items-center mb-10">
                        <h4 class="text-[11px] font-bold uppercase tracking-[0.3em] text-gray-800">History ({{ $orders->count() }})</h4>
                    </div>

                    @if($orders->isEmpty())
                        <div class="py-20 text-center">
                            <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] mb-8 italic">No orders found.</p>
                            <a href="/" class="inline-block px-12 py-4 border border-black text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-black hover:text-white transition">
                                Shop Now
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="group border border-gray-100 p-6 hover:border-gray-300 transition-all bg-white">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                        <div>
                                            <div class="flex items-center gap-4 mb-2">
                                                <span class="text-[11px] font-bold uppercase tracking-widest text-black">#{{ $order->order_number }}</span>
                                                <span class="text-[9px] text-gray-400 uppercase tracking-widest">{{ $order->created_at->format('d/m/Y') }}</span>
                                            </div>
                                            <p class="text-[10px] text-gray-500 uppercase tracking-widest">
                                                IDR {{ number_format($order->total_amount, 0, ',', '.') }} — <span class="italic">{{ $order->status }}</span>
                                            </p>
                                        </div>
                                        <a href="{{ route('profile.orders.detail', $order->order_number) }}" 
                                           class="text-[9px] font-bold uppercase tracking-[0.3em] border-b border-black pb-1">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB: DELIVERY INFO (MULTIPLE ADDRESSES) --}}
                <div x-show="tab === 'delivery'" style="display: none;">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h4 class="text-[11px] font-bold uppercase tracking-[0.3em] text-gray-800">Shipping Addresses</h4>
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Manage your delivery locations for faster checkout.</p>
                        </div>
                        <a href="{{ route('address.create') }}" class="bg-black text-white px-8 py-3 text-[9px] font-bold uppercase tracking-[0.2em] hover:bg-[#5A5A00] transition shadow-sm">
                            + Add New Address
                        </a>
                    </div>

                    @php
                        $addresses = auth()->user()->addresses;
                    @endphp

                    @if($addresses->isEmpty())
                        <div class="py-20 text-center border-2 border-dashed border-gray-100">
                            <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] mb-4 italic">You haven't saved any addresses yet.</p>
                            <a href="{{ route('address.create') }}" class="text-[9px] font-bold uppercase border-b border-black pb-1">Create your first address</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($addresses as $address)
                                <div class="border {{ $address->is_default ? 'border-black' : 'border-gray-100' }} p-8 relative group transition-all">
                                    @if($address->is_default)
                                        <span class="absolute top-0 right-0 bg-black text-white text-[8px] px-4 py-1 uppercase tracking-[0.2em] font-bold">Default Address</span>
                                    @endif
                                    
                                    <div class="flex justify-between items-start mb-4">
                                        <h5 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#5A5A00]">
                                            {{ $address->label ?? 'Home / Office' }}
                                        </h5>
                                    </div>

                                    <div class="text-[12px] text-gray-600 leading-relaxed uppercase tracking-wider space-y-1">
                                        <p class="text-black font-bold">{{ $address->recipient_name }}</p>
                                        <p>{{ $address->phone }}</p>
                                        <p class="mt-2">{{ $address->address }}</p>
                                        <p>{{ $address->district_name }}, {{ $address->city_name }}</p>
                                        <p>{{ $address->province_name }} — {{ $address->postal_code }}</p>
                                    </div>

                                    <div class="mt-8 pt-6 border-t border-gray-50 flex items-center gap-8">
                                        @if(!$address->is_default)
                                            <form action="{{ route('address.select', $address->id) }}" method="POST">
                                                @csrf
                                                <button class="text-[9px] font-bold uppercase tracking-widest text-black hover:text-[#5A5A00] transition">
                                                    Set as Default
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Delete this address?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-[9px] font-bold uppercase tracking-widest text-red-400 hover:text-red-600 transition">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB: WISHLIST --}}
                <div x-show="tab === 'wishlist'" style="display: none;">
                    <div class="py-32 text-center">
                        <p class="text-[10px] uppercase tracking-[0.5em] text-gray-300 italic">Your curation starts here. Wishlist is empty.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>