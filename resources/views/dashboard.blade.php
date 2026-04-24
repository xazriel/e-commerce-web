<x-app-layout>
    {{-- CSS Khusus untuk Efek Halus --}}
    <style>
        [x-cloak] { display: none !important; }
        .tab-active { position: relative; color: black !important; }
        .tab-active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: black;
        }
        .address-card:hover { transform: translateY(-2px); shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    </style>

    <div class="max-w-5xl mx-auto px-6 py-20" x-data="{ tab: 'orders' }" x-cloak>
        
        {{-- Header Dashboard: Dibuat lebih elegan --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 pb-10 border-b border-gray-100">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-[9px] uppercase tracking-[0.2em] text-gray-400">
                        <li><a href="/" class="hover:text-black">Home</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-black font-bold">Account</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-light tracking-[0.15em] uppercase italic text-gray-900">
                    Welcome back, <span class="font-normal not-italic">{{ auth()->user()->name }}</span>
                </h1>
            </div>
            <div class="mt-6 md:mt-0 flex items-center gap-6">
                <a href="{{ route('profile.edit') }}" class="group flex items-center gap-2 text-[10px] uppercase tracking-[0.2em] font-bold">
                    <span class="pb-0.5 border-b border-transparent group-hover:border-black transition-all">Account Settings</span>
                    <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        {{-- Flash Message: Dibuat lebih subtle --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-10 p-5 bg-black text-white text-[10px] uppercase tracking-[0.2em] font-medium flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="opacity-50 hover:opacity-100">&times;</button>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-12">
            
            {{-- Navigation: Sidebar Style for Desktop, Tabs for Mobile --}}
            <div class="lg:w-1/4">
                <div class="sticky top-10 flex flex-row lg:flex-col border-b lg:border-b-0 border-gray-100 overflow-x-auto">
                    <button @click="tab = 'orders'" 
                        :class="tab === 'orders' ? 'border-black text-black' : 'border-transparent text-gray-400 hover:text-black'"
                        class="flex-1 lg:flex-none text-left py-4 lg:pr-8 border-b-2 lg:border-b-0 lg:border-l-2 text-[10px] font-black uppercase tracking-[0.3em] transition-all whitespace-nowrap px-4 lg:px-6">
                        Order History
                    </button>
                    <button @click="tab = 'delivery'" 
                        :class="tab === 'delivery' ? 'border-black text-black' : 'border-transparent text-gray-400 hover:text-black'"
                        class="flex-1 lg:flex-none text-left py-4 lg:pr-8 border-b-2 lg:border-b-0 lg:border-l-2 text-[10px] font-black uppercase tracking-[0.3em] transition-all whitespace-nowrap px-4 lg:px-6">
                        Shipping Address
                    </button>
                    
                </div>
            </div>

            <div class="lg:w-3/4">
                {{-- TAB: ORDERS --}}
                <div x-show="tab === 'orders'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4">
                    <div class="mb-10 flex items-center justify-between">
                        <h4 class="text-[12px] font-black uppercase tracking-[0.4em] text-black">Recent Orders <span class="ml-2 text-gray-300 font-light">({{ $orders->count() }})</span></h4>
                    </div>

                    @if($orders->isEmpty())
                        <div class="py-24 text-center border border-gray-50 bg-gray-50/30">
                            <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] mb-8 italic italic">No records to display.</p>
                            <a href="/" class="inline-block px-12 py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition">
                                Start Shopping
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="group border border-gray-100 p-8 hover:border-black transition-all duration-500 bg-white shadow-sm hover:shadow-md">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-4">
                                                <span class="text-[12px] font-bold uppercase tracking-widest text-black">Ref. {{ $order->order_number }}</span>
                                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                                <span class="text-[10px] text-gray-500 uppercase tracking-widest font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-[11px] text-black font-light tracking-widest">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                                <span class="px-3 py-1 bg-gray-100 text-[8px] font-bold uppercase tracking-[0.2em] text-gray-600 rounded-full">{{ $order->status }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('profile.orders.detail', $order->order_number) }}" 
                                           class="text-[9px] font-black uppercase tracking-[0.3em] bg-gray-50 px-6 py-3 group-hover:bg-black group-hover:text-white transition-all duration-300">
                                            Manage Order
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB: DELIVERY INFO --}}
                <div x-show="tab === 'delivery'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
                        <div>
                            <h4 class="text-[12px] font-black uppercase tracking-[0.4em] text-black">Shipping Directory</h4>
                            <p class="text-[9px] text-gray-400 uppercase tracking-[0.2em] mt-2">Default address will be used for all orders.</p>
                        </div>
                        <a href="{{ route('address.create') }}" class="w-full sm:w-auto text-center bg-black text-white px-10 py-4 text-[9px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-lg shadow-black/5">
                            + Add Address
                        </a>
                    </div>

                    @php $addresses = auth()->user()->addresses; @endphp

                    @if($addresses->isEmpty())
                        <div class="py-24 text-center border-2 border-dashed border-gray-100 rounded-xl">
                            <p class="text-[10px] text-gray-300 uppercase tracking-[0.4em] mb-6 italic">Empty Directory</p>
                            <a href="{{ route('address.create') }}" class="text-[9px] font-bold uppercase border-b-2 border-black pb-1 hover:text-gray-500 transition">Register New Address</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-8">
                            @foreach($addresses as $address)
                                <div class="address-card border {{ $address->is_default ? 'border-black ring-1 ring-black' : 'border-gray-100 shadow-sm' }} p-10 relative transition-all duration-300 bg-white">
                                    @if($address->is_default)
                                        <div class="absolute -top-3 left-10 bg-black text-white text-[8px] px-6 py-1.5 uppercase tracking-[0.3em] font-bold shadow-xl">
                                            Primary
                                        </div>
                                    @endif
                                    
                                    <div class="flex justify-between items-start mb-8">
                                        <h5 class="text-[11px] font-black uppercase tracking-[0.3em] text-black bg-gray-50 px-3 py-1">
                                            {{ $address->label ?? 'General' }}
                                        </h5>
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-8 text-[11px] text-gray-600 leading-loose uppercase tracking-[0.15em]">
                                        <div class="space-y-1">
                                            <p class="text-black font-bold text-[13px] tracking-[0.2em] mb-2">{{ $address->recipient_name }}</p>
                                            <p class="flex items-center gap-2"><span class="w-4 h-[1px] bg-gray-200"></span>{{ $address->phone }}</p>
                                        </div>
                                        <div class="space-y-1 md:border-l md:pl-8 border-gray-50">
                                            <p class="text-black font-medium leading-relaxed">{{ $address->address }}</p>
                                            <p>{{ $address->district_name }}, {{ $address->city_name }}</p>
                                            <p class="text-gray-400 font-bold">{{ $address->province_name }} — {{ $address->postal_code }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-10 pt-8 border-t border-gray-50 flex flex-wrap items-center gap-10">
                                        @if(!$address->is_default)
                                            <form action="{{ route('address.select', $address->id) }}" method="POST">
                                                @csrf
                                                <button class="text-[9px] font-black uppercase tracking-[0.2em] text-black hover:tracking-[0.3em] transition-all flex items-center gap-2">
                                                    Set Primary Address <span class="text-lg">→</span>
                                                </button>
                                            </form>
                                        @endif

                                        <div class="flex items-center gap-6 ml-auto">
                                            <a href="#" class="text-[9px] font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">Edit</a>
                                            <form action="{{ route('address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Archive this address?')">
                                                @csrf @method('DELETE')
                                                <button class="text-[9px] font-bold uppercase tracking-widest text-red-300 hover:text-red-600 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB: WISHLIST --}}
                <div x-show="tab === 'wishlist'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4">
                    <div class="py-32 text-center border border-gray-50 bg-gray-50/20">
                        <div class="mb-6 opacity-20">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-[0.5em] text-gray-400 italic">No saved items found.</p>
                        <a href="/" class="mt-8 inline-block text-[9px] font-black uppercase tracking-[0.3em] border-b-2 border-black pb-1 hover:opacity-50 transition">Discover Collections</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>