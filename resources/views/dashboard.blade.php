<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .address-card:hover { transform: translateY(-2px); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #000; }
    </style>

    <div class="max-w-5xl mx-auto px-6 py-20" x-data="trackingSystem()" x-cloak>

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-16 pb-10 border-b border-gray-100">
            <div>
                <nav class="flex mb-4">
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
            <div class="mt-6 md:mt-0">
                <a href="{{ route('profile.edit') }}" class="group flex items-center gap-2 text-[10px] uppercase tracking-[0.2em] font-bold">
                    <span class="pb-0.5 border-b border-transparent group-hover:border-black transition-all">Account Settings</span>
                    <svg class="w-3 h-3 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        @if(session('success') || session('status'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="mb-10 p-5 bg-black text-white text-[10px] uppercase tracking-[0.2em] font-medium flex justify-between items-center">
            <span>{{ session('success') ?? session('status') }}</span>
            <button @click="show = false" class="opacity-50 hover:opacity-100">&times;</button>
        </div>
        @endif

        @if(session('warning'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             class="mb-10 p-5 bg-amber-50 border border-amber-200 text-amber-700 text-[10px] uppercase tracking-[0.2em] font-medium flex justify-between items-center">
            <span>{{ session('warning') }}</span>
            <button @click="show = false" class="opacity-50 hover:opacity-100">&times;</button>
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-12">

            {{-- Sidebar Nav --}}
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

                {{-- TAB: ORDER HISTORY --}}
                <div x-show="tab === 'orders'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4">

                    <div class="mb-10 flex items-center justify-between">
                        <h4 class="text-[12px] font-black uppercase tracking-[0.4em] text-black">
                            Recent Orders <span class="ml-2 text-gray-300 font-light">({{ $orders->count() }})</span>
                        </h4>
                    </div>

                    @if($orders->isEmpty())
                    <div class="py-24 text-center border border-gray-50 bg-gray-50/30">
                        <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] mb-8 italic">No records to display.</p>
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
                                    <div class="flex items-center gap-4 flex-wrap">
                                        <span class="text-[12px] font-bold uppercase tracking-widest text-black">{{ $order->order_number }}</span>
                                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">{{ $order->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <span class="text-[11px] text-black font-light tracking-widest">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </span>
                                        @php
                                            $statusColors = [
                                                'pending'   => 'bg-yellow-50 text-yellow-700',
                                                'success'   => 'bg-green-50 text-green-700',
                                                'cancelled' => 'bg-red-50 text-red-500',
                                                'shipped'   => 'bg-blue-50 text-blue-700',
                                            ];
                                            $statusColor = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600';
                                        @endphp
                                        <span class="px-3 py-1 {{ $statusColor }} text-[8px] font-bold uppercase tracking-[0.2em] rounded-full">
                                            {{ $order->status }}
                                        </span>
                                        @if($order->tracking_number)
                                        <span class="text-[9px] text-gray-400 font-mono">AWB: {{ $order->tracking_number }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    @if($order->tracking_number)
                                    <button @click="openTrackingModal('{{ $order->tracking_number }}')"
                                        class="text-[9px] font-black uppercase tracking-[0.3em] bg-white border border-black px-6 py-3 hover:bg-black hover:text-white transition-all duration-300 flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                        Track Resi
                                    </button>
                                    @endif

                                    <a href="{{ route('profile.orders.detail', $order->order_number) }}"
                                        class="text-[9px] font-black uppercase tracking-[0.3em] bg-gray-50 px-6 py-3 group-hover:bg-black group-hover:text-white transition-all duration-300 text-center">
                                        Manage Order
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- TAB: SHIPPING ADDRESS --}}
                <div x-show="tab === 'delivery'" style="display:none"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4">

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
                        <div>
                            <h4 class="text-[12px] font-black uppercase tracking-[0.4em] text-black">Shipping Directory</h4>
                            <p class="text-[9px] text-gray-400 uppercase tracking-[0.2em] mt-2">Default address will be used for all orders.</p>
                        </div>
                        <a href="{{ route('address.create') }}"
                            class="w-full sm:w-auto text-center bg-black text-white px-10 py-4 text-[9px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all">
                            + Add Address
                        </a>
                    </div>

                    @php $addresses = auth()->user()->addresses ?? collect(); @endphp

                    @if($addresses->isEmpty())
                    <div class="py-24 text-center border-2 border-dashed border-gray-100 rounded-xl">
                        <p class="text-[10px] text-gray-300 uppercase tracking-[0.4em] mb-6 italic">Empty Directory</p>
                        <a href="{{ route('address.create') }}" class="text-[9px] font-bold uppercase border-b-2 border-black pb-1 hover:text-gray-500 transition">
                            Register New Address
                        </a>
                    </div>
                    @else
                    <div class="space-y-8">
                        @foreach($addresses as $address)
                        <div class="address-card border {{ $address->is_default ? 'border-black ring-1 ring-black' : 'border-gray-100 shadow-sm' }} p-10 relative transition-all duration-300 bg-white">
                            @if($address->is_default)
                            <div class="absolute -top-3 left-10 bg-black text-white text-[8px] px-6 py-1.5 uppercase tracking-[0.3em] font-bold">
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
                                    <p class="flex items-center gap-2">
                                        <span class="w-4 h-[1px] bg-gray-200"></span>{{ $address->phone }}
                                    </p>
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
                                        Set Primary <span class="text-lg">→</span>
                                    </button>
                                </form>
                                @endif
                                <div class="flex items-center gap-6 ml-auto">
                                    <a href="{{ route('address.edit', $address->id) }}"
                                        class="text-[9px] font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('address.destroy', $address->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this address?')">
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

            </div>
        </div>

        {{-- MODAL TRACKING --}}
        <div x-show="modalOpen"
             class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             x-cloak>

            <div @click.away="modalOpen = false"
                 class="bg-white w-full max-w-2xl shadow-2xl flex flex-col max-h-[85vh]">

                {{-- Header --}}
                <div class="p-8 border-b border-gray-50 flex justify-between items-center flex-shrink-0">
                    <div>
                        <h3 class="text-[12px] font-black uppercase tracking-[0.4em] text-black">Shipment Tracker</h3>
                        <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">
                            AWB: <span class="text-black font-mono" x-text="activeAwb"></span>
                        </p>
                    </div>
                    <button @click="modalOpen = false" class="text-2xl font-light hover:rotate-90 transition-transform duration-300">&times;</button>
                </div>

                {{-- Body --}}
                <div class="p-8 overflow-y-auto custom-scrollbar flex-1">

                    {{-- Loading --}}
                    <div x-show="loading" class="py-20 text-center">
                        <div class="inline-block w-8 h-8 border-[3px] border-black border-t-transparent rounded-full animate-spin"></div>
                        <p class="mt-6 text-[9px] uppercase tracking-[0.3em] text-gray-400 animate-pulse">Retrieving logistics data...</p>
                    </div>

                    {{-- Data --}}
                    <div x-show="!loading && trackingData">
                        {{-- Status Badge --}}
                        <div class="mb-6 p-4 bg-gray-50 border-l-4 border-black">
                            <p class="text-[8px] uppercase tracking-widest text-gray-400 mb-1">Current Status</p>
                            <p class="text-[13px] font-bold uppercase tracking-widest text-black" x-text="trackingData?.status"></p>
                            <p class="text-[10px] text-gray-500 mt-1" x-text="trackingData?.last"></p>
                        </div>

                        {{-- Timeline --}}
                        <div class="space-y-6 relative before:absolute before:inset-0 before:ml-[11px] before:h-full before:w-[1px] before:bg-gray-100">
                            <template x-for="(h, index) in trackingData?.history" :key="index">
                                <div class="relative flex items-start gap-6">
                                    <div :class="index === 0 ? 'bg-black ring-4 ring-black/10' : 'bg-gray-200'"
                                         class="absolute left-0 w-[24px] h-[24px] rounded-full border-4 border-white z-10 flex items-center justify-center flex-shrink-0">
                                        <div x-show="index === 0" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                    </div>
                                    <div class="ml-10 pb-2">
                                        <p :class="index === 0 ? 'text-black font-bold' : 'text-gray-400'"
                                           class="text-[10px] uppercase tracking-widest font-mono" x-text="h.date"></p>
                                        <p :class="index === 0 ? 'text-gray-900' : 'text-gray-500'"
                                           class="text-[11px] mt-1 leading-relaxed" x-text="h.desc"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Error --}}
                    <div x-show="!loading && !trackingData" class="py-10 text-center">
                        <p class="text-[10px] text-red-400 uppercase tracking-widest italic" x-text="errorMessage || 'Data tidak ditemukan.'"></p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-6 border-t border-gray-50 bg-gray-50/50 flex-shrink-0">
                    <button @click="modalOpen = false"
                        class="w-full py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition">
                        Close Tracker
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
    function trackingSystem() {
        return {
            tab: 'orders',
            modalOpen: false,
            loading: false,
            activeAwb: '',
            trackingData: null,
            errorMessage: '',

            async openTrackingModal(awb) {
                this.activeAwb   = awb;
                this.modalOpen   = true;
                this.loading     = true;
                this.trackingData = null;
                this.errorMessage = '';

                try {
                    const res    = await fetch(`/profile/track/${awb}`);
                    const result = await res.json();

                    if (result.success) {
                        this.trackingData = {
                            status:  result.status,
                            last:    result.last || '',
                            history: result.history || [],
                        };
                    } else {
                        this.errorMessage = result.message || 'Resi tidak ditemukan.';
                    }
                } catch (err) {
                    this.errorMessage = 'Gagal terhubung ke sistem tracking.';
                } finally {
                    this.loading = false;
                }
            }
        };
    }
    </script>
</x-app-layout>