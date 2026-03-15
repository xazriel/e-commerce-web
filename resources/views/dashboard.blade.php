<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-10" x-data="{ tab: 'orders' }">
        
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-2xl font-light tracking-widest uppercase">Hi {{ auth()->user()->name }}</h1>
            <a href="{{ route('profile.edit') }}" class="px-6 py-2 border border-gray-200 text-[10px] uppercase tracking-[0.2em] hover:bg-black hover:text-white transition rounded-full">
                Settings
            </a>
        </div>

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
                <button @click="tab = 'wishlist'" 
                    :class="tab === 'wishlist' ? 'border-black text-black' : 'border-transparent text-gray-400'"
                    class="flex-1 py-4 text-[10px] font-bold uppercase tracking-[0.3em] border-b-2 transition">
                    Wishlist
                </button>
            </div>

            <div class="p-6 md:p-8">
                <div x-show="tab === 'orders'">
                    <div class="flex justify-between items-center mb-8">
                        <h4 class="text-[11px] font-bold uppercase tracking-widest">My Orders (0)</h4>
                        
                        <select class="text-[10px] border-gray-200 rounded-md uppercase tracking-wider focus:ring-0 focus:border-black">
                            <option>All Status</option>
                            <option>Unpaid</option>
                            <option>To Ship</option>
                            <option>Shipped</option>
                            <option>Completed</option>
                            <option>Cancelled</option>
                            <option>Returned</option>
                        </select>
                    </div>

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
                </div>

                <div x-show="tab === 'wishlist'" style="display: none;">
                    <div class="flex flex-col items-center py-20 text-center">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400">Your wishlist is empty</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>