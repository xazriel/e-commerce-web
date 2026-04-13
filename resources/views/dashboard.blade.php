<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            border-radius: 0.5rem;
            border-color: #e5e7eb;
            height: 45px;
            line-height: 45px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px;
            font-size: 14px;
            color: #4b5563;
        }
    </style>

    <div class="max-w-5xl mx-auto px-4 py-10" x-data="{ tab: 'orders' }">
        
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-2xl font-light tracking-widest uppercase">Hi {{ auth()->user()->name }}</h1>
            <a href="{{ route('profile.edit') }}" class="px-6 py-2 border border-gray-200 text-[10px] uppercase tracking-[0.2em] hover:bg-black hover:text-white transition rounded-full">
                Settings
            </a>
        </div>

        @if(session('status') === 'address-updated')
            <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 text-[10px] uppercase tracking-widest font-bold rounded-xl">
                Address updated successfully.
            </div>
        @endif

        <div class="mb-10 border border-gray-100 p-8 bg-white shadow-sm rounded-xl">
            <h3 class="text-[11px] font-bold uppercase tracking-[0.3em] mb-8 text-gray-500">My Vouchers</h3>
            <div class="flex flex-col items-center py-6 text-center">
                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold">No vouchers available</p>
                <p class="text-[9px] text-gray-300 mt-1">You don't have any vouchers at the moment</p>
            </div>
        </div>

        <div class="border border-gray-100 bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="flex border-b border-gray-100">
                <button @click="tab = 'orders'" 
                    :class="tab === 'orders' ? 'border-black text-black' : 'border-transparent text-gray-400'"
                    class="flex-1 py-4 text-[10px] font-bold uppercase tracking-[0.3em] border-b-2 transition">
                    Orders
                </button>
                <button @click="tab = 'delivery'" 
                    :class="tab === 'delivery' ? 'border-black text-black' : 'border-transparent text-gray-400'"
                    class="flex-1 py-4 text-[10px] font-bold uppercase tracking-[0.3em] border-b-2 transition">
                    Delivery Info
                </button>
                <button @click="tab = 'wishlist'" 
                    :class="tab === 'wishlist' ? 'border-black text-black' : 'border-transparent text-gray-400'"
                    class="flex-1 py-4 text-[10px] font-bold uppercase tracking-[0.3em] border-b-2 transition">
                    Wishlist
                </button>
            </div>

            <div class="p-6 md:p-8">
                {{-- TAB: ORDERS --}}
                <div x-show="tab === 'orders'">
                    <div class="flex justify-between items-center mb-8">
                        <h4 class="text-[11px] font-bold uppercase tracking-widest">My Orders ({{ $orders->count() }})</h4>
                        <select class="text-[10px] border-gray-200 rounded-md uppercase tracking-wider focus:ring-0 focus:border-black">
                            <option>All Status</option>
                            <option>Unpaid</option>
                            <option>To Ship</option>
                            <option>Shipped</option>
                            <option>Completed</option>
                            <option>Cancelled</option>
                        </select>
                    </div>

                    @if($orders->isEmpty())
                        <div class="flex flex-col items-center py-20 text-center">
                            <div class="w-16 h-16 bg-gray-50 flex items-center justify-center mb-6 rounded-lg">
                                <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h4 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-2 text-gray-700">No Orders Found</h4>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-8">Place an order to see it listed here.</p>
                            <a href="/" class="px-10 py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition shadow-lg">
                                Start Shopping
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="group border border-gray-100 p-5 rounded-xl hover:border-black transition-all duration-300 bg-white">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-1">
                                                <span class="text-[10px] font-bold uppercase tracking-widest text-black">#{{ $order->order_number }}</span>
                                                <span class="text-[9px] text-gray-400 uppercase tracking-widest">{{ $order->created_at->format('d M Y') }}</span>
                                            </div>
                                            <p class="text-[11px] text-gray-500 uppercase tracking-wider">
                                                Total Amount: <span class="font-bold text-black italic">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                            </p>
                                        </div>
                                        
                                        <div class="flex items-center w-full md:w-auto justify-between md:justify-end gap-6">
                                            <span class="px-4 py-1.5 text-[9px] font-bold uppercase tracking-[0.2em] rounded-full 
                                                {{ $order->status == 'paid' ? 'bg-black text-white' : 'bg-gray-100 text-gray-400 border border-gray-100' }}">
                                                {{ $order->status }}
                                            </span>
                                            
                                            <a href="{{ route('profile.orders.detail', $order->order_number) }}" 
                                               class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 group-hover:text-black transition-colors flex items-center gap-2">
                                                Details
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- TAB: DELIVERY INFO --}}
                <div x-show="tab === 'delivery'" style="display: none;">
                    <form action="{{ route('profile.address.update') }}" method="POST" class="max-w-2xl space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2 font-bold">Recipient Phone Number</label>
                                <input type="text" name="phone" value="{{ auth()->user()->phone }}" placeholder="0812..." required
                                    class="w-full border-gray-200 rounded-lg p-3 text-[13px] focus:ring-0 focus:border-black transition">
                            </div>

                            <div>
                                <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2 font-bold">Sub-district, District, City</label>
                                <select name="destination_id" id="search-location-dashboard" class="w-full" required>
                                    @if(auth()->user()->destination_id)
                                        <option value="{{ auth()->user()->destination_id }}" selected>{{ auth()->user()->destination_name }}</option>
                                    @endif
                                </select>
                                <input type="hidden" name="destination_name" id="dashboard_destination_name" value="{{ auth()->user()->destination_name }}">
                            </div>

                            <div>
                                <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2 font-bold">Address Details</label>
                                <textarea name="address" rows="3" placeholder="Street Name, House Number, etc." required
                                    class="w-full border-gray-200 rounded-lg p-3 text-[13px] focus:ring-0 focus:border-black transition">{{ auth()->user()->address }}</textarea>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="bg-[#6B6631] text-white px-10 py-3 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-black transition rounded-md">
                                Save Delivery Info
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TAB: WISHLIST --}}
                <div x-show="tab === 'wishlist'" style="display: none;">
                    <div class="flex flex-col items-center py-20 text-center">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-semibold">Your wishlist is empty</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search-location-dashboard').select2({
                placeholder: 'Type to search location (e.g. Senen)',
                minimumInputLength: 3,
                ajax: {
                    url: "{{ route('api.locations') }}",
                    dataType: 'json',
                    delay: 400,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.data.map(function (item) {
                                return { id: item.id, text: item.label };
                            })
                        };
                    }
                }
            });

            $('#search-location-dashboard').on('select2:select', function (e) {
                var data = e.params.data;
                $('#dashboard_destination_name').val(data.text);
            });
        });
    </script>   
</x-app-layout>