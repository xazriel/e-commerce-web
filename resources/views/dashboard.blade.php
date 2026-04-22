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

    <div class="max-w-4xl mx-auto px-4 py-16" x-data="{ tab: 'orders' }">
        
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

        @if(session('status') === 'address-updated')
            <div class="mb-8 p-4 bg-[#6B6631] text-white text-[9px] uppercase tracking-[0.3em] font-bold text-center">
                Profile Information Updated.
            </div>
        @endif

        <div class="border border-gray-100 bg-white shadow-sm overflow-hidden">
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

                {{-- TAB: DELIVERY INFO --}}
                <div x-show="tab === 'delivery'" style="display: none;">
                    <form action="{{ route('profile.address.update') }}" method="POST" class="max-w-xl space-y-8">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <div>
                                <label class="text-[9px] uppercase tracking-[0.3em] text-gray-400 block mb-3 font-bold">Contact Number</label>
                                <input type="text" name="phone" value="{{ auth()->user()->phone }}" placeholder="08..." required
                                    class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-[13px] focus:ring-0 focus:border-black transition bg-transparent">
                            </div>

                            <div>
                                <label class="text-[9px] uppercase tracking-[0.3em] text-gray-400 block mb-3 font-bold">Location Area</label>
                                <select name="destination_id" id="search-location-dashboard" class="w-full" required>
                                    @if(auth()->user()->destination_id)
                                        <option value="{{ auth()->user()->destination_id }}" selected>{{ auth()->user()->destination_name }}</option>
                                    @endif
                                </select>
                                <input type="hidden" name="destination_name" id="dashboard_destination_name" value="{{ auth()->user()->destination_name }}">
                            </div>

                            <div>
                                <label class="text-[9px] uppercase tracking-[0.3em] text-gray-400 block mb-3 font-bold">Full Address</label>
                                <textarea name="address" rows="3" placeholder="Street, Unit Number, etc." required
                                    class="w-full border-gray-200 border-x-0 border-t-0 border-b p-2 text-[13px] focus:ring-0 focus:border-black transition bg-transparent">{{ auth()->user()->address }}</textarea>
                            </div>
                        </div>

                        <div class="pt-6 flex items-center gap-6">
                            <button type="submit" class="bg-black text-white px-10 py-4 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-gray-800 transition">
                                Update Address
                            </button>
                            
                            <button type="button" @click="if(confirm('Clear address info?')) { window.location.reload(); }" class="text-[9px] uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition">
                                Clear Info
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TAB: WISHLIST --}}
                <div x-show="tab === 'wishlist'" style="display: none;">
                    <div class="py-32 text-center">
                        <p class="text-[10px] uppercase tracking-[0.5em] text-gray-300 italic">Discovery is ongoing. Your wishlist is empty.</p>
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
                placeholder: 'Search City or District...',
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